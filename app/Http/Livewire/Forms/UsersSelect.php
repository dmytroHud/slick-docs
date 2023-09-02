<?php

namespace App\Http\Livewire\Forms;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class UsersSelect extends Component
{

    const PER_PAGE = 10;
    public Collection $users;
    public Collection $selectedUsers;
    public string $search = '';
    public int $offset = 0;
    public string $prevSearch = '';
    public string $name;

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
        $this->users = $this->users->reject(function (User $item) use ($user) {
            return $item->is($user);
        });

        $this->selectedUsers->push($user);
        $this->selectedUsers = $this->selectedUsers->unique();
    }

    public function unselectUser(User $user)
    {
        $this->selectedUsers = $this->selectedUsers->reject(function (User $item) use ($user) {
            return $item->is($user);
        });

        $this->users->push($user);
    }

    public function render()
    {
        return view('livewire.forms.users-select');
    }

    protected function getUsers(): Collection
    {
        $query = User::query();
        $this->applySearchConditions($query);
        $this->applySelectedUsersCondition($query);

        return $query->offset($this->offset)
                     ->limit(self::PER_PAGE)
                     ->orderBy('name')
                     ->get();

    }

    protected function applySearchConditions(Builder &$query)
    {
        return $query->when($this->search, function (Builder $query, string $search) {
            $query->where(function (Builder $subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
            });
        });
    }

    protected function applySelectedUsersCondition(Builder &$query)
    {
        return $query->when(!$this->selectedUsers->isEmpty(), function (Builder $query) {
            $query->whereNotIn('id', $this->selectedUsers->pluck('id')->toArray());
        });
    }
}
