<?php
namespace App\Services\Contracts;
interface NotifierInterface
{
    public function send(string $recipient, string $message );
}
