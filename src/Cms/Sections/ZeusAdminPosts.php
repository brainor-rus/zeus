<?php

namespace Zeus\Admin\Cms\Sections;

use Zeus\Admin\Cms\Helpers\TemplatesHelper;
use Zeus\Admin\Cms\Models\ZeusAdminPost;
use Zeus\Admin\Cms\Models\ZeusAdminTag;
use Zeus\Admin\Cms\Models\ZeusAdminTerm;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zeus\Admin\SectionBuilder\Meta\Meta;

class ZeusAdminPosts extends Section
{
    protected $title = 'Записи';
    protected $model = 'Zeus\Admin\Cms\Models\ZeusAdminPost';

    public static function onDisplay(){
//        $meta = new Meta;
//        $meta->setStyles([
//            '' => ''
//        ])->setScripts([
//            'head' => [],
//            'body' => [
//                'test' => asset('js/test.js')
//            ]
//        ]);

        $pluginsFields = app()['PluginsData']['CmsData']['Posts']['DisplayField'] ?? [];
        $brFields = [
            '0.01' => Column::text('id', '#'),
            '0.02' => Column::link('title', 'Заголовок'),
            '0.03' => Column::text('description', 'Краткое описание'),
            '0.04' => Column::text('tags.title', 'Метки'),
            '0.05' => Column::text('categories.title', 'Рубрики'),
            '0.06' => Column::text('status', 'Статус'),
            '0.07' => Column::text('created_at', 'Дата создания'),
            '0.08' => Column::text('published_at', 'Дата публикации'),
        ];

        $mergedFields = array_merge($pluginsFields, $brFields);
        ksort($mergedFields);

        $display = Display::table($mergedFields)
//            ->setMeta($meta)
            ->setPagination(10);

        return $display->setScopes(['posts']);
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit($id)
    {
//        $meta = new Meta;
//        $meta->setStyles([
//            '' => ''
//        ])->setScripts([
//            'head' => [],
//            'body' => [
//                'test' => asset('js/test.js')
//            ]
//        ]);

        $pluginsFieldsLeft = app()['PluginsData']['CmsData']['Posts']['EditField']['Left'] ?? [];
        $pluginsFieldsRight = app()['PluginsData']['CmsData']['Posts']['EditField']['Right'] ?? [];

        $cur_page = $id ? ZeusAdminPost::with('ancestors')->where('id', $id)->first()->toArray() : null;

        $templates = TemplatesHelper::getTemplates('post');

        $brFieldsLeft = [
            '0.01' => FormField::input('title', 'Заголовок')->setRequired(true),
            '0.02' => FormField::textarea('description', 'Краткое описание')->setRows(3),
            '0.03' => FormField::input('slug', 'Слаг (необязательно)'),
            '0.04' => FormField::input('url', 'Ссылка ("." для автогенерации)')
                ->setRequired(true)
                ->setValue('.'),
            '0.05' => FormField::bselect('tags', 'Метки')
                ->setDataAttributes([
                    'multiple', 'data-live-search="true"'
                ])
                ->setModelForOptions(ZeusAdminTag::class)
                ->setQueryFunctionForModel(
                    function ($query) {
                        return $query->tags();
                    }
                )
                ->setDisplay('title'),
            '0.06' => FormField::bselect('categories', 'Рубрики')
                ->setDataAttributes([
                    'multiple', 'data-live-search="true"'
                ])
                ->setModelForOptions(ZeusAdminTerm::class)
                ->setQueryFunctionForModel(
                    function ($query) {
                        return $query->categories();
                    }
                )
                ->setDisplay('title'),
            '0.07' => FormField::custom(view('zeusAdmin::SectionBuilder.Form.Fields.InsertMedia.insertMedia')->with('id','input_content')),
            '0.08' => FormField::wysiwyg('content', 'Содержимое'),
        ];
        $brFieldsRight = [
            '0.01' => FormField::bselect('status', 'Статус')
                ->setOptions([
                    'draft' => 'Черновик',
                    'published' => 'Опубликовано'
                ])
                ->setDefaultSelected('published')
                ->setRequired(true),
            '0.02' => FormField::bselect('template', 'Шаблон')
                ->setDataAttributes([
                    'data-live-search="true"'
                ])
                ->setOptions($templates),
            '0.03' => FormField::bselect('comment_on', 'Комментарии')
                ->setOptions([
                    0 => 'Запрещены',
                    1 => 'Разрешены'
                ])
                ->setDefaultSelected(0)
                ->setRequired(true),
            '0.04' => FormField::datepicker('published_at', 'Дата публикации')
                ->setLanguage('ru')
                ->setFormat('yyyy-mm-dd hh:ii:ss')
                ->setClearBtn(true)
                ->setTodayBtn(true)
                ->setValue(Carbon::now())
                ->setRequired(true),
            '0.06' => FormField::custom(view('zeusAdmin::cms.partials.thumb')->with(compact('cur_page'))),
            '99.98' => FormField::hidden('thumb'),
            '99.99' => FormField::hidden('type')->setValue("post"),
        ];

        $mergedFieldsLeft = array_merge($pluginsFieldsLeft, $brFieldsLeft);
        $mergedFieldsRight = array_merge($pluginsFieldsRight, $brFieldsRight);

        ksort($mergedFieldsLeft);
        ksort($mergedFieldsRight);

        $form = Form::panel([
            FormColumn::column($mergedFieldsLeft, 'col-md-8 col-12'),
            FormColumn::column($mergedFieldsRight, 'col-md-4 col-12'),
        ]);
//            ->setMeta($meta);

        return $form;
    }

    public function afterSave(Request $request, $model = null)
    {
        $terms = [];
        $terms = array_merge($request->tags ?? [], $terms);
        $terms = array_merge($request->categories ?? [], $terms);
        $model->terms()->detach();
        $model->terms()->attach($terms);

        if($request->url == '.')
        {
            $model->url = $model->default_url;
            $model->save();
        }

        if($request->has('parent_id')) {
            $parent = ZeusAdminPost::where('id', $request->parent_id)->first();
            $parent->appendNode($model);
        } else {
            $model->saveAsRoot();
        }
    }
}