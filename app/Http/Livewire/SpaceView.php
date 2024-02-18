<?php

/**
 * Class SpaceView
 * @package App\Http\Livewire
 *
 * This component is responsible for displaying a space and its articles.
 */

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\Space;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SpaceView extends Component
{

    /**
     * Represents a Space object.
     *
     * @var string $space The name of the space.
     */
    public Space $space;
    /**
     * Array of articles.
     *
     * @var Collection<Article>
     */
    public Collection $articles;

    public Article $currentArticle;

    protected $rules = [
        'currentArticle.title' => 'string'
    ];

    /**
     * Mount the component with the given space
     *
     * @param  Space  $space  The space instance to be mounted.
     *
     * @return void
     */
    public function mount(Space $space)
    {
        $this->space = $space;
        $this->articles = $space->orderedArticles;
        $this->currentArticle = $this->getCurrentArticleSlugFromURL()
            ? $this->articles->firstWhere('slug', $this->getCurrentArticleSlugFromURL())
            : new Article();
    }

    public function addNewArticle()
    {
        Article::factory()->create([
            'space_id' => $this->space->id,
            'title' => 'Untitled'
        ]);

        $this->fetchArticles();
    }

    public function updatedCurrentArticle($value)
    {
        $this->currentArticle->save();
        $index = $this->articles->search(fn(Article $article) => $article->id === $this->currentArticle->id);
        $this->articles[$index] = $this->currentArticle;
    }

    /**
     * Sets a new parent for the given dragging article.
     *
     * @param  int  $draggingArticleId  The ID of the dragging article.
     * @param  int  $newParentId  The ID of the new parent. Default is 0.
     *
     * @return void
     */
    public function setNewParent(int $draggingArticleId, int $newParentId = 0)
    {
        if ($draggingArticleId === $newParentId) {
            return;
        }

        $article = Article::find($draggingArticleId);
        $article->parent_id = $newParentId;
        $article->save();
        $this->fetchArticles();
    }

    public function setOrder(int $draggingArticleId, int $dropArticleId)
    {

        DB::beginTransaction();
        try {
            $dropArticle = Article::findOrFail($dropArticleId);
            $draggingArticle = Article::findOrFail($draggingArticleId);
            $dropArticleOrder = $dropArticle->getOrder() + 1;

            $draggingArticle->updateOrder($dropArticleOrder);

            DB::table('article_space')
              ->where('space_id', '=', $this->space->id)
              ->where('order', '>=', $dropArticleOrder)
              ->where('article_id', '!=', $draggingArticleId)
              ->increment('order');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        $this->fetchArticles();
    }

    /**
     * Builds a tree structure from a collection of articles.
     *
     * @param  Collection  $articles  The collection of articles.
     * @param  int|null  $parentId  The parent id to start building the tree from. Defaults to null.
     *
     * @return Collection The tree structure as a collection of articles.
     */
    public function buildTree(Collection $articles, int $parentId = null): Collection
    {
        $tree = [];

        foreach ($articles as $article) {
            if ($article->parent_id == $parentId) {
                $children = $this->buildTree($articles, $article->id);

                if ($children) {
                    $article->children = $children;
                }

                $tree[] = $article;
            }
        }

        return new Collection($tree);
    }

    /**
     * Sets the current article based on the given article slug.
     *
     * @param  string  $articleSlug  The slug of the article.
     *
     * @return void
     */
    public function setCurrentArticle(string $articleSlug): void
    {
        $this->currentArticle = $this->articles->firstWhere('slug', $articleSlug);
    }

    /**
     * Renders the view for the space-view component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.space-view');
    }

    protected function fetchArticles()
    {
        $this->articles = $this->space->orderedArticles()->get();
    }

    protected function getCurrentArticleSlugFromURL()
    {
        return request()->get('article') ? : null;
    }
}
