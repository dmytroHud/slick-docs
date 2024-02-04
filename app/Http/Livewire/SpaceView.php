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
     * @var Collection|Article[]
     */
    public Collection $articles;

    public Article $currentArticle;

    /**
     * Mount the component with the given space.
     *
     * @param  Space  $space  The space instance to be mounted.
     *
     * @return void
     */
    public function mount(Space $space)
    {
        $this->space = $space;
        $this->articles = $space->articles;
        $this->currentArticle = $this->articles->first();
    }

    public function setNewParent($newParentId, $draggingArticleId)
    {
        if ($newParentId === $draggingArticleId) {
            return;
        }

        $article = Article::find($draggingArticleId);
        $article->parent_id = $newParentId;
        $article->save();
        $this->articles = Article::all();
    }

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

    public function setCurrentArticle(int $articleId): void
    {
        $this->currentArticle = $this->articles->firstWhere('id', $articleId);
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
}
