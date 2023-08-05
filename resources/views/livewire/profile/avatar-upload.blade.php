@php
    $avatar_url = !empty($avatar) ? $avatar->temporaryUrl() : $currentAvatar
@endphp

<div>
    <label for="avatar"
           class="px-4 py-2 bg-cyan-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-800 focus:bg-cyan-800 active:bg-cyan-900 transition ease-in-out duration-150">
        {{__($currentAvatar ? "Update Avatar" : "Upload Avatar")}}
        <input type="file"
               name="avatar"
               id="avatar"
               wire:model="avatar"
               class="hidden"
               accept="image/png, image/jpeg, image/jpg"
        >
    </label>

    @if ($avatar_url)
        <div class="preview mt-3">
            <img src="{{$avatar_url}}" alt="preview_image" class="max-w-sm">
        </div>
    @endif

    @error($avatar)
    <p>
        {{$message}}
    </p>
    @enderror
</div>
