@php
    $classes = 'mt-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full';
    if($type == 'color'){
        $classes .= ' max-w-xs';
    }
@endphp

<div {!! $attributes->merge(['class' => 'flex flex-col max-w-sm font-medium text-sm text-gray-700']) !!}>
    @if(isset($label))
        <label for="{{$name}}">
            {{$label}}
        </label>
    @endif

    <input class="{{$classes}}"
           type="{{$type}}"
           name="{{$name}}"
    >

    <x-forms.error :messages="$errors->get($name)" class="mt-2"/>
</div>
