<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailerService
{
    private $replayTo;
        public function __construct(private MailerInterface $mailer, $replayTo)
        {
            $this->replayTo = $replayTo;
        }
    public function sendEmail(
        $to = 'inas.hakkou2001@gmail.com',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = 'Time for Symfony Mailer!'
    ): void
    {
        dd($this->replayTo);
        $email = (new Email())
            ->from('inass.hakkou2001@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replayTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

        $this->mailer->send($email);

        // ...
    }
}