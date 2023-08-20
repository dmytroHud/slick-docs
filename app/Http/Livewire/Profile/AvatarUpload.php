<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;

class AvatarUpload extends Component
{
    use WithFileUploads;

    public $avatar;
    public $currentAvatar;

    public function mount()
    {
        $this->currentAvatar = auth()->user()->getUserAvatar() ? : '';
    }

    public function render()
    {
        return view('livewire.profile.avatar-upload');
    }
}
