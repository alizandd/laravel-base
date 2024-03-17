<?php
namespace App\Services\Contracts;

interface ScoringActionInterface {
    public function score(): int;
}
