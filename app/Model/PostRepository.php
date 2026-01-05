<?php

namespace App\Model;

class PostRepository
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function all(): array
    {
        $data = $this->load();
        usort($data, function (array $a, array $b): int {
            return strcmp($b['date'] ?? '', $a['date'] ?? '');
        });
        return $data;
    }

    public function filterByTagId(string $tagId): array
    {
        $tagId = trim($tagId);
        if ($tagId === '') {
            return [];
        }

        $filtered = array_filter($this->all(), function (array $post) use ($tagId): bool {
            $tags = $post['tags'] ?? [];
            if (!is_array($tags)) {
                return false;
            }
            foreach ($tags as $postTag) {
                if ((string) $postTag === $tagId) {
                    return true;
                }
            }
            return false;
        });

        return array_values($filtered);
    }

    public function findBySlug(string $slug): ?array
    {
        foreach ($this->load() as $post) {
            if (($post['slug'] ?? '') === $slug) {
                return $post;
            }
        }
        return null;
    }

    private function load(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $raw = file_get_contents($this->file);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

}
