<?php

declare(strict_types=1);

namespace App\Helpers;

use App\HmrcApi\Endpoints\ApiPropertyBusiness;

class ForeignPropertyHelper
{

    public function __construct(private ApiPropertyBusiness $apiPropertyBusiness) {}

    public function getForeignProperties(string $nino): array
    {
        if (isset($_SESSION[$nino]['cache']['foreign_properties'])) {
            return $_SESSION[$nino]['cache']['foreign_properties'];
        }

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];
        $tax_year = "2026-27";

        $response = $this->apiPropertyBusiness->retrieveForeignPropertyDetails($nino, $business_id, $tax_year);

        $foreign_properties =  $response['foreign_property_details'] ?? [];
        $_SESSION[$nino]['cache']['foreign_properties'] = $foreign_properties;
        return $foreign_properties;
    }
}
