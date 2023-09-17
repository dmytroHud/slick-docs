<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Space extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'slug',
        'title',
        'description'
    ];

    public function attachUsers(array|Collection $users): self
    {
        $this->users()->attach($users);

        return $this;
    }

    public function getAttachedUsers()
    {
        return $this->users()->get();
    }

    /**
     * Get space image
     * @return string
     */
    public function getImageURL(): string
    {
        $media = $this->getFirstMedia('space_images');
        if (!empty($media)) {
            return $media->getFullUrl();
        }

        return '';
    }

    public function maybeSetImage(Request $request): bool
    {
        if ($request->hasFile('space-image') && $request->file('space-image')->isValid()) {
            $this->setImage();

            return true;
        }

        return false;
    }

    public function setImage()
    {
        $this->clearMediaCollection('space_images');
        $this->addMediaFromRequest('space-image')->toMediaCollection('space_images');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'space_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
