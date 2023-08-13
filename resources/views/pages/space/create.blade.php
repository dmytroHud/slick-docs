<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Space') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 sm:rounded-lg">
            <x-forms.form method="post" class="space-y-6">
                <x-forms.input class="123" label="Input text" name="something" />
                <x-forms.input class="123123" label="Input text" name="som123ething" type="color" />
            </x-forms.form>
            <ul class="text-sm text-red-600 space-y-1 mt-2">
                <li>The password is incorrect.</li>
            </ul>
        </div>
    </div>
</x-app-layout>
