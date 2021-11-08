<?php
/**
 * @package    WordPress
 * @subpackage Traveler
 * @since      1.0
 *
 * function
 *
 * Created by ShineTheme
 *
 */
if (!defined('ST_TEXTDOMAIN'))
    define('ST_TEXTDOMAIN', 'traveler');
if (!defined('ST_TRAVELER_VERSION')) {
    $theme = wp_get_theme();
    if ($theme->parent()) {
        $theme = $theme->parent();
    }
    define('ST_TRAVELER_VERSION', $theme->get('Version'));
}
define("ST_TRAVELER_DIR", get_template_directory());
define("ST_TRAVELER_URI", get_template_directory_uri());

global $st_check_session;

if ( is_session_started() === FALSE ){
    $st_check_session = true;
    session_start();
}

$status = load_theme_textdomain('traveler', get_stylesheet_directory() . '/language');

get_template_part('inc/class.traveler');
get_template_part('inc/extensions/st-vina-install-extension');

if (!class_exists("Abraham\TwitterOAuth\TwitterOAuth")) {
    include_once "vendor/autoload.php";
}
add_filter('http_request_args', 'st_check_request_api', 10, 2);

function st_check_request_api($parse, $url) {
    global $st_check_session;
    if ($st_check_session) {
        session_write_close();
    }

    return $parse;
}
function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}
add_filter('upload_mimes', 'traveler_upload_types', 1, 1);

function traveler_upload_types($mime_types) {
    $mime_types['svg'] = 'image/svg+xml';

    return $mime_types;
}

add_theme_support(
    'html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
        )
);
//get_template_part('demo/landing_function');
//get_template_part('demo/demo_functions');
//get_template_part('quickview_demo/functions');
//get_template_part('user_demo/functions');

// laksh - start

function my_posts_groupby($groupby) {
    global $wpdb;
    $groupby = "{$wpdb->posts}.post_author";
    return $groupby;
}
add_filter( 'posts_groupby', 'my_posts_groupby' );

// laksh - end
