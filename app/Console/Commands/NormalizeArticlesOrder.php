<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeArticlesOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:normalize-articles-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalizing the article_space table by reordering the table to ensure a continuous sequence.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $spaces = DB::table('article_space')
                    ->distinct('space_id')
                    ->pluck('space_id');

        DB::transaction(function () use ($spaces) {
            foreach ($spaces as $space) {
                $orderCounter = 1;

                $articleSpaces = DB::table('article_space')
                                   ->where('space_id', $space)
                                   ->orderBy('order')
                                   ->get();

                foreach ($articleSpaces as $articleSpace) {
                    DB::table('article_space')
                      ->where('article_id', $articleSpace->article_id)
                      ->update(['order' => $orderCounter]);
                    $orderCounter++;
                }
            }
        });
    }
}
