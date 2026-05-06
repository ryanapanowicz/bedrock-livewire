<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component {
    /**
     * The empty state message loaded from ACF.
     */
    public ?string $emptyMessage;

    /**
     * The filters loaded from ACF.
     */
    public array $filters = [];

    /**
     * The default number of posts shown per page.
     */
    public int $defaultPerPage = 6;

    /**
     * The default sort option.
     */
    public ?string $defaultSort;

    /**
     * The search query.
     */
    #[Url(as: 'q', history: true)]
    public string $query = '';

    /**
     * The selected category.
     */
    #[Url(as: 'cat', history: true)]
    public string $category = 'all';

    /**
     * The selected sort option.
     */
    #[Url(as: 'sort', history: true)]
    public string $sort = 'latest';

    /**
     * The selected posts-per-page value.
     */
    #[Url(as: 'per_page', history: true)]
    public int $perPage = 6;

    /**
     * Initialize the component configuration.
     */
    public function mount(): void
    {
        $this->emptyMessage ??= 'Try a broader search term or reset the filters.';
        $this->defaultSort ??= 'latest';

        if (!request()->has('per_page')) {
            $this->perPage = $this->defaultPerPage;
        }

        if (!request()->has('sort')) {
            $this->sort = $this->defaultSort;
        }
    }

    /**
     * The available post-categories.
     *
     * @return Collection<WP_Term>
     */
    #[Computed]
    public function categories(): Collection
    {
        return collect(get_categories([
            'taxonomy' => 'category',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ]));
    }

    /**
     * The filtered posts.
     *
     * @return Collection<WP_Post>
     */
    #[Computed]
    public function posts(): Collection
    {
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => $this->perPage,
            's' => $this->query,
        ];

        if (isset($this->filters['categories']) && $this->category !== 'all') {
            $args['category'] = (int) $this->category;
        }

        if ($this->sort === 'oldest') {
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
        } elseif ($this->sort === 'title') {
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        } else {
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
        }

        return collect(get_posts($args));
    }
}

?>

<div class="post-explorer-controls">
    <div class="post-explorer-toolbar" {!! acf_inline_toolbar_editing_attrs(['filters']) !!}>
        @isset($this->filters['search'])
            <label class="post-explorer-field">
                <span>Search</span>
                <input
                        class="post-explorer-input"
                        type="search"
                        wire:model.live.debounce.350ms="query"
                        placeholder="Search posts by title or content"
                >
            </label>
        @endisset

        @isset ($this->filters['categories'])
            <label class="post-explorer-field">
                <span>Category</span>
                <select class="post-explorer-select" wire:model.live.change="category">
                    <option value="all">All categories</option>
                    @foreach ($this->categories as $term)
                        <option value="{{ $term->term_id }}">{{ $term->name }}</option>
                    @endforeach
                </select>
            </label>
        @endisset

        @isset ($this->filters['sort'])
            <label class="post-explorer-field">
                <span>Sort</span>
                <select class="post-explorer-select" wire:model.live.change="sort">
                    <option value="latest">Newest first</option>
                    <option value="oldest">Oldest first</option>
                    <option value="title">Title A-Z</option>
                </select>
            </label>
        @endisset

        @isset ($this->filters['per_page'])
            <label class="post-explorer-field">
                <span>Show</span>
                <select class="post-explorer-select" wire:model.live.change.number="perPage">
                    <option value="3">3 posts</option>
                    <option value="6">6 posts</option>
                    <option value="9">9 posts</option>
                    <option value="12">12 posts</option>
                </select>
            </label>
        @endisset
    </div>

    <div class="post-explorer-meta">
        <p>
            <strong>{{ $this->posts->count() }}</strong> result(s)
        </p>

        <span class="post-explorer-loading" aria-hidden="true"></span>
    </div>

    <div class="post-explorer-results">
        @if ($this->posts->isNotEmpty())
            <div class="post-explorer-grid">
                @foreach ($this->posts as $post)
                    <article wire:transition class="post-explorer-card" wire:key="post-explorer-{{ $post->ID }}">
                        <a class="post-explorer-card-link" href="{{ get_permalink($post->ID) }}"
                           aria-label="Read {{ esc_attr($post->post_title) }}"></a>
                        <div class="post-explorer-card-inner">
                            <p class="post-explorer-card-meta">
                                {{ get_the_date('M j, Y', $post) }}
                            </p>
                            <h3 class="post-explorer-card-title">
                                <span>{{ $post->post_title }}</span>
                            </h3>
                            <p class="post-explorer-card-taxonomy">
                                {!! get_the_category_list(', ', '', $post) !!}
                            </p>
                            <p class="post-explorer-card-excerpt">
                                {!! wp_trim_words(wp_strip_all_tags(get_the_excerpt($post)), 24) !!}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="post-explorer-empty">
                <h3>No posts match these filters.</h3>
                <p>{{ $emptyMessage }}</p>
            </div>
        @endif
    </div>
</div>
