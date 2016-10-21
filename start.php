<?php

/**
 * Developer email whitelist
 * A tool for suppressing system emails for testing
 *
 * @copyright (c) 2016, ArckInteractive LLC
 */
require_once __DIR__ . '/autoloader.php';

use ArckInteractive\DevEmails\Dispatch;

elgg_register_plugin_hook_handler('email', 'system', [Dispatch::class, 'sendEmail'], 0);
elgg_register_plugin_hook_handler('send', 'notification:email', [Dispatch::class, 'sendNotification'], 0);
