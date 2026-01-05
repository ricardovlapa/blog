<?php

namespace App\Controller;

use App\Model\PostRepository;

class PostController extends BaseController
{
    private PostRepository $posts;

    public function __construct(array $site, PostRepository $posts)
    {
        parent::__construct($site);
        $this->posts = $posts;
    }

    public function show(string $slug): void
    {
        $post = $this->posts->findBySlug($slug);
        if (!$post) {
            $notFound = new NotFoundController($this->site);
            $notFound->show();
            return;
        }

        $title = $post['title'] ?? 'Post';
        $description = $post['excerpt'] ?? ($this->site['description'] ?? '');
        $image = $post['image'] ?? '';
        $canonical = $this->getBaseUrl() . '/post/' . $slug;
        $published = $post['date'] ?? '';
        $image = $this->absoluteImageUrl($image);

        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'description' => $description,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $canonical,
            ],
            'datePublished' => $published,
            'dateModified' => $published,
            'author' => [
                '@type' => 'Person',
                'name' => $this->site['title'] ?? '',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->site['title'] ?? '',
            ],
        ];

        if ($image !== '') {
            $articleSchema['image'] = [$image];
        }

        $this->render('post', [
            'post' => $post,
        ], $title, [
            'description' => $description,
            'image' => $image,
            'type' => 'article',
            'canonical' => $canonical,
            'jsonLd' => [$articleSchema],
        ]);
    }

    private function absoluteImageUrl(?string $image): string
    {
        $image = trim((string) $image);
        if ($image === '') {
            return '';
        }

        if (preg_match('#^https?://#', $image)) {
            return $image;
        }

        $image = ltrim($image, '/');
        return $this->getBaseUrl() . '/' . $image;
    }
}
