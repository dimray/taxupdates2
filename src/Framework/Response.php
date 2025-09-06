<?php

declare(strict_types=1);

namespace Framework;

class Response
{
    private string $body = "";

    private array $headers = [];

    private int $status_code = 0;

    public function setStatusCode(int $code): void
    {
        $this->status_code = $code;
    }

    public function redirect(string $url): void
    {
        $this->addHeader("Location: $url");
    }

    public function addHeader(string $header): void
    {
        $this->headers[] = $header;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function send(): void
    {
        if ($this->status_code) {

            http_response_code($this->status_code);
        }

        foreach ($this->headers as $header) {

            header($header);
        }

        echo $this->body;
    }

    public function download(string $content, string $filename, string $content_type = 'application/octet-stream'): void
    {
        $this->setStatusCode(200);
        $this->addHeader("Content-Type: $content_type");
        $this->addHeader("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        $this->addHeader("Content-Length: " . strlen($content));

        $this->setBody($content);
    }
}
