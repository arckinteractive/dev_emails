<?php

$title = "Dev Emails";

$body = '<label>Allowed Email Addresses</label>';
$body .= elgg_view('input/plaintext', array(
	'name' => 'params[emails]',
	'value' => $vars['entity']->emails
));
$body .= elgg_view('output/longtext', array(
	'value' => "Enter one email per line.  These emails will be able to receive email from the system."
));

$body .= '<label>Allowed Email Domains</label>';
$body .= elgg_view('input/plaintext', array(
	'name' => 'params[domains]',
	'value' => $vars['entity']->domains
));
$body .= elgg_view('output/longtext', array(
	'value' => "Enter one domain per line.  These domains will be able to receive email from the system."
));

$body .= '<label>';
$options = array(
	'name' => 'params[debug]',
	'value' => 1
);
if ($vars['entity']->debug) {
	$options['checked'] = "checked";
}
$body .= elgg_view('input/checkbox', $options);
$body .= 'Debug</label>';
$body .= elgg_view('output/longtext', array(
	'value' => "If checked, email sending status will be printed in the error log"
));

echo elgg_view_module('main', $title, $body);