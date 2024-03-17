<?php
namespace App\Services\Contracts;
use App\Services\Contracts\ScoringActionInterface;

interface ScoringActionFactory {
    public function make(string $type): ScoringActionInterface;
}
