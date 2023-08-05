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
        $avatar = auth()->user()->getFirstMedia('avatars');
        if ( ! empty($avatar)) {
            $this->currentAvatar = $avatar->getFullUrl();
        }
    }

    public function render()
    {
        return view('livewire.profile.avatar-upload');
    }
}
