<?php

namespace App\Controller;

use App\Service\SmtpMailer;
use RuntimeException;

class ContactController extends BaseController
{
    public function show(array $data = []): void
    {
        $values = $data['values'] ?? [
            'name' => '',
            'email' => '',
            'subject' => '',
            'message' => '',
        ];
        $errors = $data['errors'] ?? [];
        $sent = $data['sent'] ?? (($_GET['sent'] ?? '') === '1');
        $formStartedAt = $data['formStartedAt'] ?? time();

        $this->render('contact', [
            'values' => $values,
            'errors' => $errors,
            'sent' => $sent,
            'formStartedAt' => $formStartedAt,
        ], 'Contacto', [
            'description' => 'Página de contacto.',
        ]);
    }

    public function submit(): void
    {
        $values = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'subject' => trim((string) ($_POST['subject'] ?? '')),
            'message' => trim((string) ($_POST['message'] ?? '')),
        ];
        $errors = $this->validate($values, $_POST);

        if ($errors !== []) {
            $this->show([
                'values' => $values,
                'errors' => $errors,
                'sent' => false,
                'formStartedAt' => time(),
            ]);
            return;
        }

        $config = $this->getMailConfig();
        if ($config['to'] === '' || $config['from'] === '' || $config['host'] === '' || $config['port'] === 0) {
            $this->show([
                'values' => $values,
                'errors' => ['global' => 'Configuração de email em falta.'],
                'sent' => false,
                'formStartedAt' => time(),
            ]);
            return;
        }

        $subject = $this->sanitizeHeaderValue($values['subject']);
        $body = $this->buildBody($values);

        try {
            $mailer = new SmtpMailer($config);
            $mailer->send(
                $config['from'],
                $config['fromName'],
                $config['to'],
                $subject,
                $body,
                $this->sanitizeHeaderValue($values['email'])
            );
        } catch (RuntimeException $error) {
            $this->show([
                'values' => $values,
                'errors' => ['global' => 'Não foi possível enviar a mensagem. Tente novamente mais tarde.'],
                'sent' => false,
                'formStartedAt' => time(),
            ]);
            return;
        }

        header('Location: /contacto?sent=1');
        exit;
    }

    private function validate(array $values, array $payload): array
    {
        $errors = [];

        if ($values['name'] === '') {
            $errors['name'] = 'Indique o seu nome.';
        }

        if ($values['email'] === '') {
            $errors['email'] = 'Indique o seu email.';
        } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        }

        if ($values['subject'] === '') {
            $errors['subject'] = 'Indique o assunto.';
        }

        if ($values['message'] === '') {
            $errors['message'] = 'Escreva a sua mensagem.';
        }

        $honeypot = trim((string) ($payload['website'] ?? ''));
        if ($honeypot !== '') {
            $errors['global'] = 'Submissão inválida.';
        }

        $startedAt = (int) ($payload['form_started_at'] ?? 0);
        $elapsed = time() - $startedAt;
        if ($startedAt === 0 || $elapsed < 3 || $elapsed > 3600) {
            $errors['global'] = 'Submissão inválida.';
        }

        return $errors;
    }

    private function buildBody(array $values): string
    {
        return "Nome: {$values['name']}\n" .
            "Email: {$values['email']}\n\n" .
            "Mensagem:\n{$values['message']}\n";
    }

    private function sanitizeHeaderValue(string $value): string
    {
        return str_replace(["\r", "\n"], ' ', $value);
    }

    private function getMailConfig(): array
    {
        $secure = strtolower((string) getenv('SMTP_SECURE'));
        if ($secure === '') {
            $secure = 'tls';
        }

        return [
            'host' => (string) getenv('SMTP_HOST'),
            'port' => (int) getenv('SMTP_PORT'),
            'username' => (string) getenv('SMTP_USER'),
            'password' => (string) getenv('SMTP_PASS'),
            'secure' => $secure,
            'timeout' => (int) (getenv('SMTP_TIMEOUT') ?: 20),
            'from' => (string) getenv('MAIL_FROM'),
            'fromName' => (string) (getenv('MAIL_FROM_NAME') ?: ($this->site['title'] ?? '')),
            'to' => (string) getenv('MAIL_TO'),
        ];
    }
}
