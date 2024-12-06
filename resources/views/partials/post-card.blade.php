@props(['post'])

@php
    $authorUrl = route('user.show', $post->author->id);
    $postUrl = route('post.show', $post->id);
@endphp

<article class="post-card card px-6 w-full grid grid-cols-[auto_1fr_auto] items-center content-start" data-post-id="{{ $post->id }}">
    <a href="{{ $authorUrl }}">
        <img src="{{ $post->author->getProfilePicture() }}" alt="{{ $post->author->name }}"
            class="w-12 h-12 rounded-full object-cover">
    </a>

    <div class="ms-4 flex flex-col">
        <p class="text-base/4 font-medium select-none"><a href="{{ $authorUrl }}">{{ $post->author->name }}</a></p>
        <p class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400 select-none"><a
                href="{{ $authorUrl }}">{{ '@' . $post->author->handle }}</a>{{ ' • ' . $post->creation_timestamp->diffForHumans() }}
        </p>
    </div>
    
    <div class="ms-4 -me-3 flex">
        @if ($post->is_announcement)
            @include('partials.icon-button', ['iconName' => 'pin', 'label' => 'Announcement', 'type' => 'transparent'])
        @endif
        <div class="dropdown">
            @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
            <div class="hidden">
                <div>
                    @include('partials.dropdown-item', ['icon' => 'message-circle', 'text' => 'See Post', 'anchorUrl' => route('post.show', $post->id)])
                    @if(Auth::check() && Auth::id() === $post->author->id)
                        @include('partials.dropdown-item', ['icon' => 'pencil', 'text' => 'Edit Post', 'anchorUrl' => route('post.edit', $post->id)])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 col-span-3">
        <h1 class="font-bold text-xl"><a href="{{ $postUrl }}">{{ $post->title }}</a></h1>
        <p class="whitespace-pre-wrap text-pretty break-words">{{ str_replace("\\n", "\n", $post->text) }}</p>
    </div>
    
    <div class="-ms-3 col-span-3 grid grid-cols-[auto_auto_auto_1fr_50%] items-center">
        @if(Auth::check() && Auth::id() !== $post->author->id)
            <button aria-label="Like" class="p-3 .btn-transparent like-button {{ $post->likedBy(Auth::user()) ? 'liked' : '' }}">
                @include('partials.icon', ['name' => 'heart'])
                @include('partials.icon', ['name' => 'filled-heart'])
            </button>
        @else
            <button aria-label="Like" class="p-3 .btn-transparent like-button" disabled>
                @include('partials.icon', ['name' => 'heart'])
            </button>
        @endif
        <p class="me-3 font-medium select-none">{{ $post->likes }}</p>
        @include('partials.icon-button', [
            'iconName' => 'message-square-text',
            'label' => 'Comments',
            'type' => 'transparent',
            'anchorUrl' => $postUrl,
        ])
        <p class="font-medium select-none">{{ $post->comments }}</p>
        <div class="select-none text-end break-keep">
            @foreach ($post->tags as $tag)
                {{-- TODO: Add tag search results link --}}
                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ '#' . $tag->name }}</span>
            @endforeach
        </div>
    </div>
</article>
