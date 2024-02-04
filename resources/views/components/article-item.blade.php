@props([
    'article' => null,
    'isChild' => false,
    'currentArticle' => false
])

@php
    $linkClasses = sprintf(
        '%s',
         $currentArticle === $article ? 'bg-gray-300' : ''
        );

    $hasChild = !$article->children->isEmpty();
@endphp

<ul class="pl-4 {{$isChild ? 'is-child' : ''}}"
    x-data="{
        open: false,
        getDraggingElement: () => document.querySelector('[dragging=true]'),
        isOverlap: (event) => {
            let dropElement = event.target.parentElement;
            let draggingElement = $data.getDraggingElement();
            let draggingElementChildren = draggingElement.parentElement.querySelectorAll('[draggable-element]');
            return Boolean(Array.from(draggingElementChildren).find((node) => node.isEqualNode(dropElement)));
        },
        showOverlay: () => {
            document.querySelectorAll('[drag-overlay]').forEach(element => {
                element.classList.remove('hidden');
            });
        },
        hideOverlay: () => {
            document.querySelectorAll('[drag-overlay]').forEach(element => {
                element.classList.add('hidden');
            });
        },
        setParent: (dropElement, draggingElement) => {
            let newParentId = parseInt(dropElement.getAttribute('article-id'));
            let draggingArticleId = parseInt(draggingElement.getAttribute('article-id'));
            $wire.call('setNewParent', newParentId, draggingArticleId);
        },
        handleDrop: (e) => {
            let dropElement = e.target.parentElement;
            let draggingElement = $data.getDraggingElement();
            if (!$data.isOverlap(e)) {
                $data.setParent(dropElement, draggingElement);
            }
        },
        highlightItem: (e) => {
            let dropElement = e.target.parentElement;
            if (!$data.isOverlap(e)) {
                dropElement.classList.add('article-item-drop-highlighted');
            }
        },
        omitItem: (e) => {
            e.target.parentElement.classList.remove('article-item-drop-highlighted');
        }
    }"
>

    <li>
        <div class="w-full flex items-center hover:bg-gray-200 transition-all leading-none relative {!! $linkClasses !!}"
             article-id="{{$article->id}}"
             draggable="true"
             draggable-element
             @dragstart="
                showOverlay();
                $event.target.setAttribute('dragging', true);
             "
             @dragend="
                $event.target.removeAttribute('dragging');
                hideOverlay();
             "
             @dragover="
                $event.preventDefault();
                highlightItem($event);
             "
             @dragleave="omitItem($event)"
             @drop="handleDrop($event)"
        >
            <div class="pl-2 article-item-icon"
                 :class="{'active': open}"
                 @click="open = !open"
                 draggable="false"
            >
                <span class="leading-none block w-[15px] h-[15px] relative transition-all {{$hasChild ? 'article-item-dropdown-btn' : 'article-item-marker'}} ">
                </span>
            </div>

            <a href="#article={{$article->slug}}"
               wire:click="setCurrentArticle({{$article->id}})"
               class="block w-full p-2 pl-1"
               @click="open = true"
               draggable="false" >
                <span class="leading-none">
                    {{ $article->title }}
                </span>
            </a>

            <div class="w-full h-full absolute top-0 left-0 z-50 hidden"
                 drag-overlay
            >
            </div>
        </div>

        @if($hasChild)
            @foreach($article->children as $childArticle)
                <div x-show="open">
                    <x-article-item :article="$childArticle" :is-child="true" :current-article="$currentArticle"/>
                </div>
            @endforeach
        @endif
    </li>
</ul>
