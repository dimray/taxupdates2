<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use App\Helpers\ExportHelper;
use App\Models\Submission;
use Framework\Controller;
use Framework\Response;

class Submissions extends Controller
{
    public function __construct(private Submission $submission) {}

    public function getSubmissions()
    {
        $nino = Helper::getNino();

        $nino_hash = Helper::getHash($nino);

        $tax_year = $_SESSION['tax_year'] ?? TaxYearHelper::getCurrentTaxYear();

        $submission_type = $this->request->get['submission_type'] ?? null;

        $business_id = $this->request->get['business_id'] ?? null;

        $name = "";

        if ($_SESSION['user_role'] === "individual") {
            $submissions = $this->submission->findSubmissionsByUser($nino_hash, $tax_year, $_SESSION['user_id'], $submission_type, $business_id);
        }

        if ($_SESSION['user_role'] === "agent") {
            $submissions = $this->submission->findSubmissionsByAgentForUser((int)$_SESSION['firm_id'], $nino_hash, $tax_year, $submission_type, $business_id);
        }

        $heading = "Submissions For " . $tax_year;

        $submission_types = $this->submission_types;

        return $this->view("/Submissions/list-submissions.php", compact("heading", "submissions", "submission_types"));
    }

    public function viewSubmission()
    {
        $submission_reference = $this->request->get['submission_reference'] ?? "";

        if (empty($submission_reference)) {
            return $this->redirect("/submissions/get-submissions");
        }

        $submission = $this->submission->findSubmissionById($submission_reference);

        $submission_details = [];

        if (!empty($submission)) {
            $submission_details['submissionType'] = $this->submission_types[$submission['submission_type']] ?? "";
            $submission_details['taxYear'] = $submission['tax_year'] ?? "";
            $submission_details['periodStart'] = $submission['period_start'] ?? "";
            $submission_details['periodEnd'] = $submission['period_end'] ?? "";
            $submission_details['submissionTime'] = Helper::formatDateTime($submission['submitted_at'] ?? "");
            $submission_details['submissionReference'] = $submission['submission_reference'] ?? "";
        }

        $submission_payload = json_decode($submission['submission_payload'], true) ?? [];

        $heading = $this->submission_types[$submission['submission_type']] ?? "Submission";

        $hide_tax_year = true;

        return $this->view("/Submissions/view-submission.php", compact("heading", "hide_tax_year", "submission_details", "submission_payload"));
    }

    public function downloadSubmission()
    {
        $submission_reference = $this->request->get['submission_reference'] ?? "";

        if (empty($submission_reference)) {
            return $this->redirect("/submissions/get-submissions");
        }

        $submission = $this->submission->findSubmissionById($submission_reference);

        $data = json_decode($submission['submission_payload'], true);

        $type = $submission['submission_type'] ?? "";
        $tax_year = $_SESSION['tax_year'];

        $heading = $this->submission_types[$submission['submission_type']] . "-" . $tax_year .  ".csv" ?? "Submission-" . $tax_year . ".csv";

        $csv_string = ExportHelper::generateCsvString($data, ['Category', 'Amount']);

        $response = new Response();
        $response->download($csv_string, $heading, 'text/csv');
        return $response;
    }

    private array $submission_types = [
        "annual" => "Annual Summary",
        "cumulative" => "Cumulative Summary",
        "bsas" => "Accounting Adjustments",
        "final_declaration" => "Final Declaration",
    ];
}
