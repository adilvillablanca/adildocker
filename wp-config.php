<?php
define( 'DB_NAME', 'wordpressadil' );
define( 'DB_USER', 'admin' );
define( 'DB_PASSWORD', 'Duoc.2023' );
define( 'DB_HOST', 'bbddwordpress-instance-1.c2fps3hftiub.us-east-1.rds.amazonaws.com' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY',         'Duoc.2023' );
define( 'SECURE_AUTH_KEY',  'Duoc.2023' );
define( 'LOGGED_IN_KEY',    'Duoc.2023' );
define( 'NONCE_KEY',        'Duoc.2023' );
define( 'AUTH_SALT',        'Duoc.2023' );
define( 'SECURE_AUTH_SALT', 'Duoc.2023' );
define( 'LOGGED_IN_SALT',   'Duoc.2023' );
define( 'NONCE_SALT',       'Duoc.2023' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

define( 'WP_LANG', 'es_CL' );

require_once ABSPATH . 'wp-settings.php';
