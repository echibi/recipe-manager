<?php
return [
	'settings' => [
		'displayErrorDetails'    => true, // set to false in production
		'addContentLengthHeader' => false, // Allow the web server to send the content-length header

		// Database
		'db'                     => [
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'dbname' => 'recept-api'
		],

		// Renderer settings
		'renderer'               => [
			'template_path' => __DIR__ . '/../templates/',
		],

		// Monolog settings
		'logger'                 => [
			'name'  => 'recipe-api',
			'path'  => __DIR__ . '/../logs/app.log',
			'level' => \Monolog\Logger::DEBUG,
		],
	],
];

