<?php


namespace App\Service\Notifications;


interface EmailServiceInterface
{
    public function getTo(): string;

    public function setTo(string $to): self;

    public function getFrom(): string;

    public function setFrom(string $from): self;

    public function getTitle(): string;

    public function setTitle(string $title): self;

    public function getMessage(): string;

    public function setMessage(string $message): self;

    public function sendEmail(): void;
}
