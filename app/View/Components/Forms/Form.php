<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component
{
    public string $method;
    public string $baseMethod;

    public function __construct($method)
    {
        $this->method = strtoupper($method);
        $this->baseMethod = $this->method === 'GET' ? 'GET' : 'POST';
    }

    public function render(): View|Closure|string
    {
        return view('components.forms.form');
    }
}
