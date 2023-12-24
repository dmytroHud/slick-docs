<?php

namespace App\Http\Livewire\Forms;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class UsersSelect extends Component
{

    /**
     * The constant PER_PAGE represents the number of items to display per page.
     *
     * @var int
     */
    const PER_PAGE = 10;
    /**
     * @var array $users An array containing user data.
     */
    public Collection $users;
    /**
     * @var array $selectedUsers Contains the selected users
     */
    public Collection $selectedUsers;
    /**
     * @var string $search Holds the value of the search query
     */
    public string $search = '';
    /**
     * The offset used for pagination.
     *
     * This variable stores the offset value used for pagination purposes.
     * Pagination is used to divide a large set of data into smaller, more manageable chunks.
     * The offset determines the starting point of the data set to retrieve.
     *
     * @var int $offset The offset value.
     */
    public int $offset = 0;
    /**
     * Represents the previous search value.
     *
     * @var string $prevSearch The previous search value.
     */
    public string $prevSearch = '';
    /**
     * The name of the variable.
     *
     * @var string
     */
    public string $name;

    /**
     * Mount the component.
     *
     * @param  Collection  $users  The collection of users to be mounted. Defaults to an empty collection if not provided.
     *
     * @return void
     */
    public function mount(Collection $users = new Collection())
    {
        $this->selectedUsers = $users;
        $this->users         = $this->getUsers();
    }

    /**
     * Loads more users and updates the current list of users.
     *
     * @return void
     */
    public function loadMoreUsers()
    {
        $users       = $this->getUsers();
        $this->users = $this->prevSearch !== $this->search ? $users : $this->users->merge($users);
    }

    /**
     * Increases the offset by the number of items per page and loads more users.
     *
     * @return void
     */
    public function loadMore()
    {
        $this->offset += self::PER_PAGE;
        $this->loadMoreUsers();
    }

    /**
     * Updates the search functionality.
     *
     * This method is triggered when the `search` property is updated. It performs the following actions:
     * 1. Resets the offset to 0, indicating that the first page of search results should be displayed.
     * 2. Loads more users based on the updated search criteria.
     * 3. Stores the previous search value in the `prevSearch` property.
     *
     * @return void
     */
    public function updatedSearch()
    {
        $this->offset = 0;
        $this->loadMoreUsers();
        $this->prevSearch = $this->search;
    }

    /**
     * Selects a user and updates the list of selected users.
     *
     * @param  User  $user  The user to be selected.
     *
     * @return void
     */
    public function selectUser(User $user)
    {
        $this->users = $this->users->reject(function (User $item) use ($user) {
            return $item->is($user);
        });

        $this->selectedUsers->push($user);
        $this->selectedUsers = $this->selectedUsers->unique();
    }

    /**
     * Unselects a user from the selectedUsers collection and pushes it to the users collection.
     *
     * @param  User  $user  The user to be unselected.
     *
     * @return void
     */
    public function unselectUser(User $user)
    {
        $this->selectedUsers = $this->selectedUsers->reject(function (User $item) use ($user) {
            return $item->is($user);
        });

        $this->users->push($user);
    }

    /**
     * Render the view for the form to select users.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory The rendered view for the form to select users.
     */
    public function render()
    {
        return view('livewire.forms.users-select');
    }

    /**
     * Retrieves a collection of users.
     *
     * @return Collection A collection of users.
     */
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

    /**
     * Apply search conditions to the query.
     *
     * @param  \Illuminate\Database\Query\Builder &$query  The query builder object.
     *
     * @return void
     */
    protected function applySearchConditions(Builder &$query)
    {
        return $query->when($this->search, function (Builder $query, string $search) {
            $query->where(function (Builder $subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
            });
        });
    }

    /**
     * Apply selected users condition to the query.
     *
     * @param  \Illuminate\Database\Query\Builder &$query  The query builder instance.
     *
     * @return void
     */
    protected function applySelectedUsersCondition(Builder &$query)
    {
        return $query->when(!$this->selectedUsers->isEmpty(), function (Builder $query) {
            $query->whereNotIn('id', $this->selectedUsers->pluck('id')->toArray());
        });
    }
}
