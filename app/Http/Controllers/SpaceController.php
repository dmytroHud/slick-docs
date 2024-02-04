<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceRequest;
use App\Models\Space;
use Illuminate\Support\Str;

class SpaceController extends Controller
{
    public function create()
    {
        return view('pages.space.create');
    }

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

    public function edit(string $slug)
    {
        return view('pages.space.edit', [
            'space' => Space::whereSlug($slug)->firstOrFail()
        ]);
    }

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

    public function index()
    {
        $spaces = Space::with(['users', 'media'])->OrderByDesc('created_at')->paginate(10);

        return view('pages.space.index', [
            'spaces' => $spaces
        ]);
    }

    public function single($slug)
    {
        $space = Space::whereSlug($slug)->with(['users', 'media', 'articles'])->firstOrFail();

        return view('pages.space.single', [
            'space' => $space
        ]);
    }

    protected function getSlug(string $slug): string
    {
        if (!empty(Space::whereSlug($slug)->first())) {
            $slug .= '-'.Str::random(6);
        }

        return $slug;
    }
}
