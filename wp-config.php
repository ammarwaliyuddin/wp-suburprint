<?php
define( 'WP_CACHE', true ); // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_sp' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         '(RpLl)54>]Dx1eoKSB3+LU(_/ tv@oC`gWYE+eGtL-b|DSU61Z+QvzB{`=ik@{;~' );
define( 'SECURE_AUTH_KEY',  'vf/d 6W7FAI}u65k W<XhPh>(gCm.l|)x0N^jB}K==)ABy2M/i&74E%ei5^=j?+7' );
define( 'LOGGED_IN_KEY',    'Pf0oHaVr(GHHRK:E,8PEZ]7Ltf&#s 74S5CC0RL`gp_^sdCngUyEp*%pDrq}ArYZ' );
define( 'NONCE_KEY',        '^T6*{mx`XE3H^.3EVnm3H9igoeb@V 51@C[ JfIBv<3G$PQ^(L#V][wPN{Oy|L}j' );
define( 'AUTH_SALT',        '?708Egjn!2y{9<#&Mr=<Z^jU;[20m}@QAn-eC4}n- 4NI7u+#t&U BBlQFKCg~eq' );
define( 'SECURE_AUTH_SALT', 'eT7CpQ(]1D0!#>?:(N)vxSa@n=(uM+v&)]n1;I#c)_(3zFct0pPgv(nu0[tnxH=!' );
define( 'LOGGED_IN_SALT',   'OW3H8] PS^l^$ljv[i]n`Tv6Mw+ni3uTtE-_8q&}{A-q+/B:)| v }1nE #n)G|4' );
define( 'NONCE_SALT',       '{e*X,*yqs,}]+}-P:vOVEjf4z?>@,i2S<C(FiJ#3CoD|n|_MZ1s[A>`B=gc3ktNF' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'tb_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
