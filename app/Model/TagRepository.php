<?php

namespace App\Model;

class TagRepository
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function all(): array
    {
        $data = $this->load();
        $tags = [];
        foreach ($data as $tag) {
            if (!is_array($tag)) {
                continue;
            }
            $id = trim((string) ($tag['id'] ?? ''));
            $label = trim((string) ($tag['label'] ?? ''));
            $slug = trim((string) ($tag['slug'] ?? ''));
            if ($id === '' || $label === '' || $slug === '') {
                continue;
            }
            $tags[] = [
                'id' => $id,
                'label' => $label,
                'slug' => $slug,
            ];
        }

        return $tags;
    }

    public function indexById(): array
    {
        $indexed = [];
        foreach ($this->all() as $tag) {
            $indexed[$tag['id']] = $tag;
        }
        return $indexed;
    }

    public function findBySlug(string $slug): ?array
    {
        $slug = trim($slug);
        if ($slug === '') {
            return null;
        }
        foreach ($this->all() as $tag) {
            if (($tag['slug'] ?? '') === $slug) {
                return $tag;
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
