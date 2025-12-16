<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;

class YearEnd extends Controller
{

    public function index()
    {
        $hide_tax_year = true;

        $supporting_agent  = false;
        if (isset($_SESSION['client']['agent_type']) && $_SESSION['client']['agent_type'] === "supporting") {
            $supporting_agent = true;
        }

        $heading = "Year-End";

        return $this->view("YearEnd/index.php", compact("hide_tax_year", "heading", "supporting_agent"));
    }

    public function otherIncome()
    {
        $heading = "Non-MTD Income";

        return $this->view("YearEnd/other-income.php", compact("heading"));
    }

    public function capitalGains()
    {
        $heading = "Capital Gains";

        return $this->view("YearEnd/capital-gains.php", compact("heading"));
    }

    public function taxReliefs()
    {
        $heading = "Tax Reliefs";

        return $this->view("YearEnd/tax-reliefs.php", compact("heading"));
    }

    public function disclosures()
    {
        $heading = "Disclosures";

        return $this->view("YearEnd/disclosures.php", compact("heading"));
    }
}