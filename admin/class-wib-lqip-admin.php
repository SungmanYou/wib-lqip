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

    private function convert_png_to_jpg($params)
    {
        $stats_before = filesize($params['file']);
        $img = imagecreatefrompng($params['file']);
        $bg = imagecreatetruecolor(imagesx($img), imagesy($img));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, 1);
        imagecopy($bg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
        $valid_exts = ['jpg', 'jpeg', 'jpe', 'jif', 'jfi', 'jfif'];
        do {
            $new_ext = array_shift($valid_exts);
            $new_path = preg_replace("/\.png$/", "." . $new_ext, $params['file']);
        } while (file_exists($new_path));
        if (!file_exists($new_path)) {
            $new_url = preg_replace("/\.png$/", "." . $new_ext, $params['url']);
            $quality = apply_filters('wib_lqip_jpg_quality', 100);
            if (imagejpeg($bg, $new_path, $quality)) {
                $stats_after = $stats_before - filesize($new_path);
                unlink($params['file']); // Remove original file
                $params['file'] = $new_path;
                $params['url'] = $new_url;
                $params['type'] = 'image/jpeg';
                return $params;
            }
        }
        return 0;
    }
    public function check_image_type($params)
    {
        switch (true) {
            case $params['type'] === 'image/png':
                $new_params = $this->convert_png_to_jpg($params);
                if ($new_params) {$params = $new_params;}
                break;
            default: // Do nothing
                break;
        }
        return $params;
    }
    public function add_lqip_theme_support()
    {
        add_theme_support('lqip');
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
        $quality = apply_filters('wib_lqip_placeholder_quality', 1);
        $filepath = get_attached_file($attachment_id);
        $pathinfo = pathinfo($filepath);
        if (preg_match('!^image/!', get_post_mime_type($attachment_id)) && file_is_displayable_image($filepath)) {
            foreach ($image_sizes as $size) {
                if (isset($metadata['sizes'][$size])) {
                    $width = isset($_wp_additional_image_sizes[$size]['width']) ? intval($_wp_additional_image_sizes[$size]['width']) : get_option("{$size}_size_w");
                    $height = isset($_wp_additional_image_sizes[$size]['height']) ? intval($_wp_additional_image_sizes[$size]['height']) : get_option("{$size}_size_h");
                    $crop = isset($_wp_additional_image_sizes[$size]['crop']) ? intval($_wp_additional_image_sizes[$size]['crop']) : get_option("{$size}_crop");
                    $new_size = $size . '-lqip';
                    $filename = str_replace('.' . $pathinfo['extension'], '-lqip.' . $pathinfo['extension'], $metadata['sizes'][$size]['file']);
                    $image = wp_get_image_editor($filepath);
                    $image->resize($width, $height, $crop);
                    $image->set_quality($quality);
                    $image->save($pathinfo['dirname'] . '/' . $filename);
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