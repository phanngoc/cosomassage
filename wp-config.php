<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'phanngoc_massage');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         't.a%1i8L|;ke//+U?rI`jD6}u@p3e2GS4=Y;ZBwKg5z=ZNat@=ThH~H?q!MkDbKR');
define('SECURE_AUTH_KEY',  '@KHp7{5jKdbH]8[0q6^ZAF;n3N+b~nKu*sW|1X)fKc^/Phx0jAa}m+%.IMd//`vH');
define('LOGGED_IN_KEY',    'FZ`ue^QBS6Udomp)>=z2-eQ-sMh[FTb.)wK]fE6ei9e3|8|KbU}8~;kF^AcFqWP!');
define('NONCE_KEY',        'NhHDRT3tNlSyI,~j&m]Z&FA{GCD%Iix!DAQ?&;;HE35jC|y%0KVI0<-mQX|F5}O5');
define('AUTH_SALT',        'cP+yC@)*sDak.h1nx+hH5rqpU0T$be.|uEVT2}u[<q/WoZt1M1F,!,0ld4zr_ysv');
define('SECURE_AUTH_SALT', '[irO{(a/s+mAo%Mo#+^B7kNy.-^UId;K+Qzia(z3CWK!h)}J^wqd8_MgtXZv5BYN');
define('LOGGED_IN_SALT',   'vi5b9u(UH`ne]f?i_:d00yju/TrZ=T(z@[LNSh 0eWE8.Y6qW38=YG +^_MP)KM_');
define('NONCE_SALT',       '}UH~`o5Tw9~A-BlIp}5vpZpWkUNVL!Qe1q[-ugcP<DF1&gEstdE:nnnUsUDDY.@O');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
// Turns WordPress debugging on
define('WP_DEBUG', true);

// Tells WordPress to log everything to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);

// Doesn't force the PHP 'display_errors' variable to be on
//define('WP_DEBUG_DISPLAY', false);

// Hides errors from being displayed on-screen
//@ini_set('display_errors', 0);
//define('SCRIPT_DEBUG', true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
