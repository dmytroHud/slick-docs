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

    /**
     * @var array $fillable The list of fields that are mass assignable.
     */
    protected $fillable = [
        'slug',
        'title',
        'description'
    ];

    /**
     * Attaches users to the current instance.
     *
     * @param  array|Collection  $users  The users to be attached.
     * @param  bool  $append  Whether to append the users or replace the existing users.
     *                    If set to false, the existing users will be detached before attaching the new ones.
     *                    Defaults to false.
     *
     * @return self Returns the updated instance.
     */
    public function attachUsers(array|Collection $users, bool $append = false): self
    {
        if (!$append) {
            $this->users()->detach();
        }

        $this->users()->attach($users);

        return $this;
    }

    /**
     * Get the URL of the first image in the 'space_images' media collection.
     *
     * @return string The URL of the image, or an empty string if no image is found.
     */
    public function getImageURL(): string
    {
        $media = $this->getFirstMedia('space_images');
        if (!empty($media)) {
            return $media->getFullUrl();
        }

        return '';
    }

    /**
     * Set the image for the object if a valid image file is uploaded in the request.
     *
     * @param  \Illuminate\Http\Request  $request  The request object containing the uploaded file.
     *
     * @return bool Returns true if the image is successfully set, false otherwise.
     */
    public function maybeSetImage(Request $request): bool
    {
        if ($request->hasFile('space-image') && $request->file('space-image')->isValid()) {
            $this->setImage();

            return true;
        }

        return false;
    }

    /**
     * Clear the existing media collection 'space_images' and add a new image from the request to the collection.
     *
     * @return void
     */
    public function setImage()
    {
        $this->clearMediaCollection('space_images');
        $this->addMediaFromRequest('space-image')->toMediaCollection('space_images');
    }

    /**
     * Get the articles associated with the space.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'space_id');
    }

    /**
     * Get the ordered articles associated with the space.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orderedArticles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class)
                    ->withPivot('order')
                    ->orderBy('order');
    }

    /**
     * Get the users associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
