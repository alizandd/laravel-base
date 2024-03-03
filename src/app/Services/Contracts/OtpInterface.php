<?php
namespace App\Services\Contracts;
interface OtpInterface
{
    public function generate(string $identifier): object;
    public function validate(string $identifier, string $otp , string $key): bool;
}
