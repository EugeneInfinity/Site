@php
    $field_name = $field_name ?? '';
    $value = isset($value) ? $value : '';
@endphp

<div class="form-group {{ $errors->has($field_name) ? 'has-error' : ''}}">
    @isset($label)
    <label for="{{ $field_name }}" class="control-label">{{ $label }}</label>
    @endisset
    <input class="form-control"
           placeholder="Например: Яблуко"
           type="text"
           name="{{ $field_name }}"
           value="{{ old($field_name, $value) }}"
           id="{{ $field_name }}"
    >
    {!! $errors->first($field_name, '<p class="help-block">:message</p>') !!}
</div>

{{--
@include('admin.fields.field-text', [
    'label' => 'Название',
    'field_name' => 'name',
    'value' => isset($term) ? $term->name : '',
])
--}}