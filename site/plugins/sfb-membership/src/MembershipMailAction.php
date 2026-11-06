<?php

namespace Uniform\Actions;

use Kirby\Toolkit\Str;
use PHPMailer\PHPMailer\PHPMailer;
use Uniform\Exceptions\PerformerException;

/**
 * Sends a membership application (see site/controllers/membership.php) to the membership administration:
 * the filled in PDF form is attached to the e-mail, the body contains a summary of the data.
 *
 * The recipient is not part of the form but configured in the config.php (option sfb.membership.recipient).
 *
 * This file is loaded on demand by the plugin sfb-membership, because it extends the PhpMailerAction
 * of the plugin uniform-actions, which is loaded after the plugin sfb-membership.
 */
class MembershipMailAction extends PhpMailerAction
{
    public function perform()
    {
        try {
            $recipient = \MembershipApplication::recipient();

            if ($recipient === null) {
                error_log('Membership application: sfb.membership.recipient is not configured.');
                $this->fail('Der Mitgliedsantrag ist derzeit nicht verfügbar. Bitte versuchen Sie es später erneut.');
            }

            // raw (not HTML-escaped) data
            $application = \MembershipApplication::fromFormData($this->form->data('', '', false));
            $pdf = \MembershipPdf::render($application, \MembershipApplication::templatePath());

            $name = trim($application['vorname'] . ' ' . $application['nachname']);

            $mail = $this->createMailer('sf-bronnen.de - Mitgliedsantrag');
            $mail->addAddress($recipient);
            $mail->addReplyTo($application['email'], $name);
            $mail->isHTML(true);
            $mail->Subject = 'Neuer Mitgliedsantrag: ' . $name;
            $mail->Body = $this->htmlBody($application);
            $mail->AltBody = $this->textBody($application);
            $mail->addStringAttachment($pdf, $this->fileName($application), PHPMailer::ENCODING_BASE64, 'application/pdf');

            if (!$this->deliver($mail)) {
                throw new \Exception('Fehler beim Versenden der E-Mail: ' . $mail->ErrorInfo);
            }
        } catch (PerformerException $e) {
            throw $e;
        } catch (\Throwable $e) {
            error_log('Membership application failed: ' . get_class($e) . ': ' . $e->getMessage());
            $this->fail('Leider ist ein technischer Fehler aufgetreten. Bitte versuchen Sie es später erneut.');
            throw $e;
        }
    }

    /**
     * Send the e-mail
     */
    protected function deliver(PHPMailer $mail): bool
    {
        return $mail->send();
    }

    /**
     * File name of the PDF, e.g. Beitrittserklaerung_Mustermann_Max.pdf
     */
    protected function fileName(array $application): string
    {
        $parts = array_filter([
            ucfirst(Str::slug($application['nachname'])),
            ucfirst(Str::slug($application['vorname'])),
        ]);

        return 'Beitrittserklaerung_' . ($parts !== [] ? implode('_', $parts) : 'Antrag') . '.pdf';
    }

    /**
     * Summary of the application: list of sections, each with its lines (label => value)
     *
     * @return array<string, array<int, array{0: string, 1: string}>>
     */
    protected function summary(array $application): array
    {
        $groups = array_map([\MembershipApplication::class, 'groupTitle'], $application['gruppen']);

        $family = [];
        foreach ($application['familie'] as $index => $member) {
            $family[] = [
                (string) ($index + 1),
                $member['vorname'] . ' ' . $member['nachname'] . ', geboren am ' . \MembershipApplication::formatDate($member['geburtsdatum'])
                    . ' - ' . \MembershipApplication::groupTitle($member['gruppe']),
            ];
        }

        $summary = [
            'Mitglied' => [
                ['Name', $application['vorname'] . ' ' . $application['nachname']],
                ['Anschrift', $application['strasse'] . ', ' . $application['plz'] . ' ' . $application['ort']],
                ['Geburtsdatum', \MembershipApplication::formatDate($application['geburtsdatum'])],
                ['Gewünschtes Eintrittsdatum', \MembershipApplication::formatDate($application['eintrittsdatum'])],
                ['E-Mail', $application['email']],
                ['Telefon', $application['telefon']],
            ],
            'Mitgliedschaft' => [
                ['Gruppe / Abteilung', implode('; ', $groups)],
                ['Beitragsklasse', \MembershipApplication::FEE_CLASSES[$application['beitragsklasse']] ?? $application['beitragsklasse']],
            ],
        ];

        if ($family !== []) {
            $summary['Weitere Familienmitglieder'] = $family;
        }

        $summary['Bankverbindung (SEPA-Lastschriftmandat)'] = [
            ['Kontoinhaber', $application['kontoinhaberVorname'] . ' ' . $application['kontoinhaberNachname']],
            ['Kreditinstitut', $application['kreditinstitut']],
            ['IBAN', \MembershipApplication::maskIban($application['iban'])],
            ['BIC', $application['bic']],
        ];

        $summary['Einwilligung'] = [
            ['DSGVO', 'Einwilligung zur Erhebung und Verarbeitung personenbezogener Daten erteilt am ' . $application['datum']],
        ];

        return $summary;
    }

    protected function htmlBody(array $application): string
    {
        $escape = fn(string $text): string => htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        $html = '<p>Hallo,</p>'
            . '<p>über das Formular &quot;Mitglied werden&quot; auf sf-bronnen.de ging ein neuer Mitgliedsantrag ein. '
            . 'Das ausgefüllte Formular (Beitrittserklärung und Vollmacht) findest Du als PDF im Anhang.</p>';

        foreach ($this->summary($application) as $title => $lines) {
            $html .= '<h3 style="margin:1.2em 0 0.4em;font-size:1.05em;">' . $escape($title) . '</h3>'
                . '<table cellpadding="3" cellspacing="0" style="border-collapse:collapse;">';

            foreach ($lines as [$label, $value]) {
                $html .= '<tr><td style="vertical-align:top;color:#555;padding-right:1em;white-space:nowrap;">' . $escape($label) . '</td>'
                    . '<td style="vertical-align:top;"><b>' . $escape($value) . '</b></td></tr>';
            }

            $html .= '</table>';
        }

        $html .= '<hr style="margin-top:1.5em;"/>'
            . '<p>Die Unterschriften im PDF wurden aus den eingegebenen Namen automatisch erzeugt. '
            . 'Die IBAN ist in dieser E-Mail aus Datenschutzgründen nur teilweise lesbar, vollständig steht sie im PDF.</p>'
            . '<p>Du kannst direkt auf diese E-Mail antworten. Du antwortest dann direkt dem Antragsteller.</p>';

        return $html;
    }

    protected function textBody(array $application): string
    {
        $text = "Hallo,\n\n"
            . "über das Formular \"Mitglied werden\" auf sf-bronnen.de ging ein neuer Mitgliedsantrag ein. "
            . "Das ausgefüllte Formular (Beitrittserklärung und Vollmacht) findest Du als PDF im Anhang.\n";

        foreach ($this->summary($application) as $title => $lines) {
            $text .= "\n" . $title . "\n" . str_repeat('-', mb_strlen($title)) . "\n";

            foreach ($lines as [$label, $value]) {
                $text .= $label . ': ' . $value . "\n";
            }
        }

        $text .= "\nDie Unterschriften im PDF wurden aus den eingegebenen Namen automatisch erzeugt. "
            . "Die IBAN ist in dieser E-Mail aus Datenschutzgründen nur teilweise lesbar, vollständig steht sie im PDF.\n\n"
            . "Du kannst direkt auf diese E-Mail antworten. Du antwortest dann direkt dem Antragsteller.\n";

        return $text;
    }
}
