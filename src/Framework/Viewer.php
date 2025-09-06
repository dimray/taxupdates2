<?php

declare(strict_types=1);

namespace Framework;

class Viewer
{
    public function render(string $template, array $variables = [], ?string $layout = 'default'): string
    {
        extract($variables, EXTR_SKIP);

        // Capture the view output
        ob_start();
        require ROOT_PATH . "views/$template";
        $content = ob_get_clean();

        // If no layout specified, return raw content
        if ($layout === null) {
            return $content;
        }

        // Render content inside specified layout
        ob_start();
        require ROOT_PATH . "views/layouts/{$layout}.php";
        return ob_get_clean();
    }

    public function renderEmail(string $template, array $variables = []): string
    {
        $variables['base_url'] = $this->getBaseUrl();
        return $this->render($template, $variables, 'email');
    }

    public function renderTextEmail(string $template, array $variables = []): string
    {
        $variables['base_url'] = $this->getBaseUrl();
        return $this->render($template, $variables, null);
    }

    private function getBaseUrl(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? $_ENV['APP_URL'];
        return rtrim("{$scheme}://{$host}", '/');
    }
}
