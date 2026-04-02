<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class PostSearch extends Component
{
    /**
     * The search query.
     */
    #[Url]
    public string $query = '';

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        $posts = $this->query ? get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            's' => $this->query,
        ]) : [];

        $posts = collect($posts);

        return view('livewire.post-search', compact('posts'));
    }
}
