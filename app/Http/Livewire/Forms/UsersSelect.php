<?php

namespace App\Http\Livewire\Forms;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UsersSelect extends Component
{

    const PER_PAGE = 10;
    public Collection $users;
    public Collection $selectedUsers;
    public string $search = '';
    public int $offset = 0;
    public string $prevSearch = '';

    public function mount()
    {
        $this->selectedUsers = new Collection();
        $this->users         = $this->getUsers();
    }

    public function loadMoreUsers()
    {
        $users       = $this->getUsers();
        $this->users = $this->prevSearch !== $this->search ? $users : $this->users->merge($users);
    }

    public function loadMore()
    {
        $this->offset += self::PER_PAGE;
        $this->loadMoreUsers();
    }

    public function updatedSearch()
    {
        $this->offset = 0;
        $this->loadMoreUsers();
        $this->prevSearch = $this->search;
    }

    public function selectUser(User $user)
    {
        $this->users = $this->users->filter(function (User $item) use ($user) {
            return $item->toArray() !== $user->toArray();
        });

        $this->selectedUsers->push($user);
    }

    public function unselectUser(User $user)
    {

    }

    public function render()
    {
        return view('livewire.forms.users-select');
    }

    protected function getUsers(): Collection
    {
        $selected_users = $this->selectedUsers->map(function (User $user) {
            return $user->id;
        });

        return User::when($this->search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->when(! $this->selectedUsers->isEmpty(), function (Builder $query) use ($selected_users) {
//            dd($selected_users);
        })->offset($this->offset)
                   ->limit(self::PER_PAGE)
                   ->orderByDesc('created_at')
                   ->get();
    }
}
