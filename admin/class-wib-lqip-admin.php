<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/SungmanYou
 * @since      1.0.0
 *
 * @package    Wib_Lqip
 * @subpackage Wib_Lqip/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wib_Lqip
 * @subpackage Wib_Lqip/admin
 * @author     Sungman You <sungman.you@gmail.com>
 */
class Wib_Lqip_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wib-lqip-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wib-lqip-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Add a sub-level menu item under the Settings top-level menu in administration screens.
     * @link https://codex.wordpress.org/Adding_Administration_Menus
     * @since    1.0.0
     */
    public function add_admin_menu()
    {
        add_submenu_page(
            'options-general.php',
            'WIB - LQIP',
            'WIB - LQIP',
            'manage_options',
            'wib-lqip',
            function () {
                include (plugin_dir_path(__FILE__) . 'partials/wib-lqip-admin-display.php');
            }
        );
    }

    /**
     * Whitelist settings
     * @link https://codex.wordpress.org/index.php?title=Creating_Options_Pages&oldid=97268
     * @since    1.0.0
     */
    public function register_settings()
    {
        register_setting('wib-lqip-settings-field', 'wib_lqip_quality');
        register_setting('wib-lqip-settings-field', 'wib_lqip_thumbnail_enabled');
        register_setting('wib-lqip-settings-field', 'wib_lqip_attachment_enabled');
        register_setting('wib-lqip-settings-field', 'wib_lqip_woocommerce_archive_product_enabled');
        register_setting('wib-lqip-settings-field', 'wib_lqip_woocommerce_single_product_enabled');
    }

    public function generate_attachment_metadata($metadata, $attachment_id)
    {
        global $_wp_theme_features;
        if (!isset($_wp_theme_features['lqip'])) {
            return $metadata;
        }
        global $_wp_additional_image_sizes;
        if (is_array($_wp_theme_features['lqip'])) {
            $image_sizes = $_wp_theme_features['lqip'][0];
        } else {
            $image_sizes = get_intermediate_image_sizes();
            $image_sizes = apply_filters('intermediate_image_sizes_advanced', $image_sizes);
        }
        $file = get_attached_file($attachment_id);
        $quality = apply_filters('lqip_quality', 5);

        if (
            preg_match('!^image/!', get_post_mime_type($attachment_id)) &&
            file_is_displayable_image($file)
        ) {
            $path_parts = pathinfo($file);

            foreach ($image_sizes as $size) {
                if (isset($metadata['sizes'][$size])) {
                    if (isset($_wp_additional_image_sizes[$size]['width'])) {
                        $width = intval($_wp_additional_image_sizes[$size]['width']);
                    } else {
                        $width = get_option("{$size}_size_w");
                    }

                    if (isset($_wp_additional_image_sizes[$size]['height'])) {
                        $height = intval($_wp_additional_image_sizes[$size]['height']);
                    } else {
                        $height = get_option("{$size}_size_h");
                    }

                    if (isset($_wp_additional_image_sizes[$size]['crop'])) {
                        $crop = intval($_wp_additional_image_sizes[$size]['crop']);
                    } else {
                        $crop = get_option("{$size}_crop");
                    }

                    $new_size = $size . '-lqip';
                    $filename = str_replace(
                        '.' . $path_parts['extension'],
                        '-lqip.' . $path_parts['extension'],
                        $metadata['sizes'][$size]['file']
                    );

                    $image = wp_get_image_editor($file);
                    $image->resize($width, $height, $crop);
                    $image->set_quality($quality);
                    $image->save($path_parts['dirname'] . '/' . $filename);

                    if (!is_wp_error($image)) {
                        $metadata['sizes'][$new_size] = $metadata['sizes'][$size];
                        $metadata['sizes'][$new_size]['file'] = $filename;
                    }
                }
            }
        }
        return $metadata;
    }

}