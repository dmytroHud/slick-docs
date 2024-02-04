<div class="flex">
    <div class="w-3/12 h-screen border-r border-gray-200">
        <div class="flex items-center bg-white px-4 py-2">
            @if($imgUrl = $space->getImageURL())
                <img class="max-w-[45px] rounded-full" src="{{$imgUrl}}" alt="Space Image">
            @endif
            <h2 class="pl-4 font-semibold text-xl text-gray-800 leading-tight">
                {{ __($space->title) }}
            </h2>
        </div>
        <div class="pt-4 pr-4 overflow-x-hidden">
            @foreach($this->buildTree($articles) as $article)
                <x-article-item
                    :article="$article"
                    :current-article="$this->currentArticle"
                />
            @endforeach
        </div>

        {{--        @dump(Str::random(40))--}}
    </div>
    <div class="w-9/12 bg-white h-screen">
        {!! $currentArticle->content !!}
    </div>
</div>
