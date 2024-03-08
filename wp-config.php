<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'servicesite' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'M6@_#ruNE!HuS#oN04Ra' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'h88#tf}uNZA;V3C$%6OxKFDT1q3%5| UcrRt@D SDNX9(s;mIr%92zH%&/ Cm`Sm' );
define( 'SECURE_AUTH_KEY',  '-dm_:4=swYvBGw<uf.nlVx[Fn0 YZj=v:(RV>{W[jKIXTCKsB#ECLe2u}_x0e@I0' );
define( 'LOGGED_IN_KEY',    '9%bl%xx g1YaYo]upD1Hu=[%Yg!Dy#WGn?EuKdwia@&5$2IyT0ccu;SB]KyY?m4X' );
define( 'NONCE_KEY',        'lgoR7P%CyOF%#(($sH}h]aD2JjTnFom+uYitO~<esw%8SAZ[]a<+MZ-1yo104LA[' );
define( 'AUTH_SALT',        'ddznAIs]ye`@jVP!$4:i/ruf=3vo>kH|!fht;5q^-rp,4tN!SQFhG1Kdfv{Sp[}=' );
define( 'SECURE_AUTH_SALT', 'L,rLVg4=[dUzp`4Z<:52Y8Y}]UU*ZlJ<+nn@G4/A$hl<C&gdGqSZq#yB!b9Zy.[{' );
define( 'LOGGED_IN_SALT',   'pc*e0AloD=t$4S1*Z[jJNp{]LSa^b$~T{1u6NEF(M||7|9+4}e9BhJ<,zA]}ci.M' );
define( 'NONCE_SALT',       'Lf38uxWl30Tgy#yD=htSi_lChQ4f)Ygv$pc!Kw0E#T<7G:^rCSS^LynqghGdS+@~' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
