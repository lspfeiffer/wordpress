<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'teaser');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'Z5`[1Z7_wFwjetIwMP=N%dXyt&Fs+;.<syJTitw8C!XxKMQw)_ uq%I/$|zG,|4t');
define('SECURE_AUTH_KEY',  '{)HZ[Q`1hsBwB|9oE%jdDHkGCP~q|;Vw&N~rFo+r9pN{!.%RTVyCJ8?TnuQmkjC6');
define('LOGGED_IN_KEY',    '/lC<eo20T55Teg;P1~r-3n|>W3+-m$<XtuP1Q&,XH1:1o2iQ|{ZC.TT{^;lih17/');
define('NONCE_KEY',        'Fli15ERI:8ox|xYn,7/0nfI(L7Y)iXbNkjI8=_tal^eAGV#.ZbUSsZ2+^gj2|#3B');
define('AUTH_SALT',        'lnfS{*B(94 <BRYVctW=^5pqn#.mH( rC7Fn.Bo+lgAANW+jN 8@5~|p)Cbo@F5v');
define('SECURE_AUTH_SALT', '<HV6+7p/8ycr! Z~C[B&uO>w.}p?&Xo1Xq-_|2>yL=,M{f%MtU6fi8EmvC_P#gl,');
define('LOGGED_IN_SALT',   'i&>~TH9r5<aA4i%>bZ)6Z[[C!f|_|w_4y1J6ah/2~-.V1-e0G/,Fw.>50%bZ^`?}');
define('NONCE_SALT',       'PlGPRDv8|IP=^fDeQmC[V#K-~gRtAkUX4ba&5 87rZ)@/q%_w-~5{t@kzRkA~|yJ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
