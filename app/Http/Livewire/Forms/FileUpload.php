<?php

namespace App\Http\Livewire\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;

class FileUpload extends Component
{
    use WithFileUploads;

    public $image;
    public $currentImage;
    public $name;

    public function mount(string $name, string $currentImage = '')
    {
        $this->name = $name;
        $this->currentImage = $currentImage;
    }

    public function render()
    {
        return view('livewire.forms.file-upload');
    }
}
