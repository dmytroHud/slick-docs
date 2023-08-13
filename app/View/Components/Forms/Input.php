<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{

    public string $label;
    public string $name;
    public string $type;

    /**
     * Create a new component instance.
     */
    public function __construct(string $label, string $name, string $type = 'text')
    {
        $this->label = $label;
        $this->name  = $name;
        $this->type  = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.input');
    }
}
