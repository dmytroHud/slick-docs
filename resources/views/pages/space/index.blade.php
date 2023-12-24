<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Spaces') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 sm:rounded-lg">
            @forelse($spaces as $space)
                <div class="bg-gray-100 rounded-lg flex mb-4 p-4 items-center">
                    <div class="w-1/12">
                        <img src="{{$space->getImageURL()}}" class="rounded-full" alt="asd">
                    </div>
                    <div class="w-9/12 pl-2">
                        {{$space->title}}
                    </div>
                    <div class="w-2/12 flex row justify-end">
                        <a href="#" class="btn-primary mr-2">Enter</a>
                        <a href="{{route('space.edit', $space->slug)}}" class="btn-secondary">Edit</a>
                    </div>
                </div>

            @empty
                No spaces
            @endforelse
        </div>
    </div>
</x-app-layout>
