<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Space') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 sm:rounded-lg">
            @if (session('status'))
                <p class="p-4 bg-green-200 border-green-700 border-2 rounded-md text-green-900">
                    {{ session('status') }}
                </p>
            @endif

            <x-forms.form method="patch"
                          class="space-y-6"
                          action="{{ route('space.update', ['slug' => $space->slug]) }}"
                          enctype="multipart/form-data">

                <livewire:forms.file-upload name="space-image" :current-image="$space->getImageURL()"/>
                <x-forms.input label="Name" name="space-name" :value="$space->title"/>
                <x-forms.textarea label="Description" name="space-description">
                    {{ $space->description }}
                </x-forms.textarea>
                <livewire:forms.users-select name="selected-users" :users="$space->getAttachedUsers()"/>

                <x-primary-button>
                    {{ __('Update') }}
                </x-primary-button>

            </x-forms.form>
        </div>
    </div>
</x-app-layout>
