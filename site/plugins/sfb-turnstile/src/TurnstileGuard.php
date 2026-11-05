<?php

namespace Uniform\Guards;

use GuzzleHttp\Client;
use Kirby\Cms\App;

/**
 * Guard that verifies a Cloudflare Turnstile token server-side (siteverify).
 *
 * Options:
 * - action (required): the `data-action` of the widget that protects the form
 *
 * Plugin options (see sfb-turnstile/index.php):
 * - sfb.turnstile.secret: the widget secret key (never commit it)
 * - sfb.turnstile.hostnames: frontend hostnames accepted in the siteverify response
 */
class TurnstileGuard extends Guard
{
    /**
     * Name of the form field the Turnstile widget adds to the form.
     *
     * @var string
     */
    const FIELD_NAME = 'cf-turnstile-response';

    /**
     * Siteverify endpoint.
     *
     * @var string
     */
    const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * {@inheritDoc}
     * Verify the Turnstile token. Rejects if the token is missing, invalid, expired,
     * already used, issued for another action or for an unexpected hostname.
     */
    public function perform()
    {
        $app = App::instance();
        $expectedAction = $this->requireOption('action');
        $secret = static::pluginOption('secret');
        $hostnames = array_filter((array) static::pluginOption('hostnames', []), 'is_string');
        $token = $app->request()->body()->get(self::FIELD_NAME);

        if (!is_string($secret) || trim($secret) === '' || empty($hostnames)) {
            error_log('Turnstile: sfb.turnstile.secret or sfb.turnstile.hostnames is not configured.');
            $this->reject('Der Spamschutz ist derzeit nicht verfügbar. Bitte versuchen Sie es später erneut.', self::FIELD_NAME);
        }

        if (!is_string($token) || $token === '' || strlen($token) > 2048) {
            $this->reject('Bitte füllen Sie den Spamschutz korrekt aus.', self::FIELD_NAME);
        }

        $result = $this->siteverify($secret, $token, $app->visitor()->ip());

        if ($result === null) {
            $this->reject('Der Spamschutz ist derzeit nicht verfügbar. Bitte versuchen Sie es später erneut.', self::FIELD_NAME);
        }

        if (
            ($result['success'] ?? false) !== true ||
            ($result['action'] ?? null) !== $expectedAction ||
            !in_array($result['hostname'] ?? null, $hostnames, true)
        ) {
            $this->reject('Bitte füllen Sie den Spamschutz korrekt aus.', self::FIELD_NAME);
        }
    }

    /**
     * Read an option of the sfb-turnstile plugin.
     *
     * Kirby's option() does not find a value that is written as nested array in the
     * config.php ('sfb' => ['turnstile' => ['secret' => ...]]) because the defaults of the
     * plugin are registered under the key 'sfb.turnstile' and shadow it. Therefore all
     * notations are supported (first match wins):
     * 1. flat key in the config.php: 'sfb.turnstile.secret' => ...
     * 2. nested array in the config.php: 'sfb' => ['turnstile' => ['secret' => ...]]
     * 3. default of the plugin (see sfb-turnstile/index.php)
     *
     * @param string $key Option name without the 'sfb.turnstile.' prefix
     * @param mixed $default
     * @return mixed
     */
    protected static function pluginOption($key, $default = null)
    {
        $options = App::instance()->options();
        $flatKey = 'sfb.turnstile.' . $key;

        if (isset($options[$flatKey])) {
            return $options[$flatKey];
        }

        if (isset($options['sfb']) && is_array($options['sfb']) && isset($options['sfb']['turnstile'][$key])) {
            return $options['sfb']['turnstile'][$key];
        }

        if (isset($options['sfb.turnstile']) && is_array($options['sfb.turnstile']) && isset($options['sfb.turnstile'][$key])) {
            return $options['sfb.turnstile'][$key];
        }

        return $default;
    }

    /**
     * Call the siteverify endpoint.
     *
     * @param string $secret Widget secret key
     * @param string $token Value of the cf-turnstile-response field
     * @param string|null $ip IP address of the visitor
     * @return array|null The decoded response or null if siteverify could not be reached
     */
    protected function siteverify($secret, $token, $ip = null)
    {
        $params = ['secret' => $secret, 'response' => $token];
        if (!empty($ip)) {
            $params['remoteip'] = $ip;
        }

        try {
            $client = new Client(['timeout' => 10, 'http_errors' => false]);
            $response = $client->post(self::VERIFY_URL, ['form_params' => $params]);
        } catch (\Throwable $e) {
            error_log('Turnstile: siteverify request failed: ' . get_class($e));

            return null;
        }

        if ($response->getStatusCode() !== 200) {
            error_log('Turnstile: siteverify returned HTTP ' . $response->getStatusCode());

            return null;
        }

        $result = json_decode((string) $response->getBody(), true);

        return is_array($result) ? $result : null;
    }
}
