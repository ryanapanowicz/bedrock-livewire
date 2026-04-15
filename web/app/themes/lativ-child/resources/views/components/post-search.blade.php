<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component {
    /**
     * The search query.
     */
    #[Url(as: 'q', history: true)]
    public string $query = '';

    /**
     * The search results.
     *
     * @return Collection<WP_Post>
     */
    #[Computed]
    public function posts(): Collection
    {
        $posts = $this->query ? get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            's' => $this->query,
        ]) : [];

        return collect($posts);
    }
}

?>

<div class="post-search-wrapper">
    <div class="post-search-field">
        <input
                class="post-search-input"
                wire:model.live.debounce.300ms="query"
                type="search"
                placeholder="Search posts..."
        >
        <span class="post-search-loading" aria-hidden="true"></span>
    </div>
    @if ($this->query)
        <div class="post-search-results-wrapper" wire:transition="post-search-results">
            <div class="post-search-results">
                @if ($this->posts->isNotEmpty())
                    <div class="post-search-results-info">
                        <p>Found {{ $this->posts->count() }} result(s) for "{{ $this->query }}"</p>
                    </div>
                    <ul class="post-search-results-list">
                        @foreach ($this->posts as $post)
                            <li wire:key="post-search-result-{{ $post->ID }}">
                                <a href="{{ get_permalink($post->ID) }}">
                                    {{ $post->post_title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="post-search-results-info">
                        <p>No results found for "{{ $this->query }}"</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
