<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{

    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => Str::random(40),
            'title' => $this->faker->jobTitle(),
            'content' => '',
            'author_id' => Auth::id(),
            'space_id' => Space::first(),
            'parent_id' => 0,
            'status' => 'draft'
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Article $article) {
            $spaceId = $article->space_id;

            $lastRecordOrderForSpace = DB::table('article_space')
                                         ->where('space_id', '=', $spaceId)
                                         ->orderByDesc('order')
                                         ->first()->order;

            DB::table('article_space')->insert([
                'space_id' => $spaceId,
                'article_id' => $article->id,
                'order' => $lastRecordOrderForSpace + 1
            ]);
        });
    }
}
