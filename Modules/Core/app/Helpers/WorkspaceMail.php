<?php

namespace Modules\Core\Helpers;

use Illuminate\Config\Repository;
use Illuminate\Mail\MailManager;
use Modules\Core\Models\Setting;
use Symfony\Component\Mailer\Transport\TransportInterface;

class WorkspaceMail
{
    public function __construct(private MailManager $mail, private Repository $config) {}

    public function transport(array $configuration): TransportInterface
    {
        // Resolve only when sending, after the HTTP/job tenant context is selected.
        $values = Setting::values('email');
        $this->config->set('mail.from', $values['enabled']
            ? ['address' => $values['from_address'], 'name' => $values['from_name']]
            : $configuration['fallback_from']);

        if (! $values['enabled']) {
            return $this->mail->mailer($configuration['fallback'])->getSymfonyTransport();
        }

        return $this->mail->createSymfonyTransport([
            'transport' => 'smtp',
            'scheme' => $values['encryption'] === 'ssl' ? 'smtps' : 'smtp',
            'host' => $values['host'],
            'port' => (int) $values['port'],
            'username' => $values['username'],
            'password' => $values['password'],
            'auto_tls' => $values['encryption'] !== 'none',
            'require_tls' => $values['encryption'] === 'tls',
            'timeout' => 15,
        ]);
    }

    public function reset(): void
    {
        $this->config->set('mail.from', $this->config->get('mail.mailers.workspace.fallback_from'));
        $this->mail->forgetMailers();
        app()->forgetInstance('mailer');
    }
}
