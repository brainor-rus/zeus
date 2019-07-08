@if($showTopButtons)
    <div class="row pt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                    <a @click.prevent="$emit('redirectTo',$event)" href="{{ $pluginData['redirectUrl'] ?? '/'.config("zeusAdmin.admin_url").'/' . $sectionName}}" class="btn btn-secondary">Отмена</a>
                </div>
            </div>
        </div>
    </div>
@endif
<form @submit.prevent="$emit('fireAction',$event)"
      id="{{ $sectionName }}-edit-form"
        action={{ $action == 'edit' ? "/".config('zeusAdmin.admin_url')."/" . $sectionName . "/" . $id . "/edit-action" : "/".config('zeusAdmin.admin_url')."/" . $sectionName . "/create-action"}}
        method="post">
    @csrf
    <input type="hidden" name="pluginData[deleteUrl]" value="{{ $pluginData['deleteUrl'] ?? null }}">
    <input type="hidden" name="pluginData[redirectUrl]" value="{{ $pluginData['redirectUrl'] ?? null }}">
    <input type="hidden" name="pluginData[sectionPath]" value="{{ $pluginData['sectionPath'] ?? null }}">

    <div class="row">
        @foreach($columns as $column)
            <div class="{{ $column->getClass() }}">
                @foreach($column->getFields() as $field)
                    @php
                        $currentRow = method_exists($field, 'getRow') && !empty($field->getRow()) ? $field->getRow() : $model;
                    @endphp

                    @if($field instanceof \Zeus\Admin\SectionBuilder\Form\Panel\Fields\Related)
                        @php
                            $relatedRows = $currentRow->{ $field->getName() } ?? null;
                        @endphp
                        {!! $field->render($relatedRows) !!}
                    @elseif($field instanceof \Zeus\Admin\SectionBuilder\Form\Panel\Fields\Gallery)
                        {!! $field->render($model) !!}
                    @else
                        @php
                            $value = $currentRow->{ $field->getName() } ?? null;
                            if($value instanceof Countable)
                            {
                                $value = $value->pluck('id')->toArray();
                            }
                        @endphp
                        {!! $field->render($value) !!}
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
    @if($showButtons)
        <div class="row pt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                        <a @click.prevent="$emit('redirectTo',$event)" href="{{ $pluginData['cancelUrl'] ?? '/'.config("zeusAdmin.admin_url").'/' . $sectionName}}" class="btn btn-secondary">Отмена</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</form>