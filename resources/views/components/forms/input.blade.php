<div {!! $attributes->merge(['class' => 'flex flex-col max-w-sm font-medium text-sm text-gray-700']) !!}>
    @if(isset($label))
        <x-forms.label for="{{$name}}">
            {{$label}}
        </x-forms.label>
    @endif
    <input class="{{$inputClasses}}"
           type="{{$type}}"
           name="{{$name}}"
           id="{{$name}}"
           value="{{$value}}"
        {!! $inputAttrs !!}
    >
    <x-forms.error :messages="$errors->get($name)" class="mt-2"/>
</div>
