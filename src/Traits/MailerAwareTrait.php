<?php


namespace App\Traits;


use Symfony\Component\Mailer\MailerInterface;

trait MailerAwareTrait
{
    /**
     * @var MailerInterface $mailer
     */
    protected $mailer;

    /**
     * @return MailerInterface
     */
    public function getMailer(): MailerInterface
    {
        return $this->mailer;
    }

    /**
     * @required
     *
     * @param MailerInterface $mailer
     */
    public function setMailer(MailerInterface $mailer): void
    {
        $this->mailer = $mailer;
    }
}
