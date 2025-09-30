<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;

class YearEnd extends Controller
{

    public function index()
    {
        $hide_tax_year = true;

        $heading = "Year-End Tasks";

        return $this->view("YearEnd/index.php", compact("hide_tax_year", "heading"));
    }
}
