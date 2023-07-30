<div>
    <label for="profileImage"
           class="px-4 py-2 bg-cyan-600 cursor-pointer border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-800 focus:bg-cyan-800 active:bg-cyan-900 transition ease-in-out duration-150">
        Upload Image
        <input type="file"
               name="profileImage"
               id="profileImage"
               wire:model="profileImage"
               wire:change="preview"
{{--               class="hidden"--}}
               accept="image/png, image/jpeg">
    </label>
    @if ($profileImage)
        <div class="preview mt-3">
            <img src="{{$profileImage->temporaryUrl()}}" alt="preview_image" class="max-w-sm">
        </div>
    @endif
    @error($profileImage)
    <p>
        {{$message}}
    </p>
    @enderror
</div>
