<div>
    <x-forms.input type="search"
                   label="Search for user"
                   name="user-search"
                   input-attributes="wire:model.debounce.250ms='search'"
    />

    @if($users)
        <div class="users-wrap flex w-full">
            <div class="users flex-col w-full max-w-sm h-full max-h-[200px] overflow-y-auto mt-2"
                 x-data
                 @scroll="onScroll(event, $wire)">
                @foreach($users as $key => $user)
                    <div class="user w-full p-1 hover:bg-gray-50 cursor-pointer"
                         wire:click.prevent="selectUser({{$user}})"
                         @click="$el.remove()"
                    >
                        {{$user->name}}
                    </div>
                @endforeach
            </div>
            @if($selectedUsers)
                <div class="selected-users w-full max-w-sm h-full max-h-[200px] overflow-y-auto">
                    @foreach($selectedUsers as $selectedUser)
                        <div class="selected-user w-full p-1 bg-gray-50 flex justify-between">
                            <span>{{$selectedUser->name}}</span>
                            <button wire:click.prevent="unselectUser({{$selectedUser}})"
                                    @click="$el.remove()">
                                <x-svgs.close/>
                            </button>
                        </div>
                    @endforeach
                </div>
                <select name="{{$name}}[]" multiple class="hidden">
                    @foreach($selectedUsers as $selectedUser)
                        <option value="{{$selectedUser->id}}" selected>{{$selectedUser->id}}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <script>
            function onScroll(event, wire) {
                const {scrollHeight, scrollTop, clientHeight} = event.target;
                const scroll = scrollHeight - scrollTop - clientHeight;
                const hasVerticalScrollbar = scrollHeight > clientHeight;
                if (hasVerticalScrollbar && scroll === 0) {
                    wire.call('loadMore');
                }
            }
        </script>
    @endif
    <x-forms.error :messages="$errors->get($name)" class="mt-2"/>
</div>
