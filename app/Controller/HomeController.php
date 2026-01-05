<?php

namespace App\Controller;

use App\Model\PostRepository;

class HomeController extends BaseController
{
    private PostRepository $posts;

    public function __construct(array $site, PostRepository $posts)
    {
        parent::__construct($site);
        $this->posts = $posts;
    }

    public function show(): void
    {
        $featured = array_slice($this->posts->all(), 0, 3);
        $this->render('home', [
            'featured' => $featured,
        ], 'Início', [
            'description' => $this->site['description'] ?? ($this->site['tagline'] ?? ''),
        ]);
    }
}
