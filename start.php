<?php

namespace Arck\DevEmails;

// trigger last so we can cache the last registered email handler
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init', 999);

function init() {
	elgg_register_plugin_hook_handler('email', 'system', __NAMESPACE__ . '\\send_email', 0);
	elgg_register_plugin_hook_handler('send', 'notification:email', __NAMESPACE__ . '\\email_notifications_send', 0);
}

function send_email($h, $t, $r, $p) {
	$emails = get_email_whitelist();
	$domains = get_domain_whitelist();

	if (in_array($p['to'], $emails)) {
		return $r; // we'll allow it
	}

	$to_domain = explode('@', $p['to']);
	if (in_array($to_domain[1], $domains)) {
		return $r; // we'll allow it
	}

	return false;
}

function get_email_whitelist() {
	static $emails;
	if ($emails) {
		return $emails;
	}

	$email_str = elgg_get_plugin_setting('emails', 'dev_emails');
	$email_arr = explode("\n", $email_str);
	$emails = array_map('trim', $email_arr);

	return $emails;
}

function get_domain_whitelist() {
	static $domains;
	if ($domains) {
		return $domains;
	}

	$domain_str = elgg_get_plugin_setting('domains', 'dev_emails');
	$domain_arr = explode("\n", $domain_str);
	$domains = array_map('trim', $domain_arr);

	return $domains;
}

function email_notifications_send($hook, $type, $return, $params) {
	$message = $params['notification'];

	$recipient = $message->getRecipient();

	if (!$recipient || !$recipient->email) {
		return $return;
	}
	
	$emails = get_email_whitelist();
	$domains = get_domain_whitelist();

	if (in_array($recipient->email, $emails)) {
		return $return; // we'll allow it
	}

	$to_domain = explode('@', $recipient->email);
	if (in_array($to_domain[1], $domains)) {
		return $return; // we'll allow it
	}

	return true; // indicate we've already sent something
}
