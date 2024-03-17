<?php
namespace App\Services\Implementations;

use App\Services\Contracts\UploadVideoInterface;


class UploadVideo implements UploadVideoInterface {
    public function score(): int {
        return 10; // Points for watching a video
    }
}
