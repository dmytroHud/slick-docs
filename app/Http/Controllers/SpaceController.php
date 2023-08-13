<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SpaceController extends Controller
{
    public function create(): View
    {
        return view('pages.space.create');
    }

    public function store(Request $request)
    {

        return redirect('space.create');
    }
}
