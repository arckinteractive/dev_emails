<?php

namespace ArckInteractive\DevEmails;

use Elgg\Mail\Address;
use Zend\Mail\Message;
use Zend\Mail\Transport\File;
use Zend\Mail\Transport\FileOptions;

class Dispatch {

	/**
	 * Prevent the system from sending emails to non-whitelisted emails and domains
	 *
	 * @param string $hook   "email"
	 * @param string $type   "system"
	 * @param mixed  $return Status or new params
	 * @param array  $params Hook params
	 * @return bool
	 */
	public static function sendEmail($hook, $type, $return, $params) {

		$params['to'] = Address::fromString(elgg_extract('to', $params, ''));
		$to_address = $params['to']->getEmail();

		if (Settings::isWhitelisted($to_address)) {
			// Email or domain of the recipient is whitelisted
			elgg_log("[dev_emails] Allowing Email: $to_address");
			return;
		}

		$params['from'] = Address::fromString($params['from']);

		$params['subject'] = elgg_strip_tags($params['subject']);
		$params['subject'] = html_entity_decode($params['subject'], ENT_QUOTES, 'UTF-8');
		// Sanitise subject by stripping line endings
		$params['subject'] = preg_replace("/(\r\n|\r|\n)/", " ", $params['subject']);
		$params['subject'] = elgg_get_excerpt(trim($params['subject'], 80));

		$params['body'] = elgg_strip_tags($params['body']);
		$params['body'] = html_entity_decode($params['body'], ENT_QUOTES, 'UTF-8');
		$params['body'] = wordwrap($params['body']);

		$message = new Message();
		foreach ($params['headers'] as $headerName => $headerValue) {
			$message->getHeaders()->addHeaderLine($headerName, $headerValue);
		}

		$message->setEncoding('UTF-8');
		$message->addFrom($params['from']);
		$message->addTo($params['to']);
		$message->setSubject($params['subject']);
		$message->setBody($params['body']);

		$dirname = elgg_get_config('dataroot') . 'notifications_log/dev_emails/';
		if (!is_dir($dirname)) {
			mkdir($dirname, 0700, true);
		}

		$params = array(
			'path' => $dirname,
			'callback' => function() {
				return 'Message_' . microtime(true) . '_' . mt_rand() . '.txt';
			},
		);
		$transport = new File(new FileOptions($params));
		$transport->send($message);

		elgg_log("[dev_emails] Blocking Email: $to_address. Message written to disk in $dirname");
		return false;
	}

	/**
	 * Handle email notifications
	 *
	 * @param string $hook   "send"
	 * @param string $type   "notification:email"
	 * @param bol    $return Delivery status
	 * @param array  $params Hook params
	 * @return bool
	 */
	public static function sendNotification($hook, $type, $return, $params) {
		// make sure we use the default system sender so that delivery is suppressed by the other hook
		// plugins implementing custom handlers tend to unregister the default handler
		// and use their own funtions without triggering the system email hook
		return _elgg_send_email_notification($hook, $type, $return, $params);
	}

}
