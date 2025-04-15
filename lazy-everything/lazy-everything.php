<?php
/*
Plugin Name: Lazy Everything
Plugin URI: https://github.com/millaw/lazy-everything
Description: Global lazy loading for files, images, iframes, and videos.
Version: 3.0
Author: Milla Wynn
Author URI: https://github.com/millaw
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: lazy-everything
*/

if (!defined('ABSPATH')) exit;

function lazy_everything_enqueue_assets() {
    wp_enqueue_script('lazy-everything-js', plugins_url('assets/lazyload.js', __FILE__), [], null, true);
}
add_action('wp_enqueue_scripts', 'lazy_everything_enqueue_assets');

function lazy_everything_filter($content) {
    // Lazy load file links
    $content = preg_replace_callback('/<a[^>]+href=["\']([^"\']+\.(pdf|docx?|xlsx?|zip))["\'][^>]*>(.*?)<\/a>/i', function($matches) {
        return '<a href="#" data-href="' . esc_url($matches[1]) . '" data-lazy-file>' . $matches[3] . '</a>';
    }, $content);

    // Lazy load images
    $content = preg_replace('/\sfetchpriority=["\']high["\']/', '', $content);
    $content = preg_replace_callback('/<img((?!loading=)[^>])*?>/i', function($matches) {
        $img = $matches[0];
        if (strpos($img, 'loading=') === false) {
            $img = str_replace('<img', '<img loading="lazy"', $img);
        }
        return $img;
    }, $content);

    // Lazy load iframes/videos
    $content = preg_replace('/<iframe([^>]+?)src=["\']([^"\']+)["\']/', '<iframe$1data-src="$2"', $content);
    $content = preg_replace('/<video([^>]+?)src=["\']([^"\']+)["\']/', '<video$1data-src="$2"', $content);
    return $content;
}
add_filter('the_content', 'lazy_everything_filter');
?>
