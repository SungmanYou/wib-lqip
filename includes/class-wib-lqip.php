<?php
/**
 * @link          https://github.com/SungmanYou
 * @since         1.0.0
 * @package       Wib_Lqip
 * @subpackage    Wib_Lqip/includes
 * @author        Sungman You <sungman.you@gmail.com>
 */
class Wib_Lqip
{
    protected $loader;
    protected $plugin_name;
    protected $version;
    public function __construct()
    {
        $this->version = defined('WIB_LQIP_VERSION') ? WIB_LQIP_VERSION : '1.0.0';
        $this->plugin_name = 'wib-lqip';
        $this->define_hooks();
    }
    private function define_hooks()
    {
        add_action('after_setup_theme', [$this, 'add_image_sizes']);
        add_filter('wp_handle_upload', [$this, 'check_image_type']);
        add_filter('wp_generate_attachment_metadata', [$this, 'generate_attachment_metadata'], 10, 2);
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
            $quality = apply_filters('wib_lqip_png_to_jpg_quality', 100);
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

    public function add_image_sizes()
    {
        add_image_size('lqip', 50, 0, false);
        add_image_size('lqip_square', 50, 50, true);
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

    public function generate_attachment_metadata($metadata, $attachment_id)
    {
        global $_wp_theme_features;
        global $_wp_additional_image_sizes;
        // $image_sizes = apply_filters('intermediate_image_sizes_advanced', get_intermediate_image_sizes());
        $image_sizes = ['lqip', 'lqip_square'];
        $quality = apply_filters('wib_lqip_placeholder_quality', 25);
        $filepath = get_attached_file($attachment_id);
        $pathinfo = pathinfo($filepath);
        if (preg_match('!^image/!', get_post_mime_type($attachment_id)) && file_is_displayable_image($filepath)) {
            foreach ($image_sizes as $size) {
                if (isset($metadata['sizes'][$size])) {
                    $width = isset($_wp_additional_image_sizes[$size]['width']) ? intval($_wp_additional_image_sizes[$size]['width']) : get_option("{$size}_size_w");
                    $height = isset($_wp_additional_image_sizes[$size]['height']) ? intval($_wp_additional_image_sizes[$size]['height']) : get_option("{$size}_size_h");
                    $crop = isset($_wp_additional_image_sizes[$size]['crop']) ? intval($_wp_additional_image_sizes[$size]['crop']) : get_option("{$size}_crop");
                    $filename = $metadata['sizes'][$size]['file'];
                    $image = wp_get_image_editor($filepath);
                    $image->resize($width, $height, $crop);
                    $image->set_quality($quality);
                    $image->save($pathinfo['dirname'] . '/' . $filename);
                }
            }
        }
        return $metadata;
    }
}