<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Space') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 sm:rounded-lg">
            <x-forms.form method="post"
                          class="space-y-6"
                          action="{{ route('space.store') }}"
                          enctype="multipart/form-data">

                <livewire:forms.file-upload name="space-image"/>
                <x-forms.input label="Name" name="space-name"/>
                <x-forms.textarea label="Description" name="space-description">
                </x-forms.textarea>
                <livewire:forms.users-select name="selected-users"/>

                <x-primary-button>
                    {{ __('Save') }}
                </x-primary-button>

            </x-forms.form>
        </div>
    </div>
</x-app-layout>
