<div class="form-group">
    @if(empty($relatedName))
        <label for="input_{{ $name }}">{{ $label }} @if($required) <span class="text-danger">*</span> @endif</label>
    @endif
    <textarea class="form-control"
              @if(empty($relatedName)) id="input_{{ $name }}" @endif
              name="{{ $relatedName ?? $name }}"
              cols="{{ $cols }}"
              rows="{{ $rows }}"
              @if($required) required @endif
              @if($readonly) readonly @endif
              placeholder="{{ $placeholder ?? null }}">{!! $value ?? null !!}</textarea>
    {!! $helpBlock !!}
</div>