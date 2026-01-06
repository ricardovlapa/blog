<main class="container">
  <section class="section">
    <article class="card contact-card">
      <header>
        <h1>Contacto</h1>
      </header>
      <p>Utilize o formulário abaixo para enviar a sua mensagem.</p>

      <?php if (!empty($sent)): ?>
        <div class="form-status form-status--success">
          Mensagem enviada com sucesso. Obrigado pelo contacto.
        </div>
      <?php endif; ?>

      <?php if (!empty($errors['global'])): ?>
        <div class="form-status form-status--error">
          <?= e($errors['global']) ?>
        </div>
      <?php endif; ?>

      <form class="contact-form" method="post" action="/contacto">
        <div class="form-field">
          <label for="contact-name">Nome</label>
          <input id="contact-name" name="name" type="text" value="<?= e($values['name'] ?? '') ?>" required>
          <?php if (!empty($errors['name'])): ?>
            <span class="form-error"><?= e($errors['name']) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-field">
          <label for="contact-email">Email</label>
          <input id="contact-email" name="email" type="email" value="<?= e($values['email'] ?? '') ?>" required>
          <?php if (!empty($errors['email'])): ?>
            <span class="form-error"><?= e($errors['email']) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-field">
          <label for="contact-subject">Assunto</label>
          <input id="contact-subject" name="subject" type="text" value="<?= e($values['subject'] ?? '') ?>" required>
          <?php if (!empty($errors['subject'])): ?>
            <span class="form-error"><?= e($errors['subject']) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-field">
          <label for="contact-message">Mensagem</label>
          <textarea id="contact-message" name="message" rows="6" required><?= e($values['message'] ?? '') ?></textarea>
          <?php if (!empty($errors['message'])): ?>
            <span class="form-error"><?= e($errors['message']) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-honeypot" aria-hidden="true">
          <label for="contact-website">Website</label>
          <input id="contact-website" name="website" type="text" tabindex="-1" autocomplete="off">
        </div>

        <input type="hidden" name="form_started_at" value="<?= e((string) ($formStartedAt ?? time())) ?>">

        <button class="button" type="submit">Enviar mensagem</button>
      </form>
    </article>
  </section>
</main>
