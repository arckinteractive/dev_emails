<?php

namespace dev_emails;
use ElggEntity;
use ElggUser;

// trigger last so we can cache the last registered email handler
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init', 999);

function init() {
	global $NOTIFICATION_HANDLERS;
	
	elgg_register_plugin_hook_handler('email', 'system', __NAMESPACE__ . '\\send_email', 0);
	
	elgg_set_config('dev_emails_notification_handler', $NOTIFICATION_HANDLERS['email']->handler);
	register_notification_handler("email", __NAMESPACE__ . '\\email_notification_handler');
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


function email_notification_handler(ElggEntity $from, ElggUser $to, $subject, $message, array $params = NULL) {
	$emails = get_email_whitelist();
	$domains = get_domain_whitelist();
	
	$handler = elgg_get_config('dev_emails_notification_handler');
	
	if (in_array($to->email, $emails)) {
		return $handler($from, $to, $subject, $message, $params); // we'll allow it
	}
	
	$to_domain = explode('@', $to->email);
	if (in_array($to_domain[1], $domains)) {
		return $handler($from, $to, $subject, $message, $params); // we'll allow it
	}
	
	return true;
}
