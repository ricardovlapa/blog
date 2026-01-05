<main class="container">
  <section class="hero">
    <div>
      <span class="pill">Notas e reflexões mensais</span>
      <h1 class="hero-title"><?= e($site['tagline']) ?></h1>
      <p>Ensaios curtos sobre o Barreiro, a sociedade e os pequenos detalhes do quotidiano que ajudam a compreender o presente.</p>
      <a class="button" href="/blog">Ler o blog</a>
    </div>
  </section>

  <section class="section">
    <h2>Últimos artigos</h2>
    <div class="grid">
      <?php if (count($featured) === 0): ?>
        <div class="card"><p>Ainda não há artigos. Adicione um a posts.json.</p></div>
      <?php else: ?>
        <?php foreach ($featured as $post): ?>
          <article class="card post-card">
            <?php if (!empty($post['image'])): ?>
              <div class="post-image post-image--thumb">
                <img src="<?= image_src($post['image']) ?>" alt="<?= e($post['title'] ?? 'Imagem do artigo') ?>">
              </div>
            <?php else: ?>
              <div class="post-image post-image--thumb post-image--empty">Sem imagem por agora</div>
            <?php endif; ?>
            <div class="post-card__body">
              <p class="post-meta"><time datetime="<?= e($post['date'] ?? '') ?>"><?= e(format_date($post['date'] ?? '')) ?></time></p>
              <h3><?= e($post['title'] ?? 'Untitled') ?></h3>
              <p><?= markdown($post['excerpt'] ?? '') ?></p>
              <a class="button" href="/post/<?= e($post['slug'] ?? '') ?>">Ler</a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
  <div class="ad-slot<?= $site['adSlotsVisible'] ? '' : ' ad-slot--silent' ?>">Espaço reservado para Google Ads (hero)</div>
</main>
