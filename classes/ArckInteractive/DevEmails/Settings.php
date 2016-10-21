<?php

namespace ArckInteractive\DevEmails;

class Settings {

	/**
	 * @var array
	 */
	private static $emails;

	/**
	 * @var array
	 */
	private static $domains;

	/**
	 * Check if email address is whitelisted
	 * 
	 * @param string $email Email address
	 * @return bool
	 */
	public static function isWhitelisted($email = '') {

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}

		$emails = self::getWhiteListedEmails();
		$domains = self::getWhiteListedDomains();
		
		list(, $domain) = explode('@', $email);

		return in_array($email, $emails) || in_array($domain, $domains);
	}

	/**
	 * Normalize emails/domains (trim, lowercase etc)
	 * 
	 * @param string $email Email/domain
	 * @return string
	 */
	public static function normalize($email = '') {
		$email = trim($email);
		$email = strtolower($email);
		return $email;
	}

	/**
	 * Returns an array of whitelisted emails
	 * @return array
	 */
	public static function getWhiteListedEmails() {
		if (!isset(self::$emails)) {
			$setting = elgg_get_plugin_setting('emails', 'dev_emails', '');
			$emails = explode(PHP_EOL, $setting);
			$emails = array_map([self, 'normalize'], $emails);
			$emails = array_filter($emails, function($email) {
				return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
			});
			self::$emails = $emails;
		}
		return self::$emails;
	}

	/**
	 * Returns an array of whitelisted domains
	 * @return array
	 */
	public static function getWhiteListedDomains() {
		if (!isset(self::$domains)) {
			$setting = elgg_get_plugin_setting('domains', 'dev_emails', '');
			$domains = explode(PHP_EOL, $setting);
			$domains = array_map([self, 'normalize'], $domains);
			$domains = array_filter($domains);
			self::$domains = $domains;
		}
		return self::$domains;
	}
	
}
