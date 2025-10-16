<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\Flash;
use App\Helpers\Helper;
use App\HmrcApi\Endpoints\Other\ApiSelfAssessmentAssist;
use Framework\Controller;

class SelfAssessmentAssist extends Controller
{
    public function __construct(private ApiSelfAssessmentAssist $apiSelfAssessmentAssist) {}

    public function produceReport()
    {
        $calculation_id = $this->request->get['calculation_id'] ?? $_SESSION['calculation_id'];

        $_SESSION['calculation_id'] = $calculation_id;

        if (empty($calculation_id)) {
            Flash::addMessage("Unable to obtain report", Flash::WARNING);
            return $this->redirect("/individual-calculations/trigger-calculation");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiSelfAssessmentAssist->produceAHmrcSelfAssessmentAssistReport($nino, $tax_year, $calculation_id);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $hmrc_response = $response['response'] ?? [];
        $messages = $hmrc_response['messages'] ?? [];
        $nino = $hmrc_response['nino'] ?? '';
        $tax_year = $hmrc_response['taxYear'] ?? '';
        $calculation_id = $hmrc_response['calculationId'] ?? '';
        $report_id = $hmrc_response['reportId'] ?? '';
        $correlation_id = $hmrc_response['correlationId'] ?? '';

        // used by individual-calculations/retrieve-calculation
        $_SESSION['tax_year'] = $tax_year;

        $heading = "HMRC Assist Messages";

        $query_string = http_build_query(compact("nino", "report_id", "correlation_id", "calculation_id"));

        return $this->view("Endpoints/Other/SelfAssessmentAssist/messages.php", compact("heading", "messages", "nino", "tax_year", "calculation_id", "query_string"));
    }

    public function acknowledgeReport()
    {
        $nino = $this->request->get['nino'] ?? '';
        $report_id = $this->request->get['report_id'] ?? '';
        $correlation_id = $this->request->get['correlation_id'] ?? '';
        $calculation_id = $this->request->get['calculation_id'] ?? $_SESSION['calculation_id'];

        $_SESSION['calculation_id'] = $calculation_id;

        $response = $this->apiSelfAssessmentAssist->acknowledgeAHmrcSelfAssessmentAssistReport($nino, $report_id, $correlation_id);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("Acknowledgement sent to HMRC", Flash::SUCCESS);
        }

        return $this->redirect("/self-assessment-assist/produce-report");
    }
}
