<main class="container">
  <section class="section">
    <div class="post-layout">
      <div class="post-media">
        <?php if (!empty($post['image'])): ?>
          <div class="post-image">
            <img src="<?= image_src($post['image']) ?>" alt="<?= e($post['title'] ?? 'Imagem do artigo') ?>">
          </div>
        <?php else: ?>
          <div class="post-image post-image--empty">Sem imagem por agora</div>
        <?php endif; ?>
      </div>
      <div class="post-body">
        <div class="card">
          <p class="post-meta"><time datetime="<?= e($post['date'] ?? '') ?>"><?= e(format_date($post['date'] ?? '')) ?></time></p>
          <h2><?= e($post['title'] ?? 'Artigo') ?></h2>
          <?php
            $content = trim($post['content'] ?? '');
            $paragraphs = $content === '' ? [] : preg_split('/\n\s*\n/', $content);
          ?>
          <?php foreach ($paragraphs as $paragraph): ?>
            <p><?= markdown($paragraph) ?></p>
          <?php endforeach; ?>
        </div>
        <div class="ad-slot<?= $site['adSlotsVisible'] ? '' : ' ad-slot--silent' ?>" style="margin-top: 24px;">Espaço reservado para Google Ads (artigo)</div>
      </div>
    </div>
  </section>
</main>
