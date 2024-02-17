<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceRequest;
use App\Models\Space;
use Illuminate\Support\Str;
use Illuminate\View\Factory;
use Illuminate\View\View;

class SpaceController extends Controller
{
    /**
     * Renders the view for creating a new space.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('pages.space.create');
    }

    /**
     * Store a new space.
     *
     * @param  StoreSpaceRequest  $request  The request object containing the space data.
     *
     * @return \Illuminate\Http\RedirectResponse The redirect response to the create space page.
     */
    public function store(StoreSpaceRequest $request)
    {

        $data = $request->validated();
        $slug = $this->getSlug(Str::slug($data['space-name']));

        $space = Space::create([
            'title' => $data['space-name'],
            'slug' => $slug,
            'description' => $data['space-description']
        ])->attachUsers($data['selected-users']);

        $space->maybeSetImage($request);

        return redirect()->route('space.create');
    }

    /**
     * Retrieve the space with the given slug and display the edit view.
     *
     * @param  string  $slug  The slug of the space to edit.
     *
     * @return Factory|View
     */
    public function edit(string $slug)
    {
        return view('pages.space.edit', [
            'space' => Space::whereSlug($slug)->firstOrFail()
        ]);
    }

    /**
     * Update the space with the given slug.
     *
     * @param  string  $slug  The slug of the space to update.
     * @param  StoreSpaceRequest  $request  The request object containing the validated data.
     *
     * @return \Illuminate\Http\RedirectResponse The redirect response to the edit page of the updated space.
     */
    public function update(string $slug, StoreSpaceRequest $request)
    {
        $space = Space::whereSlug($slug)->firstOrFail();
        $data = $request->validated();

        if ($slug !== Str::slug($data['space-name'])) {
            $slug = $this->getSlug(Str::slug($data['space-name']));
        }

        $space->fill([
            'title' => $data['space-name'],
            'slug' => $slug,
            'description' => $data['space-description']
        ])->save();

        $space->attachUsers($data['selected-users']);

        $space->maybeSetImage($request);

        return redirect()->route('space.edit', ['slug' => $space->slug])->with('status', 'The space is updated');
    }

    /**
     * Retrieves a paginated list of spaces with associated users and media,
     * ordered by the creation date in descending order.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $spaces = Space::with(['users', 'media'])->OrderByDesc('created_at')->paginate(10);

        return view('pages.space.index', [
            'spaces' => $spaces
        ]);
    }

    /**
     * Fetches a single space by its slug and returns the corresponding view
     *
     * @param  string  $slug  The slug of the space
     *
     * @return Factory|View
     */
    public function single($slug)
    {
        $space = Space::whereSlug($slug)->with(['users', 'media', 'articles'])->firstOrFail();

        return view('pages.space.single', [
            'space' => $space
        ]);
    }

    /**
     * Generates a unique slug for a space.
     *
     * @param  string  $slug  The original slug value.
     *
     * @return string The unique slug value.
     */
    protected function getSlug(string $slug): string
    {
        if (!empty(Space::whereSlug($slug)->first())) {
            $slug .= '-'.Str::random(6);
        }

        return $slug;
    }
}
