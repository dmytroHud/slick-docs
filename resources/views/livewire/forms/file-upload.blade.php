@php
    $image_url = !empty($image) ? $image->temporaryUrl() : $currentImage;
    $inputAttributes = 'wire:model="image" accept="image/png, image/jpeg, image/jpg"';
@endphp

<div>
    <x-forms.label for="{{$name}}"
                   class="px-4 py-2 bg-cyan-600 w-min cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-800 focus:bg-cyan-800 active:bg-cyan-900 transition ease-in-out duration-150"
    >
        {{$currentImage ? 'Update' : 'Upload'}}
    </x-forms.label>
    <x-forms.input type="file"
                   name="{{$name}}"
                   input-classes="hidden"
                   :input-attributes="$inputAttributes"
    />

    @if ($image_url)
        <div class="preview mt-3">
            <img src="{{$image_url}}" alt="preview_image" class="max-w-xs">
        </div>
    @endif

    <x-forms.error :messages="$errors->get('{{$name}}')" class="mt-2"/>
</div>
