<?php
/** @var array $posts */
/** @var array $tags */
/** @var string $activeTag */
/** @var string $activeTagLabel */
?>
<main class="container">
  <section class="section">
    <h2>Todos os artigos</h2>
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
          <a class="button" href="/feed.xml">Abrir feed</a>
        </div>
        <div class="ad-slot<?= $site['adSlotsVisible'] ? '' : ' ad-slot--silent' ?>" style="margin-top: 20px;">Espaço reservado para Google Ads (sidebar)</div>
      </aside>
    </div>
  </section>
</main>
