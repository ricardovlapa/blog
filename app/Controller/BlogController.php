<?php

namespace App\Controller;

use App\Model\PostRepository;
use App\Model\TagRepository;

class BlogController extends BaseController
{
    private PostRepository $posts;
    private TagRepository $tags;

    public function __construct(array $site, PostRepository $posts, TagRepository $tags)
    {
        parent::__construct($site);
        $this->posts = $posts;
        $this->tags = $tags;
    }

    public function show(string $tagSlug = ''): void
    {
        $activeSlug = $this->slugifyTag($tagSlug);
        $queryTag = trim((string) ($_GET['tag'] ?? ''));
        if ($activeSlug === '' && $queryTag !== '') {
            $activeSlug = $this->slugifyTag($queryTag);
        }

        $availableTags = $this->tags->all();
        $tagLinks = $this->buildTagLinks($availableTags);
        $tagIndex = $this->indexTagsById($availableTags);

        $activeTag = $activeSlug === '' ? null : $this->findTagBySlug($availableTags, $activeSlug);
        if ($activeTag === null && $queryTag !== '') {
            $activeTag = $this->findTagByLabel($availableTags, $queryTag);
            if ($activeTag !== null) {
                $activeSlug = (string) ($activeTag['slug'] ?? $activeSlug);
            }
        }

        if ($activeTag !== null) {
            $posts = $this->posts->filterByTagId((string) ($activeTag['id'] ?? ''));
        } else {
            $posts = $this->posts->all();
        }

        $posts = $this->attachPostTagLinks($posts, $tagIndex);

        $activeLabel = (string) ($activeTag['label'] ?? '');
        if ($activeLabel === '' && $activeSlug !== '') {
            $activeLabel = $activeSlug;
        }

        $description = $activeLabel !== ''
            ? ('Artigos sobre ' . $activeLabel . '.')
            : ($this->site['description'] ?? ($this->site['tagline'] ?? ''));

        $this->render('blog', [
            'posts' => $posts,
            'tags' => $tagLinks,
            'activeTag' => $activeSlug,
            'activeTagLabel' => $activeLabel,
        ], 'Blog', [
            'description' => $description,
        ]);
    }

    private function buildTagLinks(array $tags): array
    {
        $links = [];
        foreach ($tags as $tag) {
            $links[] = [
                'id' => $tag['id'] ?? '',
                'label' => $tag['label'] ?? '',
                'slug' => $tag['slug'] ?? '',
            ];
        }

        return $links;
    }

    private function attachPostTagLinks(array $posts, array $tagsById): array
    {
        $updated = [];
        foreach ($posts as $post) {
            $tags = $post['tags'] ?? [];
            $links = [];
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    $tagId = (string) $tag;
                    if ($tagId === '' || !isset($tagsById[$tagId])) {
                        continue;
                    }
                    $tagData = $tagsById[$tagId];
                    $links[] = [
                        'label' => $tagData['label'] ?? '',
                        'slug' => $tagData['slug'] ?? '',
                    ];
                }
            }
            $post['tag_links'] = $links;
            $updated[] = $post;
        }

        return $updated;
    }

    private function slugifyTag(string $tag): string
    {
        $tag = trim($tag);
        if ($tag === '') {
            return '';
        }

        if (function_exists('iconv')) {
            $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $tag);
            if ($converted !== false) {
                $tag = $converted;
            }
        }

        $tag = strtolower($tag);
        $tag = preg_replace('/[^a-z0-9]+/', '-', $tag);
        $tag = trim($tag ?? '', '-');

        return $tag;
    }

    private function indexTagsById(array $tags): array
    {
        $indexed = [];
        foreach ($tags as $tag) {
            if (!empty($tag['id'])) {
                $indexed[$tag['id']] = $tag;
            }
        }
        return $indexed;
    }

    private function findTagBySlug(array $tags, string $slug): ?array
    {
        foreach ($tags as $tag) {
            if (($tag['slug'] ?? '') === $slug) {
                return $tag;
            }
        }
        return null;
    }

    private function findTagByLabel(array $tags, string $label): ?array
    {
        foreach ($tags as $tag) {
            if (strcasecmp((string) ($tag['label'] ?? ''), $label) === 0) {
                return $tag;
            }
        }
        return null;
    }
}
