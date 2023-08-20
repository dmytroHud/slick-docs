<div>
    <x-forms.input type="search"
                   label="Search for user"
                   name="user-search">
        wire:model.debounce.250ms="search"
    </x-forms.input>

    @if($users)
        <div class="users-wrap flex w-full">
            <div class="users flex-col w-full max-w-sm h-full max-h-[200px] overflow-y-auto mt-2"
                 x-data
                 @scroll="onScroll(event, $wire)">
                @foreach($users as $user)
                    <div class="user w-full p-1 hover:bg-gray-50 cursor-pointer"
                         wire:click.prevent="selectUser({{$user}})">
                        {{$user->name}}
                    </div>
                @endforeach
            </div>
            @if($selectedUsers)
                <div class="selected-users w-full max-w-sm">
                    @foreach($selectedUsers as $selectedUser)
                        <div class="selected-user w-full p-1 bg-gray-50 flex justify-between">
                            <span>{{$selectedUser->name}}</span>
                            <button wire:click.prevent="unselectUser({{$selectedUser}})">
                                <svg class="h-5 w-5 text-gray-900" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <script>
            function onScroll(event, wire) {
                const {scrollHeight, scrollTop, clientHeight} = event.target;
                const scroll = scrollHeight - scrollTop - clientHeight;
                if (scroll === 0) {
                    wire.call('loadMore');
                }
            }
        </script>
    @endif

</div>
