<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{

    public string $textareaClasses = 'mt-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full';
    public string $textareaAttrs;
    public string $label;
    public string $name;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $label,
        string $name,
        string $textareaClasses = '',
        string $textareaAttrs = ''
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->textareaClasses = !empty($textareaClasses) ? $this->textareaClasses.' '.$textareaClasses : $this->textareaClasses;
        $this->textareaAttrs = $textareaAttrs;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.textarea');
    }
}
