<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

class Article extends Model
{
    use HasFactory;

    /**
     * The $fillable array represents the attributes that are mass assignable.
     *
     * These attributes can be set in bulk using the `fill` method.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
        'author_id',
        'space_id',
        'parent_id',
        'status'
    ];

    /**
     * Get the author of the current instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the space that this object belongs to.
     *
     * @return BelongsTo The relationship instance.
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * Retrieves the order of the article in the article_space pivot table.
     *
     * @return int|null The order of the article if it exists, null otherwise.
     */
    public function getOrder(): int|null
    {
        $pivotData = DB::table('article_space')
                       ->select('order')
                       ->where('article_id', $this->id)
                       ->first();

        if ($pivotData) {
            return $pivotData->order;
        }

        return null;
    }

    /**
     * Update the order of an article in the article_space table.
     *
     * @param  int  $newOrder  The new order value for the article.
     *
     * @return void
     */
    public function updateOrder($newOrder): void
    {
        DB::table('article_space')->where('article_id', '=', $this->id)->update(['order' => $newOrder]);
    }

    /**
     * Get the parent article of this article, if it exists.
     *
     * @return \Illuminate\Database\Eloquent\Model|null The parent article model, if it exists. Otherwise, null.
     */
    public function parent(): Article|null
    {
        return $this->findOrFail($this->parent_id);
    }

    /**
     * Get the children of the current model instance.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Article>
     */
    public function children(): Collection
    {
        return $this->where('parent_id', '=', $this->id)->get();
    }

    protected static function newFactory(): Factory
    {
        return ArticleFactory::new();
    }
}
