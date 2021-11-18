<?php

namespace Uniform\Actions;

use C;
use PHPMailer\PHPMailer\PHPMailer;
use GuzzleHttp\Client;

class PhpMailerAction extends Action
{
    public function perform()
    {
        try {

            $data = $this->form->data();

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = true;
            $mail->Host = c::get('mailerHost');
            $mail->SMTPAuth = true;
            $mail->Username = c::get('mailerFrom');
            $mail->Password = c::get('mailerPassword');
            $mail->SMTPSecure = 'tls';
            $mail->Port = c::get('MailerSSLPort');
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(c::get('mailerFrom'), 'sf-bronnen.de - Kontaktformular');
            $mail->addAddress($data['empfaenger']);
            $mail->addReplyTo($data['email']);

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

class CertificateAction extends Action
{
    public function perform()
    {
        try {

            $client = new Client(['base_uri' => c::get('backendApiUri')]);

            $data = $this->form->data();

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
