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

// ** Database settings - This information is provided by your hosting provider ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('WORDPRESS_DB_NAME') ?: 'wordpress' );

/** MySQL database username */
define( 'DB_USER', getenv('WORDPRESS_DB_USER') ?: 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD') ?: 'wordpress' );

/** MySQL hostname */
define( 'DB_HOST', getenv('WORDPRESS_DB_HOST') ?: 'wp_db:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'NaEr<i{YRB|d}1NupH^=#j| <D}bV.?DGO}@p=iq$k9Rqc>D(ymsv&eph{rVYd$o' );
define( 'SECURE_AUTH_KEY',  ';^;vq-H=zdVbB[Q3aGdQO#9?)E<CNo}]>cmc+f&d:NH)wop lOu^yL$##iImoy&3' );
define( 'LOGGED_IN_KEY',    '2uS5hF3]r;y0U\/*Ih\/|\/zI.IkDU6+r-I-FM!H`pyV(9to0F*Duj6|^]B|}e 6~Aw' );
define( 'NONCE_KEY',        'M?a$E~6SS9xE[ly&Q(BmyA^xA :l>MP\/)1.33:kMerJLU=B`<rn=cLh,B!mWjdR3' );
define( 'AUTH_SALT',        'W9I8j0k8:S&^i*MW =6g 2eMz0F m\/^>%kank9C^\/CsB.LOF1[zBPgY~*4x`_>fq' );
define( 'SECURE_AUTH_SALT', 'kf.M@nS^+D:O4;uSfxC>9)dUt{}M(3v\/*}jYz!O 1Lg#4FW`CGWyWtzR0K=h]OJ:' );
define( 'LOGGED_IN_SALT',   'Jf8Z?&8,~<qFdQ^7T&gx.qii{`LbC<8lrL-Qz2YXiMI[)3C:n39HYK:}B:)A9;ad' );
define( 'NONCE_SALT',       '%pjDr1*md>!_c&l-xX,lECe=r>uW3h,b&2^>,}<K<,nxQ<KIw1^8fPA,&8jXG>p?' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv('WORDPRESS_TABLE_PREFIX') ?: 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices
 * during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', getenv('WORDPRESS_DEBUG') ?: false );

/* Add any custom values between this line and the "stop editing" comment. */

// Memory limit
define('WP_MEMORY_LIMIT', '256M');

// Disable file editing
define('DISALLOW_FILE_EDIT', true);

// Handle reverse proxy setup
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// Fix for reverse proxy
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

// Set WordPress URL and Site URL (configurable via environment variables)
define('WP_HOME', getenv('WORDPRESS_HOME') ?: 'https://sklztect.siliconhaveli.in');
define('WP_SITEURL', getenv('WORDPRESS_SITEURL') ?: 'https://sklztect.siliconhaveli.in');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
