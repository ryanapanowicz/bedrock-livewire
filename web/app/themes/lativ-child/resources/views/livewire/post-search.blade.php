<div class="post-search-wrapper">
    <div class="post-search-field">
        <input
                class="post-search-input"
                wire:model.live="query"
                type="search"
                placeholder="Search posts..."
        >
    </div>
    @if ($query)
        <div class="post-search-results-wrapper">
            <div class="post-search-results">
                @if ($posts)
                    <div class="post-search-results-info">
                        <p>Found {{ $posts->count() }} result(s) for "{{ $query }}"</p>
                    </div>
                    <ul class="post-search-results-list">
                        @foreach ($posts as $post)
                            <li>
                                <a href="{{ get_permalink($post->ID) }}">
                                    {{ $post->post_title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No results found for "{{ $query }}"</p>
                @endif
            </div>
        </div>
    @endif
</div>