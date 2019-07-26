<?php

namespace Zeus\Admin\Cms\Sections;

use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;

class ZeusAdminComments extends Section
{
    protected $title = 'Комментарии';
    protected $model = 'Zeus\Admin\Cms\Models\ZeusAdminComment';

    public function isCheckAccess(): bool
    {
        return config('zeusAdmin.cms_comments_check_access') ?? false;
    }

    public static function onDisplay(){
        $pluginsFields = app()['PluginsData']['CmsData']['Comments']['DisplayField'] ?? [];
        $brFields = [
            '0.01' => Column::link('id', '#'),
            '0.02' => Column::text('user_id', 'ID пользователя'),
            '0.03' => Column::text('email', 'Email'),
            '0.04' => Column::text('fio', 'ФИО'),
            '0.05' => Column::text('ip', 'IP'),
            '0.06' => Column::text('rating', 'Рейтинг'),
            '0.07' => Column::text('visible', 'Видимость'),
            '0.08' => Column::text('moderate', 'Модерация'),
            '0.09' => Column::text('created_at', 'Создан')
        ];

        $mergedFields = array_merge($pluginsFields, $brFields);
        ksort($mergedFields);

        $display = Display::table($mergedFields)->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit()
    {
        $pluginsFields = app()['PluginsData']['CmsData']['Tags']['Comments']['Edit'] ?? [];

        $brFields = [
            "0.01" => FormField::input('user_id', 'ID пользователя'),
            "0.02" => FormField::input('email', 'Email'),
            "0.03" => FormField::input('fio', 'ФИО'),
            "0.04" => FormField::input('ip', 'IP'),
            "0.05" => FormField::input('rating', 'Рейтинг')->setType('number'),
            "0.06" => FormField::bselect('visible', 'Видимость')
                ->setOptions([
                    0 => 'Нет',
                    1 => 'Да',
                ])->setRequired(true),
            "0.07" => FormField::bselect('moderate', 'Модерация')
                ->setOptions([
                    0 => 'Нет',
                    1 => 'Да',
                ])->setRequired(true),
            "0.08" => FormField::Wysiwyg('text', 'Текст')->setRequired(true),
        ];


        $mergedFields = array_merge($pluginsFields, $brFields);

        ksort($mergedFields);

        $form = Form::panel([
            FormColumn::column($mergedFields)
        ]);

        return $form;
    }

//    public function isCreatable()
//    {
//        return false;
//    }
}