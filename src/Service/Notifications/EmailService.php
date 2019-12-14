<?php
namespace App\Service\Notifications;

use App\Traits\MailerAwareTrait;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

class EmailService implements EmailServiceInterface
{
    use MailerAwareTrait;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $from = 'destek@enuygun.com';

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return EmailServiceInterface
     */
    public function setTo(string $to): EmailServiceInterface
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return self
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return EmailServiceInterface
     */
    public function setTitle(string $title): EmailServiceInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return EmailServiceInterface
     */
    public function setMessage(string $message): EmailServiceInterface
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(): void
    {
        $email = (new Email())
            ->from($this->getFrom())
            ->to($this->getTo())
            ->subject($this->getTitle())
            ->text($this->getMessage());
        $this->mailer->send($email);
    }
}
