<?php


namespace App\Service\Notifications;


interface EmailServiceInterface
{
    public function setTo(string $to): self;
    public function setTitle(string $title): self;
    public function setMessage(string $message): self;
    public function sendEmail(): void;
}
