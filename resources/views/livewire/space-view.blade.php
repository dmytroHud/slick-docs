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
                />
            @endforeach
            <div class="pl-5">
                <a href="#"
                   class="w-full flex items-center hover:bg-gray-200 transition-all leading-none p-2"
                   wire:click.prevent="addNewArticle"
                >
                    + Add Article
                </a>
            </div>
        </div>
    </div>
    <div class="w-9/12 bg-white h-screen">
        <div class="py-6 px-4">
            <input type="text"
                   wire:model.lazy="currentArticle.title"
                   class="w-full bg-gray-100 border-transparent text-xl focus:outline-none focus:border-transparent focus:shadow-none"
                   style="box-shadow: 0 0 #0000 !important;"
            />
        </div>

        <div class="px-4 h-full">
            <div id="editor">
                {!! $currentArticle->content !!}
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.js"></script>
        <script>
            const options = {
                debug: 'info',
                modules: {
                    toolbar: true,
                },
                placeholder: 'Compose an epic...',
                theme: 'snow'
            };
            const quill = new Quill('#editor', options);
        </script>
    </div>
</div>
