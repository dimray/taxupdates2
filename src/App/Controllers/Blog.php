<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;

class Blog extends Controller
{

    public function fraudHeaders()
    {

        $heading = "Fraud Prevention Headers And The Collapse Of The UK Public Sector";
        return $this->view("Blog/fraud-headers.php", compact("heading"));
    }
}
