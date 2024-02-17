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

<ul class="pl-4 relative article-item {{$isChild ? 'is-child' : 'drag-root'}}"
    x-data="{
                open: true,
                getDraggingElement: () => document.querySelector('[dragging=true]'),
                isOverlap: (event) => {
                    let dropElement = event.target.parentElement;
                    let draggingElement = $data.getDraggingElement();
                    let draggingElementChildren = draggingElement.parentElement.querySelectorAll('[draggable-element]');

                    return Boolean(Array.from(draggingElementChildren).find((node) => node.isEqualNode(dropElement)));
                },
                isSameParent: (event) => {
                    let dropElement = event.target.parentElement;
                    let draggingElement = $data.getDraggingElement();

                    if (
                        dropElement.closest('.article-item.is-child') !== null
                        && draggingElement.closest('.article-item.is-child') !== null
                    ) {

                        let dropElementContainer = dropElement.closest('.article-item.is-child').parentElement.closest('.article-item');
                        let draggingElementContainer = draggingElement.closest('.article-item.is-child').parentElement.closest('.article-item');
                        return dropElementContainer.isSameNode(draggingElementContainer);
                    }
                },
                removeHighlighted: (event) => {
                    event.target.parentElement.classList.remove('article-item-drop-parent-highlighted');
                    event.target.parentElement.classList.remove('article-item-drop-no-parent-highlighted');
                    event.target.parentElement.classList.remove('article-item-drop-order-highlighted');
                },
                showOverlay: () => {
                    document.querySelectorAll('[drop-zone-parent]').forEach(element => {
                        element.classList.remove('hidden');
                    });
                    document.querySelectorAll('[drop-zone-order]').forEach(element => {
                        element.classList.remove('hidden');
                    });
                    document.querySelectorAll('[drop-zone-no-parent]').forEach(element => {
                        element.classList.remove('hidden');
                    });
                },
                hideOverlay: () => {
                    document.querySelectorAll('[drop-zone-parent]').forEach(element => {
                        element.classList.add('hidden');
                    });
                    document.querySelectorAll('[drop-zone-order]').forEach(element => {
                        element.classList.add('hidden');
                    });
                    document.querySelectorAll('[drop-zone-no-parent]').forEach(element => {
                        element.classList.add('hidden');
                    });
                },
                setParent: (draggingArticleId, newParentId) => {
                   $wire.call('setNewParent', draggingArticleId, newParentId);
                },
                setOrder: (draggingArticleId, dropArticleId) => {
                    $wire.call('setOrder', draggingArticleId, dropArticleId);
                },
                handleDrop: (e) => {
                    let dropElement = e.target;
                    let draggingElement = $data.getDraggingElement();
                    let dropArticleId = parseInt(dropElement.parentElement.getAttribute('article-id'));
                    let draggingArticleId = parseInt(draggingElement.getAttribute('article-id'));

                    if (dropElement.hasAttribute('drop-zone-no-parent')) {
                        $data.setParent(draggingArticleId, 0);
                    }
                    if (!$data.isOverlap(e) && dropElement.hasAttribute('drop-zone-parent')) {
                        $data.setParent(draggingArticleId, dropArticleId);
                    }
                    if (e.target.hasAttribute('drop-zone-order') && $data.isSameParent(e) === true) {
                        $data.setOrder(draggingArticleId, dropArticleId);
                    }

                    $data.removeHighlighted(e);
                },
                highlightItem: (e) => {
                    let dropElement = e.target.parentElement;
                    if (!$data.isOverlap(e) && e.target.hasAttribute('drop-zone-parent')) {
                        dropElement.classList.add('article-item-drop-parent-highlighted');
                    }
                    if (e.target.hasAttribute('drop-zone-no-parent')) {
                        dropElement.classList.add('article-item-drop-no-parent-highlighted');
                    }
                    if (e.target.hasAttribute('drop-zone-order') && $data.isSameParent(e) === true) {
                        dropElement.classList.add('article-item-drop-order-highlighted');
                    }
                },
                omitItem: (e) => {
                    $data.removeHighlighted(e);
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
                <span class="leading-none block w-[15px] h-[15px] relative transition-all
                    {{$hasChild ? 'article-item-dropdown-btn' : 'article-item-marker'}}"
                >
                </span>
            </div>

            <a href="#article={{$article->slug}}"
               wire:click="setCurrentArticle({{$article->id}})"
               class="block w-full p-2 pl-1"
               @click="open = true"
               draggable="false">
                    <span class="leading-none">
                        {{ $article->title }}
                    </span>
            </a>
            <div class="w-[50%] h-full absolute top-0 right-0 z-10 hidden"
                 drop-zone-parent
            >
            </div>
            <div class="w-[50%] h-full absolute top-0 left-0 z-10 hidden"
                 drop-zone-order
            >
            </div>
            <div class="absolute left-0 top-0 w-[100px] h-full z-50 translate-x-[-100%] hidden"
                 drop-zone-no-parent
            >
            </div>
        </div>

        @if($hasChild)
            @foreach($article->children as $childArticle)
                <div x-show="open">
                    <x-article-item :article="$childArticle"
                                    :is-child="true"
                                    :current-article="$currentArticle"
                    />
                </div>
            @endforeach
        @endif
    </li>
</ul>

