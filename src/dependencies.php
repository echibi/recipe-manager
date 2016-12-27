<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ( $c ) {
	$settings = $c->get( 'settings' )['renderer'];

	return new Slim\Views\PhpRenderer( $settings['template_path'] );
};

// monolog
$container['logger'] = function ( $c ) {
	$settings = $c->get( 'settings' )['logger'];
	$logger   = new Monolog\Logger( $settings['name'] );
	$logger->pushProcessor( new Monolog\Processor\UidProcessor() );
	$logger->pushHandler( new Monolog\Handler\StreamHandler( $settings['path'], $settings['level'] ) );

	return $logger;
};

$container['db'] = function ( $c ) {
	$db = $c['settings']['db'];

	try {
		$config = array(
			'driver'   => 'mysql', // Db driver
			'host'     => $db['host'],
			'database' => $db['dbname'],
			'username' => $db['user'],
			'password' => $db['pass']
		);
		// QB is the new alias for accessing the DB
		new \Pixie\Connection( 'mysql', $config, 'QB' );

	} catch ( PDOException $e ) {
		$c->get( 'logger' )->alert( 'Database connection failed: ' . $e->getMessage() );
	}

	return \QB::pdo();
};
