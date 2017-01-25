<?php

$plugin = elgg_extract('entity', $vars);

echo elgg_view_input('plaintext', [
	'name' => 'params[emails]',
	'value' => $plugin->emails,
	'label' => 'Allowed Email Addresses',
	'help' => 'Enter one email per line. These emails will be able to receive email from the system.',
]);

echo elgg_view_input('plaintext', [
	'name' => 'params[domains]',
	'value' => $plugin->domains,
	'label' => 'Allowed Email Domains',
	'help' => 'Enter one domain per line. These domains will be able to receive email from the system.',
]);