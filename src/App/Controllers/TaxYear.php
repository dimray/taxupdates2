<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;

class TaxYear extends Controller
{

    public function changeTaxYear()
    {
        $tax_year = $this->request->get['tax_year'] ?? null;
        $source = $this->request->get['source'] ?? '/';

        $source_map = [
            'retrieve-calculation' => '/individual-calculations/get-latest-calculation',
        ];

        if ($tax_year) {
            $_SESSION['tax_year'] = $tax_year;
        }

        unset($this->request->get['source'], $this->request->get['tax_year']);

        $query_string = http_build_query($this->request->get);

        // Resolve redirect URL
        if (isset($source_map[$source])) {
            $redirect_url = $source_map[$source];

            // Only append query parameters if the url is in $source_map (to avoid duplicates where the url is already there)
            if (!empty($this->request->get)) {
                $query_string = http_build_query($this->request->get);
                $redirect_url .= '?' . $query_string;
            }
        } elseif (str_starts_with($source, '/')) {
            // Safe user-defined fallback (must start with slash)
            $redirect_url = $source;
        } else {
            // Unsafe or malformed source
            $redirect_url = '/';
        }


        return $this->redirect($redirect_url);
    }
}
