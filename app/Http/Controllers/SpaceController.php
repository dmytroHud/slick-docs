<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpaceRequest;
use App\Models\Space;
use Illuminate\View\View;

class SpaceController extends Controller
{
    public function create(): View
    {
        return view('pages.space.create');
    }

    public function store(StoreSpaceRequest $request)
    {

        $data = $request->validated();
        $space = Space::create([
            'title' => $data['space-name'],
            'slug' => \Str::slug($data['space-name']),
            'description' => $data['space-description']
        ])->attachUsers($data['selected-users']);

        if ($request->hasFile('space-image') && $request->file('space-image')->isValid()) {
            $space->clearMediaCollection('space_images');
            $space->addMediaFromRequest('space-image')->toMediaCollection('space_images');
        }

        return redirect(route('space.create'));
    }

    public function update() {

    }

    public function all() {
        
    }
}
