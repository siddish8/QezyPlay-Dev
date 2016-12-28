<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
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
define('DB_NAME', 'tradmin_newqezy');

/** MySQL database username */
define('DB_USER', 'tradmin_qezyplay');

/** MySQL database password */
define('DB_PASSWORD', '&(qezy@word)&');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'tzV[b&l~*]>viZmDO6bWSnF2jN:R/UWzWoiBfQYWWyP%!),SXOXYvQ^7um8O/mgR');
define('SECURE_AUTH_KEY',  'auY6P7I+zKG+Y/+}qg0w7u9Ls|)!h UIu%_VV-8uVOb?c-or#[n&c;cB@.^$,mr[');
define('LOGGED_IN_KEY',    '`[8}a`O_tlkz#Km$pgObK(RO5QR7cy%gU wtZR@EWiA$5|}#|s]-7q)ABkn^0T;x');
define('NONCE_KEY',        'MG)-)kob<>,2;cl4raZufncW}b3xD4=iI{R%i5N-,|R{ou[e{=J-Ce*t`HhXA+n2');
define('AUTH_SALT',        '|/-&-XISeLM?m~UO~Y<rhuRN+~QOHyTH2flyv7?XFF7JB~;&IzgG~lC?]eR{h:(}');
define('SECURE_AUTH_SALT', 'NE /B/+-qL(8?}D!Z?OOCTQN%A@6sI5.]mzp-7;GkGu6WHb@Xc)Zowrna1qbj50/');
define('LOGGED_IN_SALT',   'T|nyszWds+oikc5oOzmX3+z{doua#x!f`-@DCtAqSDILL_Lz4~|T6T`wSrrPgZ}Q');
define('NONCE_SALT',       '$G{h &,o8{gIlSbnYmWAD1$>PRiY{|EqydoLZzM$E_R}dcKe<Pl08C5kMa+neyW-');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
