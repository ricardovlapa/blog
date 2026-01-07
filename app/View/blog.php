<?php
/** @var array $posts */
/** @var array $tags */
/** @var string $activeTag */
/** @var string $activeTagLabel */
?>
<main class="container">
  <section class="section">
    <h1>Todos os artigos</h1>
    <div class="layout">
      <div class="post-list">
        <?php if (count($posts) === 0): ?>
          <div class="card">
            <?php if ($activeTag !== ''): ?>
              <p>Sem artigos para a tag “<?= e($activeTagLabel) ?>”.</p>
            <?php else: ?>
              <p>Ainda não há artigos. Adicione um a posts.json.</p>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <?php foreach ($posts as $post): ?>
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
                <?php if (!empty($post['tag_links'])): ?>
                  <p>
                    <?php foreach ($post['tag_links'] as $tag): ?>
                      <a class="tag" href="/blog/tag/<?= e($tag['slug'] ?? '') ?>">#<?= e($tag['label'] ?? '') ?></a>
                    <?php endforeach; ?>
                  </p>
                <?php endif; ?>
                <a class="button" href="/post/<?= e($post['slug'] ?? '') ?>">Ler</a>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <aside class="sidebar">
        <div class="card tag-filter">
          <span class="tag-filter__label">Tags</span>
          <div class="tag-filter__list">
            <a class="tag-filter__item<?= $activeTag === '' ? ' is-active' : '' ?>" href="/blog">Todos</a>
            <?php foreach ($tags as $tag): ?>
              <?php $isActive = ($tag['slug'] ?? '') === $activeTag; ?>
              <a class="tag-filter__item<?= $isActive ? ' is-active' : '' ?>" href="/blog/tag/<?= e($tag['slug'] ?? '') ?>">#<?= e($tag['label'] ?? '') ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="card">
          <h3>Acompanha as novidades</h3>
          <p>Atualizações mensais, sem ruído. Subscreve no teu leitor de feeds favorito.</p>
          <a class="rss-subscribe" href="/feed.xml" aria-label="Subscrever RSS">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M6 18a2 2 0 1 1-2-2 2 2 0 0 1 2 2z" />
              <path d="M4 10a10 10 0 0 1 10 10h-3a7 7 0 0 0-7-7z" />
              <path d="M4 4a16 16 0 0 1 16 16h-3A13 13 0 0 0 4 7z" />
            </svg>
            <span>Subscrever</span>
          </a>
        </div>
        <div class="ad-slot<?= $site['adSlotsVisible'] ? '' : ' ad-slot--silent' ?>" style="margin-top: 20px;">Espaço reservado para Google Ads (sidebar)</div>
      </aside>
    </div>
  </section>
</main>
