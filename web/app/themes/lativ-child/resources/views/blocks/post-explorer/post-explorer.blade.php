@php
    $template = wp_json_encode([
        [
            'core/heading',
            [
                'level' => 4,
                'content' => 'Continue Reading',
                'placeholder' => 'Add heading',
            ],
        ],
        [
            'core/paragraph',
            [
                'content' => 'See more areas of the site, or continue reading.',
                'placeholder' => 'Add details',
            ],
        ],
    ]);
@endphp
<section {!! $is_preview ? 'class="post-explorer post-explorer-shell post-explorer-block"' : wp_kses_data(get_block_wrapper_attributes(['class' => 'post-explorer post-explorer-shell post-explorer-block'])) !!}>
    <div class="post-explorer-header">
        <p class="post-explorer-eyebrow" @php acf_inline_text_editing_attrs('eyebrow'); @endphp>
            {{ get_field('eyebrow') }}
        </p>
        <h2 class="post-explorer-title" @php acf_inline_text_editing_attrs('heading'); @endphp>
            {{ get_field('heading') }}
        </h2>
        <p @php acf_inline_text_editing_attrs('description'); @endphp>
            {{ get_field('description') }}
        </p>
    </div>

    <livewire:post-explorer
            :empty-message="get_field('empty_message')"
            :filters="get_field('filters') ? array_flip(get_field('filters')) : []"
            :default-per-page="intval(get_field('default_per_page'))"
            :default-sort="get_field('default_sort')"
    />

    <div class="post-explorer-footer">
        <InnerBlocks template="{!! esc_attr($template) !!}"/>
    </div>
</section>
