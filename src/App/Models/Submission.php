<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;
use PDO;

class Submission extends Model
{

    protected $table = "submissions";

    // used in Submissions
    public function findSubmissionsByUser($nino_hash, $tax_year, $user_id, $submission_type = null, $business_id = null): array
    {
        $pdo = $this->database->getConnection();

        $sql = "select * from {$this->getTable()} where nino_hash = :nino_hash and tax_year = :tax_year and submitted_by_user_id = :user_id";

        $params = [
            ':nino_hash' => $nino_hash,
            ':tax_year' => $tax_year,
            ':user_id' => $user_id
        ];

        if ($submission_type !== null) {
            $sql .= " AND submission_type = :submission_type";
            $params[':submission_type'] = $submission_type;
        }

        if ($business_id !== null) {
            $sql .= " AND business_id = :business_id";
            $params[':business_id'] = $business_id;
        }

        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // used in submissions
    public function findSubmissionsByAgentForUser($firm_id, $nino_hash, $tax_year, $submission_type = null, $business_id = null)
    {

        $pdo = $this->database->getConnection();

        $sql = "select * from {$this->getTable()} where nino_hash = :nino_hash and tax_year = :tax_year and submitted_by_firm_id = :firm_id";

        $params = [
            ':nino_hash' => $nino_hash,
            ':tax_year' => $tax_year,
            ':firm_id' => $firm_id
        ];

        if ($submission_type !== null) {
            $sql .= " AND submission_type = :submission_type";
            $params[':submission_type'] = $submission_type;
        }

        if ($business_id !== null) {
            $sql .= " AND business_id = :business_id";
            $params[':business_id'] = $business_id;
        }

        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findSubmissionById($submission_reference): array
    {
        $pdo = $this->database->getConnection();

        $sql = "select * from submissions where submission_reference = :submission_reference limit 1";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":submission_reference", $submission_reference, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch();

        return $result ?: [];
    }

    // used by Profile when deleting user
    public function deleteUserSubmissions(int $user_id)
    {
        $pdo = $this->database->getConnection();

        $sql = "delete from submissions where submitted_by_user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // used in delete annual summary (self-employment and property business) (to update the submission to show deleted)
    public function findSubmission(string $nino_hash, string $business_id, string $tax_year,  string $submission_type): ?int
    {

        $pdo = $this->database->getConnection();

        $sql = "SELECT id
        FROM submissions
        WHERE nino_hash = :nino_hash
        AND business_id = :business_id
        AND tax_year = :tax_year        
        AND submission_type = :submission_type
        AND deleted_at IS NULL
        ORDER BY submitted_at DESC
        LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":nino_hash", $nino_hash, PDO::PARAM_STR);
        $stmt->bindValue(":business_id", $business_id, PDO::PARAM_STR);
        $stmt->bindValue(":tax_year", $tax_year, PDO::PARAM_STR);
        $stmt->bindValue(":submission_type", $submission_type, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch();

        return $result ? (int) $result['id'] : null;
    }
}
