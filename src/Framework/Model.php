<?php

declare(strict_types=1);

namespace Framework;

use PDO;
use App\Database;

abstract class Model
{
    public function __construct(protected Database $database) {}

    protected $table;

    protected function getTable(): string
    {
        if ($this->table !== null) {

            return $this->table;
        }

        $parts = explode("\\", $this::class);

        return strtolower(array_pop($parts));
    }

    public function getLastId(): string
    {
        $conn = $this->database->getConnection();

        return $conn->lastInsertId();
    }


    public function find(int $id): array|bool
    {
        $pdo = $this->database->getConnection();

        if ($this->getTable() === "individuals" || $this->getTable() === "agents") {

            $sql = "select * from {$this->getTable()} where user_id = :id";
        } else {

            $sql = "select * from {$this->getTable()} where id = :id";
        }

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function findAll(): array
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT *
                FROM {$this->getTable()}";

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }

    public function insert(array $data): bool
    {
        $pdo = $this->database->getConnection();

        $columns = implode(", ", array_keys($data));

        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $sql = "insert into {$this->getTable()} ($columns) values ($placeholders)";


        $stmt = $pdo->prepare($sql);

        $i = 1;

        foreach ($data as $value) {
            $type = match (getType($value)) {
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                "boolean" => PDO::PARAM_BOOL,
                default => PDO::PARAM_STR
            };


            $stmt->bindValue($i++, $value, $type);
        }

        return $stmt->execute();
    }

    public function update(array $data): bool
    {
        $id = $data['id'];

        unset($data['id']);

        if (empty($data)) {
            return false;
        }

        $columns = array_keys($data);

        $placeholders = implode(" = ?, ", $columns) . " = ?";

        $pdo = $this->database->getConnection();

        $table = $this->getTable();

        $id_column = in_array($table, ['individuals', 'agents']) ? 'user_id' : 'id';

        $sql = "UPDATE {$table} SET $placeholders WHERE {$id_column} = ?";

        $stmt = $pdo->prepare($sql);

        $i = 1;

        foreach ($data as $value) {
            $type = match (getType($value)) {
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                "boolean" => PDO::PARAM_BOOL,
                default => PDO::PARAM_STR
            };

            $stmt->bindValue($i++, $value, $type);
        }

        $stmt->bindValue($i, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }


    public function delete(string|int $id): bool
    {
        $pdo = $this->database->getConnection();

        if ($this->getTable() === "individuals" || $this->getTable() === "agents") {

            $sql = "delete from {$this->getTable()} where user_id = :id";
        } else {

            $sql = "delete from {$this->getTable()} where id = :id";
        }

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    private array $allowedColumns =  [
        'email',
        'nino_hash',
        'access_token',
        'refresh_token',
        'password_reset_hash',
        'activation_hash',
        'is_active',
        'arn_hash',
        'role',
    ];

    public function getFromDatabase($column, $user_id): string|int|false|null
    {
        $allowedColumns = $this->allowedColumns;

        if (!in_array($column, $allowedColumns)) {
            return false;
        }

        $pdo = $this->database->getConnection();

        if ($this->getTable() === "individuals" || $this->getTable() === "agents") {

            $sql = "select {$column} from {$this->getTable()} where user_id = :user_id";
        } else {

            $sql = "select {$column} from {$this->getTable()} where id = :user_id";
        }

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $item = $stmt->fetchColumn();

        return $item;
    }

    public function findUserBy(string $column, $value): array|false
    {
        $allowedColumns = $this->allowedColumns;

        if (!in_array($column, $allowedColumns, true)) {
            return false;
        }

        $pdo = $this->database->getConnection();

        $sql = "select * from {$this->getTable()} where $column = :$column";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":$column", $value, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function beginTransaction(): void
    {
        $this->database->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->database->getConnection()->commit();
    }

    public function rollBack(): void
    {
        $this->database->getConnection()->rollBack();
    }

    public function getLastPdoError(): string
    {
        $pdo = $this->database->getConnection();
        $errorInfo = $pdo->errorInfo();
        return $errorInfo[2] ?? 'Unknown PDO error';
    }
}
