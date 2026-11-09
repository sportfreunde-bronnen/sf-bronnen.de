<?php

namespace Uniform\Actions;

use PHPMailer\PHPMailer\PHPMailer;
use Uniform\Exceptions\PerformerException;

/**
 * Sends a partnership request (see site/controllers/partnership.php) to the Förderverein.
 *
 * The recipient is not part of the form but configured in the config.php (option sfb.partnership.recipient).
 *
 * This file is loaded on demand by the plugin sfb-partnership, because it extends the PhpMailerAction
 * of the plugin uniform-actions, which is loaded after the plugin sfb-partnership.
 */
class PartnershipMailAction extends PhpMailerAction
{
    public function perform()
    {
        try {
            $recipient = \PartnershipRequest::recipient();

            if ($recipient === null) {
                error_log('Partnership request: sfb.partnership.recipient is not configured.');
                $this->fail('Die Partnerschaftsanfrage ist derzeit nicht verfügbar. Bitte versuchen Sie es später erneut.');
            }

            // raw (not HTML-escaped) data
            $request = \PartnershipRequest::fromFormData($this->form->data('', '', false));

            $mail = $this->createMailer('sf-bronnen.de - Partnerschaft');
            $mail->addAddress($recipient);
            $mail->addReplyTo($request['email'], $request['ansprechpartner']);
            $mail->isHTML(true);
            $mail->Subject = $this->subject($request);
            $mail->Body = $this->htmlBody($request);
            $mail->AltBody = $this->textBody($request);

            if (!$this->deliver($mail)) {
                throw new \Exception('Fehler beim Versenden der E-Mail: ' . $mail->ErrorInfo);
            }
        } catch (PerformerException $e) {
            throw $e;
        } catch (\Throwable $e) {
            error_log('Partnership request failed: ' . get_class($e) . ': ' . $e->getMessage());
            $this->fail('Leider ist ein technischer Fehler aufgetreten. Bitte versuchen Sie es später erneut.');
        }
    }

    /**
     * Send the e-mail
     */
    protected function deliver(PHPMailer $mail): bool
    {
        return $mail->send();
    }

    protected function subject(array $request): string
    {
        $sender = $request['firma'] !== '' ? $request['firma'] : $request['ansprechpartner'];

        return 'Neue Partnerschaftsanfrage: ' . $sender;
    }

    /**
     * Summary of the request: list of sections, each with its lines (label => value)
     *
     * @return array<string, array<int, array{0: string, 1: string}>>
     */
    protected function summary(array $request): array
    {
        $summary = [
            'Interesse an' => array_map(fn (string $interest): array => ['•', $interest], $request['interessen']),
            'Firma und Ansprechpartner' => [
                ['Firma', $request['firma'] !== '' ? $request['firma'] : '-'],
                ['Ansprechpartner', $request['ansprechpartner']],
                ['Straße, Hausnummer', $request['strasse']],
                ['PLZ, Ort', $request['plz'] . ' ' . $request['ort']],
                ['Telefon/Mobil', $request['telefon'] !== '' ? $request['telefon'] : '-'],
                ['E-Mail', $request['email']],
            ],
        ];

        if ($request['nachricht'] !== '') {
            $summary['Anmerkungen'] = [['', $request['nachricht']]];
        }

        $summary['Einwilligung'] = [
            ['Datenschutz', 'Einwilligung zur Verarbeitung der personenbezogenen Daten erteilt am ' . $request['datum']],
        ];

        return $summary;
    }

    protected function htmlBody(array $request): string
    {
        $escape = fn (string $text): string => htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        $html = '<p>Hallo,</p>'
            . '<p>über das Formular &quot;Partnerschaft&quot; auf sf-bronnen.de ging eine neue Anfrage ein. '
            . 'Bitte nimm Kontakt mit dem Absender auf.</p>';

        foreach ($this->summary($request) as $title => $lines) {
            $html .= '<h3 style="margin:1.2em 0 0.4em;font-size:1.05em;">' . $escape($title) . '</h3>'
                . '<table cellpadding="3" cellspacing="0" style="border-collapse:collapse;">';

            foreach ($lines as [$label, $value]) {
                $html .= '<tr>';

                if ($label !== '') {
                    $html .= '<td style="vertical-align:top;color:#555;padding-right:1em;white-space:nowrap;">' . $escape($label) . '</td>';
                }

                $html .= '<td style="vertical-align:top;"><b>' . nl2br($escape($value)) . '</b></td></tr>';
            }

            $html .= '</table>';
        }

        $html .= '<hr style="margin-top:1.5em;"/>'
            . '<p>Du kannst direkt auf diese E-Mail antworten. Du antwortest dann direkt dem Absender der Anfrage.</p>';

        return $html;
    }

    protected function textBody(array $request): string
    {
        $text = "Hallo,\n\n"
            . "über das Formular \"Partnerschaft\" auf sf-bronnen.de ging eine neue Anfrage ein. "
            . "Bitte nimm Kontakt mit dem Absender auf.\n";

        foreach ($this->summary($request) as $title => $lines) {
            $text .= "\n" . $title . "\n" . str_repeat('-', mb_strlen($title)) . "\n";

            foreach ($lines as [$label, $value]) {
                // a bullet or no label at all: just the value, otherwise "label: value"
                $prefix = $label === '' ? '' : ($label === '•' ? '• ' : $label . ': ');
                $text .= $prefix . $value . "\n";
            }
        }

        $text .= "\nDu kannst direkt auf diese E-Mail antworten. Du antwortest dann direkt dem Absender der Anfrage.\n";

        return $text;
    }
}
