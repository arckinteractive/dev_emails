<?php

$plugin = $vars['entity'];
/* @var ElggPlugin $plugin */

$title = "Dev Emails";

$body = '<label>Allowed Email Addresses</label>';
$body .= '<div class="elgg-text-help">Enter one email per line. These emails will be able to receive email from the system.</div>';
$body .= elgg_view('input/plaintext', array(
	'name' => 'params[emails]',
	'value' => $plugin->emails
));

$body .= '<label>Allowed Email Domains</label>';
$body .= '<div class="elgg-text-help">Enter one domain per line. These domains will be able to receive email from the system.</div>';
$body .= elgg_view('input/plaintext', array(
	'name' => 'params[domains]',
	'value' => $plugin->domains
));

$body .= '<label>Blocked e-mail log</label>';
$body .= '<div class="elgg-text-help">Enter a file path. Blocked e-mails will be appended to the file.</div>';
$body .= elgg_view('input/text', array(
	'name' => 'params[blocked_log]',
	'value' => $plugin->blocked_log
));

$body .= '<div class="mtm">';
$body .= '<label>';
$body .= elgg_view('input/checkbox', array(
	'name' => 'params[debug]',
	'value' => '1',
	'default' => '',
	'checked' => (bool)$plugin->debug,
));
$body .= ' Debug</label>';
$body .= '<div class="elgg-text-help">If checked, email sending status will be printed in the error log.</div>';
$body .= '</div>';

echo elgg_view_module('main', $title, $body);