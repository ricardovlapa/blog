<?php

namespace App\Service;

use RuntimeException;

class SmtpMailer
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $secure;
    private int $timeout;

    public function __construct(array $config)
    {
        $this->host = (string) ($config['host'] ?? '');
        $this->port = (int) ($config['port'] ?? 0);
        $this->username = (string) ($config['username'] ?? '');
        $this->password = (string) ($config['password'] ?? '');
        $this->secure = strtolower((string) ($config['secure'] ?? ''));
        $this->timeout = (int) ($config['timeout'] ?? 20);
    }

    public function send(
        string $from,
        string $fromName,
        string $to,
        string $subject,
        string $body,
        string $replyTo = ''
    ): void {
        $socket = $this->connect();

        $this->expect($socket, [220]);
        $this->sendCommand($socket, 'EHLO localhost', [250]);

        if ($this->secure === 'tls') {
            $this->sendCommand($socket, 'STARTTLS', [220]);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('Falha ao iniciar TLS.');
            }
            $this->sendCommand($socket, 'EHLO localhost', [250]);
        }

        if ($this->username !== '' && $this->password !== '') {
            $this->sendCommand($socket, 'AUTH LOGIN', [334]);
            $this->sendCommand($socket, base64_encode($this->username), [334]);
            $this->sendCommand($socket, base64_encode($this->password), [235]);
        }

        $this->sendCommand($socket, 'MAIL FROM:<' . $from . '>', [250]);
        $this->sendCommand($socket, 'RCPT TO:<' . $to . '>', [250, 251]);
        $this->sendCommand($socket, 'DATA', [354]);

        $headers = $this->buildHeaders($from, $fromName, $to, $subject, $replyTo);
        $payload = $headers . "\r\n" . $this->normalizeBody($body) . "\r\n.";
        $this->sendRaw($socket, $payload);
        $this->expect($socket, [250]);

        $this->sendCommand($socket, 'QUIT', [221]);
        fclose($socket);
    }

    private function connect()
    {
        $host = $this->host;
        if ($host === '' || $this->port === 0) {
            throw new RuntimeException('Configuração SMTP incompleta.');
        }

        if ($this->secure === 'ssl') {
            $host = 'ssl://' . $host;
        }

        $socket = stream_socket_client(
            $host . ':' . $this->port,
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT
        );

        if (!is_resource($socket)) {
            throw new RuntimeException('Falha ao ligar ao servidor SMTP: ' . $errstr);
        }

        stream_set_timeout($socket, $this->timeout);

        return $socket;
    }

    private function buildHeaders(
        string $from,
        string $fromName,
        string $to,
        string $subject,
        string $replyTo
    ): string {
        $fromHeader = $fromName !== '' ? $this->formatAddress($fromName, $from) : $from;
        $headers = [
            'From: ' . $fromHeader,
            'To: ' . $to,
            'Subject: ' . $this->encodeHeader($subject),
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
        ];

        if ($replyTo !== '') {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        return implode("\r\n", $headers);
    }

    private function formatAddress(string $name, string $email): string
    {
        return $this->encodeHeader($name) . ' <' . $email . '>';
    }

    private function encodeHeader(string $value): string
    {
        if (function_exists('mb_encode_mimeheader')) {
            return mb_encode_mimeheader($value, 'UTF-8', 'B');
        }

        return $value;
    }

    private function normalizeBody(string $body): string
    {
        $normalized = preg_replace("/\r\n|\r|\n/", "\r\n", $body);
        if ($normalized === null) {
            $normalized = $body;
        }

        return str_replace("\n.", "\n..", $normalized);
    }

    private function sendCommand($socket, string $command, array $expectedCodes): void
    {
        $this->sendRaw($socket, $command . "\r\n");
        $this->expect($socket, $expectedCodes);
    }

    private function sendRaw($socket, string $data): void
    {
        $length = strlen($data);
        $written = 0;

        while ($written < $length) {
            $result = fwrite($socket, substr($data, $written));
            if ($result === false) {
                throw new RuntimeException('Falha ao comunicar com o servidor SMTP.');
            }
            $written += $result;
        }
    }

    private function expect($socket, array $expectedCodes): void
    {
        $response = $this->readResponse($socket);
        $code = (int) substr($response, 0, 3);

        if (!in_array($code, $expectedCodes, true)) {
            throw new RuntimeException('Resposta inesperada do SMTP: ' . $response);
        }
    }

    private function readResponse($socket): string
    {
        $data = '';

        while (($line = fgets($socket, 512)) !== false) {
            $data .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') {
                break;
            }
        }

        return trim($data);
    }
}
