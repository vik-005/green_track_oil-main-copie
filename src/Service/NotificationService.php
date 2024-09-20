<?php

namespace App\Service;

use Twig\Environment;
use Symfony\Component\Mime\Email;
use App\Entity\DemandesProspection;
use Symfony\Component\Mailer\MailerInterface;

class NotificationService
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendApprovalRequest(DemandesProspection $prospection): void
    {
        $agent = $prospection->getAgent();
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($agent->getEmail())
            ->subject('Demande de prospection en attente d\'approbation')
            ->html($this->twig->render('emails/approval_request.html.twig', [
                'prospection' => $prospection,
            ]));

        $this->mailer->send($email);
    }

    public function sendProspectionUpdateNotification(DemandesProspection $prospection): void
    {
        $agent = $prospection->getAgent();
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($agent->getEmail())
            ->subject('Demande de prospection mise à jour')
            ->html($this->twig->render('emails/prospection_update.html.twig', [
                'prospection' => $prospection,
            ]));

        $this->mailer->send($email);
    }

    public function sendProspectionDeletionNotification(DemandesProspection $prospection): void
    {
        $agent = $prospection->getAgent();
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($agent->getEmail())
            ->subject('Demande de prospection supprimée')
            ->html($this->twig->render('emails/prospection_deletion.html.twig', [
                'prospection' => $prospection,
            ]));

        $this->mailer->send($email);
    }
}
