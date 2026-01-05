<?php

namespace App\Controller;

class AboutController extends BaseController
{
    public function show(): void
    {
        $description = 'Conhece o percurso e as ideias de ' . ($this->site['title'] ?? '');
        $this->render('about', [], 'Sobre', [
            'description' => $description,
        ]);
    }
}
