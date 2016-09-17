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
define('DB_NAME', 'sara');

/** MySQL database username */
define('DB_USER', 'sara');

/** MySQL database password */
define('DB_PASSWORD', '260888');

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
define('AUTH_KEY',         '0-<P/l1>y|+zyMo}V8vZzAO(+=?b5aJC;&}+O&`3@FfOX?fI-,!VfV/,)B[M8C1z');
define('SECURE_AUTH_KEY',  'yYQC_kfOTl-n^&eszx?PagM2?>]!fBYJ6$%k(cj=zI=Tf=>/Sd`N2l@ZjF1--c_Q');
define('LOGGED_IN_KEY',    'eldH|g|`3u8)mhR2b(by/U=h=PLf3rwKpuX{(AB<vS>YO/_eZ38~uk4?&-wXUs^G');
define('NONCE_KEY',        '50l3A)+JzjN9DDf4LT.z@Al*w@>w(q<e*>:FlXxSC-%dJbS`gPh-<v8<1*J9!-pt');
define('AUTH_SALT',        '/,E?9v5m &H6|`z>Vl~;,:#d$YWOMT}$u`y%s[Du8]S}h)HZ@LqTy*V4cZL$q8(W');
define('SECURE_AUTH_SALT', '3Dh8:Jm~u ,=br) >QJRFW^]/=T,[iV{>vs#3)^nCMB+/oy4`Qrg<_L}n85bZA71');
define('LOGGED_IN_SALT',   'A|!!q8d=xH%J^|Bp0r46Ho5{ZUeV^:&VFEi_6v7rfD_{K7+cj#GqRk-}J2F]qvO-');
define('NONCE_SALT',       '1a0ts]DY9BJ@!)wNV1@<ycR9+%n)h+m+8d EU@Ewu*2|-/AIL!}ea(? ja2ep<%F');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpSara_';

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
//define('WP_DEBUG', true);
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

//troll: 
//define('FS_METHOD','direct');

//http://www.blogaid.net/disable-xml-rpc-in-wordpress-to-prevent-ddos-attack/
//add_filter('xmlrpc_enabled', '__return_false');

//File editor disable.
define('DISALLOW_FILE_EDIT', TRUE);

//prevent mails wordpress?
function wp_mail(){
    // Do nothing!
}