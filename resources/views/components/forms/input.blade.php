<div {!! $attributes->merge(['class' => 'flex flex-col max-w-sm font-medium text-sm text-gray-700']) !!}>
    @if(isset($label))
        <label for="{{$name}}">
            {{$label}}
        </label>
    @endif

    <input class="{{$inputClasses}}"
           type="{{$type}}"
           name="{{$name}}"
            {{$slot}}
    >
    <x-forms.error :messages="$errors->get($name)" class="mt-2"/>
</div>
