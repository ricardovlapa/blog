<?php require_once dirname(__DIR__) . '/helpers.php'; ?>
<!doctype html>
<html lang="pt-PT">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
  <link rel="manifest" href="/assets/favicon/site.webmanifest">
  <title><?= e($pageTitle) ?></title>
  <?php if (!empty($meta['description'])): ?>
    <meta name="description" content="<?= e($meta['description']) ?>">
  <?php endif; ?>
  <?php if (!empty($meta['robots'])): ?>
    <meta name="robots" content="<?= e($meta['robots']) ?>">
  <?php endif; ?>
  <?php if (!empty($meta['canonical'])): ?>
    <link rel="canonical" href="<?= e($meta['canonical']) ?>">
  <?php endif; ?>
  <?php if (!empty($meta['rss'])): ?>
    <link rel="alternate" type="application/rss+xml" title="<?= e($site['title'] ?? 'Feed') ?>" href="<?= e($meta['rss']) ?>">
  <?php endif; ?>
  <?php if (!empty($meta['og'])): ?>
    <meta property="og:title" content="<?= e($meta['og']['title'] ?? '') ?>">
    <meta property="og:description" content="<?= e($meta['og']['description'] ?? '') ?>">
    <meta property="og:type" content="<?= e($meta['og']['type'] ?? 'website') ?>">
    <meta property="og:url" content="<?= e($meta['og']['url'] ?? '') ?>">
    <meta property="og:site_name" content="<?= e($site['title'] ?? '') ?>">
    <meta property="og:locale" content="pt_PT">
    <?php if (!empty($meta['og']['image'])): ?>
      <meta property="og:image" content="<?= e($meta['og']['image']) ?>">
    <?php endif; ?>
  <?php endif; ?>
  <?php if (!empty($meta['twitter'])): ?>
    <meta name="twitter:card" content="<?= e($meta['twitter']['card'] ?? 'summary') ?>">
    <meta name="twitter:title" content="<?= e($meta['twitter']['title'] ?? '') ?>">
    <meta name="twitter:description" content="<?= e($meta['twitter']['description'] ?? '') ?>">
    <?php if (!empty($meta['twitter']['image'])): ?>
      <meta name="twitter:image" content="<?= e($meta['twitter']['image']) ?>">
    <?php endif; ?>
  <?php endif; ?>
  <?php if (!empty($meta['jsonLd'])): ?>
    <script type="application/ld+json">
      <?= json_encode($meta['jsonLd'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <nav class="nav">
        <div class="brand">
          <a href="/" aria-label="<?= e($site['title']) ?> | Início">
            <img class="image-logo" alt="<?= e($site['title']) ?>" src="/assets/images/rl_logo.png">
          </a>
        </div>
        <div class="nav-links">
          <a href="/">Início</a>
          <a href="/about">Sobre</a>
          <a href="/blog">Blog</a>
        </div>
      </nav>
    </div>
  </header>

  <?= $content ?>

  <footer class="site-footer">
    <div class="container">
      <div class="footer-main">
        <img class="footer-avatar" src="/assets/images/myPhoto.png" alt="Retrato de Ricardo Venceslau Lapa">
        <h2 class="footer-name"><?= e($site['title']) ?></h2>
        <p class="footer-text">Crónicas e textos de opinião sobre o Barreiro, a sociedade e o tempo em que vivemos.</p>
        <div class="footer-social">
          <a href="https://www.facebook.com/cronicasdobarreiro" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v6h3v-6h3l1-3h-4v-2c0-.6.4-1 1-1z" />
            </svg>
          </a>
          <a href="https://www.instagram.com/ricardo.venceslau.lapa" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4z" fill="none" stroke="currentColor" stroke-width="1.6" />
              <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.6" />
              <circle cx="16.5" cy="7.5" r="1.2" fill="currentColor" />
            </svg>
          </a>
          <a href="https://www.linkedin.com/in/ricardo-lapa" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M6 9H3v12h3z" />
              <path d="M4.5 3.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z" />
              <path d="M14.5 9a4.5 4.5 0 0 0-3.5 1.7V9H8v12h3v-6.2a2.2 2.2 0 0 1 2.2-2.2c1.3 0 1.8.8 1.8 2.1V21h3v-7.1C18 10.8 16.6 9 14.5 9z" />
            </svg>
          </a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p>
          Copyright © <?= date('Y') ?> <?= e($site['title']) ?>. Todos os direitos reservados.
          <span class="footer-sep">|</span>
          <a href="/contacto">Contacto</a>
          <span class="footer-sep">|</span>
          <a href="/nota-editorial-e-de-privacidade">Nota editorial e de privacidade</a>
        </p>
      </div>
    </div>
  </footer>
  <script>
    const header = document.querySelector('.site-header');
    if (header) {
      const offset = header.offsetTop;
      const addAt = offset + 24;
      const removeAt = offset + 12;
      let isScrolled = false;
      let ticking = false;
      const updateHeader = () => {
        const y = window.scrollY;
        if (!isScrolled && y > addAt) {
          header.classList.add('site-header--scrolled');
          isScrolled = true;
        } else if (isScrolled && y < removeAt) {
          header.classList.remove('site-header--scrolled');
          isScrolled = false;
        }
        ticking = false;
      };
      const onScroll = () => {
        if (!ticking) {
          ticking = true;
          window.requestAnimationFrame(updateHeader);
        }
      };
      updateHeader();
      window.addEventListener('scroll', onScroll, { passive: true });
    }
  </script>
</body>
</html>
