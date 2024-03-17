<?php
namespace App\Services\Implementations;

use App\Services\Contracts\ProfileCompletionInterface;


class CompleteProfile implements ProfileCompletionInterface {
    public function score(): int {
        return 20; // Points for completing a profile
    }
}
