<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;

class MediaUpload extends Component
{
    use WithFileUploads;

    public $profileImage;

    public function preview()
    {
        $this->validate([
            'profileImage' => 'max:1024'
        ]);

        $this->profileImage->store('profileImages');
    }

    public function render()
    {
        return view('livewire.profile.media-upload');
    }
}
