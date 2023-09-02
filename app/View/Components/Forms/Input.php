<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public string $inputClasses = 'mt-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full';
    public string $inputAttrs;
    public string $label;
    public string $name;
    public string $type;
    public string|int|float $value;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name,
        string $type = 'text',
        string $label = '',
        string|int|float $value = '',
        string $inputClasses = '',
        string $inputAttrs = ''
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->inputClasses = !empty($inputClasses) ? $this->inputClasses.' '.$inputClasses : $this->inputClasses;
        $this->inputAttrs = $inputAttrs;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.input');
    }
}
