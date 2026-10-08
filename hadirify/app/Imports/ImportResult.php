<?php

namespace App\Imports;

trait ImportResult
{
    protected array $errors = [];
    protected int $successCount = 0;

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    protected function duplicateKeyExists(string $table, string $column, string $value): bool
    {
        return \Illuminate\Support\Facades\DB::table($table)
            ->whereRaw("LOWER(TRIM({$column})) = ?", [mb_strtolower(trim($value))])
            ->exists();
    }

    protected function isDuplicateDatabaseException(\Illuminate\Database\QueryException $exception): bool
    {
        $sqlState = (string) $exception->getCode();
        $driverCode = $exception->errorInfo[1] ?? null;
        $message = strtolower($exception->getMessage());

        return ($sqlState === '23000' && $driverCode === 1062)
            || $sqlState === '23505'
            || ($sqlState === '23000' && str_contains($message, 'unique constraint failed'));
    }
}
