<?php
/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 * @link       https://github.com/SungmanYou
 * @since      1.0.0
 * @package    Wib_Lqip
 * @subpackage Wib_Lqip/admin
 * @author     Sungman You <sungman.you@gmail.com>
 */
class Wib_Lqip_Admin
{

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    public function add_lqip_theme_support()
    {
        add_theme_support('lqip');
    }
    public function add_admin_menu()
    {
        add_options_page('WIB - LQIP', 'WIB - LQIP', 'manage_options', $this->plugin_name, function () {
            include (plugin_dir_path(__FILE__) . 'partials/wib-lqip-admin-display.php');
        });
    }
    public function register_settings()
    {
        $settings = array_merge(['wib_lqip_quality'], []);
        foreach ($settings as $setting) {
            register_setting("{$this->plugin_name}-settings-group", $setting);
        }
    }
    public function generate_attachment_metadata($metadata, $attachment_id)
    {
        global $_wp_theme_features;
        global $_wp_additional_image_sizes;
        if (!isset($_wp_theme_features['lqip'])) {
            return $metadata;
        }
        $image_sizes = is_array($_wp_theme_features['lqip'])
        ? $_wp_theme_features['lqip'][0]
        : apply_filters('intermediate_image_sizes_advanced', get_intermediate_image_sizes());
        $file = get_attached_file($attachment_id);
        $quality = get_option('wib_lqip_quality', 1);

        if (preg_match('!^image/!', get_post_mime_type($attachment_id)) && file_is_displayable_image($file)) {
            $path_parts = pathinfo($file);
            foreach ($image_sizes as $size) {
                if (isset($metadata['sizes'][$size])) {
                    $width = isset($_wp_additional_image_sizes[$size]['width']) ? intval($_wp_additional_image_sizes[$size]['width']) : get_option("{$size}_size_w");
                    $height = isset($_wp_additional_image_sizes[$size]['height']) ? intval($_wp_additional_image_sizes[$size]['height']) : get_option("{$size}_size_h");
                    $crop = isset($_wp_additional_image_sizes[$size]['crop']) ? intval($_wp_additional_image_sizes[$size]['crop']) : get_option("{$size}_crop");
                    $new_size = $size . '-lqip';
                    $filename = str_replace('.' . $path_parts['extension'], '-lqip.' . $path_parts['extension'], $metadata['sizes'][$size]['file']);
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