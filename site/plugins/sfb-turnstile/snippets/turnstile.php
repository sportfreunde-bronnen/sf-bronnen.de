<?php
/**
 * Cloudflare Turnstile widget.
 *
 * Usage: <?php snippet('turnstile', ['action' => 'contact']); ?>
 * The action must match the action of the TurnstileGuard in the controller of the page.
 */
?>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<div class="cf-turnstile" data-theme="light" data-sitekey="<?= esc(option('sfb.turnstile.sitekey'), 'attr') ?>" data-action="<?= esc($action, 'attr') ?>"></div>
