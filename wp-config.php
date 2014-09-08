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
define('DB_NAME', 'professi');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

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
define('AUTH_KEY',         ';MK=|Sm-sVIfmM{`W+)..w5[e@K.HdA4E;J>-)[L&V`.,x$,2AQ7~c3W!w unQz*');
define('SECURE_AUTH_KEY',  't1K?a[ul(8siZl^!WT&pmbs8Itn+#X*v_BAaY*Z63W3`@b)XVpcE71}l|?*rih%R');
define('LOGGED_IN_KEY',    '4*XW+e!qxg:/2J|p@,|@$KVensn:H,f}S?0.eXSR[19ppT+RCvZ(|<(mso[HA9nf');
define('NONCE_KEY',        'K+T$*jG{yQ&+dI3Q+?D%N O{Ly~hn+J|$~=Lsu=_D6*&Fw,i&>sO(C23h&`]`S#+');
define('AUTH_SALT',        ',3q6f8U#gK^Tp39xnl$}5#QoZlxtJ&6qmAwyZohbk/-w!3.-:Qa:-G!^w_t}-S([');
define('SECURE_AUTH_SALT', '1ya}wsHTLxG gZRzYW[I-0iLrXOWPUm-Ybg?<U[c*4|3wCri%Tgi^yXARwqtffXh');
define('LOGGED_IN_SALT',   'j>J1Q.=b][ogTYU}J`,IxLWDq#TE9Tc,;juD&rfrwoV#i9>!!-ds;Q.^}W)lI=tc');
define('NONCE_SALT',       '#|+|4^5]vXY:_+qd1K5Y.b7}2|6mXwH=pV>Q~ekyI8tb7VT6#+Y%M)WG^CT=Pi+^');

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
