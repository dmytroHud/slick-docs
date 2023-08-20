<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get user custom avatar if exist
     * @return string
     */
    public function getUserAvatar(): string
    {
        $media = $this->getFirstMedia('avatars');
        if ( ! empty($media)) {
            return $media->getFullUrl();
        }

        return '';
    }

    /**
     * Get default avatar
     * @return string
     */
    public function getDefaultAvatar(): string
    {
        return asset('storage/default/avatar.jpg');
    }

    /**
     * Get user custom avatar or default
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->getUserAvatar() ?: $this->getDefaultAvatar();
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }
}
