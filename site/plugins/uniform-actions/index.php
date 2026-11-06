<?php

namespace Uniform\Actions;

use C;
use PHPMailer\PHPMailer\PHPMailer;
use GuzzleHttp\Client;

class PhpMailerAction extends Action
{
    /**
     * Create a mailer which sends via the SMTP account configured in the config.php
     *
     * @param string $fromName Sender name
     * @return PHPMailer
     */
    protected function createMailer($fromName = 'sf-bronnen.de - Kontaktformular')
    {
        $mail = new PHPMailer;
        $mail->isSMTP();
        //$mail->SMTPDebug = true;
        $mail->Host = c::get('mailerHost');
        $mail->SMTPAuth = true;
        $mail->Username = c::get('mailerFrom');
        $mail->Password = c::get('mailerPassword');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = c::get('MailerSSLPort');
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(c::get('mailerFrom'), $fromName);

        return $mail;
    }

    public function perform()
    {
        try {

            // HTML-escaped data for the HTML body, raw data for the addresses
            $data = $this->form->data();
            $rawData = $this->form->data('', '', false);

            $mail = $this->createMailer();
            $mail->addAddress($rawData['empfaenger']);
            $mail->addReplyTo($rawData['email']);

            $mail->isHTML(true);

            $body = <<<EOM
Hallo,

über das sf-bronnen.de Kontaktformular ging folgende Anfrage für Dich ein:
<hr/>
Von: {$data['vorname']} {$data['nachname']}
E-Mail Adresse: {$data['email']}

Anfrage:
{$data['nachricht']}
<hr/>

Du kannst direkt auf diese E-Mail antworten. Du antwortest dann direkt dem Anfragenden.
EOM;

            $mail->Subject = 'Neue Anfragen über das sf-bronnen.de Kontaktformular';

            $mail->Body = nl2br($body);

            if(!$mail->send()) {
                throw new \Exception('Fehler beim Versenden der E-Mail');
            }

        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

/**
 * Sends a request of the SVZ contact form (see site/controllers/svzcontact.php).
 *
 * The recipient is not part of the form but configured in the config.php (option sfb.svz.recipient).
 */
class SvzMailAction extends PhpMailerAction
{
    public function perform()
    {
        try {

            $recipient = \SvzDomain::recipient();

            if ($recipient === null) {
                error_log('SVZ contact form: sfb.svz.recipient is not configured.');
                throw new \Exception('Das Kontaktformular ist derzeit nicht verfügbar. Bitte versuchen Sie es später erneut.');
            }

            // HTML-escaped data for the HTML body, raw data for the addresses and the subject
            $data = $this->form->data();
            $rawData = $this->form->data('', '', false);

            $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $rawData['datum']);
            $weekdays = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
            $dateText = $date->format('d.m.Y') . ' (' . $weekdays[(int) $date->format('N') - 1] . ')';
            $package = \SvzPage::PACKAGES[$rawData['paket']]['title'] ?? $data['paket'];
            $yesNo = ['ja' => 'Ja', 'nein' => 'Nein'];
            $phone = $data['telefon'] !== '' ? $data['telefon'] : '-';
            $member = $yesNo[$rawData['mitglied']] ?? $data['mitglied'];
            $service = $yesNo[$rawData['servicekraft']] ?? $data['servicekraft'];

            $mail = $this->createMailer('sf-bronnen.de - SVZ Anfrage');
            $mail->addAddress($recipient);
            $mail->addReplyTo($rawData['email'], trim($rawData['vorname'] . ' ' . $rawData['nachname']));

            $mail->isHTML(true);

            $body = <<<EOM
Hallo,

über das SVZ Kontaktformular ging folgende Anfrage für Dich ein:
<hr/>
Wunschtermin: <b>{$dateText}</b>
Paket: {$package}
Aktives Mitglied: {$member}
Servicekraft gewünscht: {$service}

Von: {$data['vorname']} {$data['nachname']}
E-Mail Adresse: {$data['email']}
Telefon: {$phone}

Nachricht:
{$data['nachricht']}
<hr/>

Du kannst direkt auf diese E-Mail antworten. Du antwortest dann direkt dem Anfragenden.
EOM;

            $mail->Subject = 'Neue SVZ-Anfrage für den ' . $date->format('d.m.Y');

            $mail->Body = nl2br($body);

            if (!$mail->send()) {
                throw new \Exception('Fehler beim Versenden der E-Mail');
            }

        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class CertificateAction extends Action
{
    public function perform()
    {
        try {

            $client = new Client(['base_uri' => c::get('backendApiUri')]);

            // raw (not HTML-escaped) data, because it is sent as JSON
            $data = $this->form->data('', '', false);

            $response = $client->request('POST', '/api/certificate', ['json' => [
                'address' => $data['address'],
                'email' => $data['email']
            ]]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception("Leider ist ein technischer Fehler aufgetreten. Bitte versuche es erneut!");
            }

        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}
