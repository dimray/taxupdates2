<?php

declare(strict_types=1);

namespace Framework;

class Controller
{
    protected Request $request;

    protected Response $response;

    protected Viewer $viewer;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function setViewer(Viewer $viewer)
    {
        $this->viewer = $viewer;
    }

    protected function view(string $template, array $data = []): Response
    {
        $data = array_merge($this->request->attributes, $data);

        $this->response->setBody($this->viewer->render($template, $data));

        return $this->response;
    }

    protected function redirect(string $url): Response
    {
        $this->response->redirect($url);

        return $this->response;
    }

    protected array $errors = [];

    protected function addError($message)
    {
        $this->errors[] = $message;
        $_SESSION['errors'] = $_SESSION['errors'] ?? []; // Initialize if not set
        $_SESSION['errors'][] = $message;
    }

    // set errors for the view
    protected function flashErrors(): array
    {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        return $errors;
    }
}
