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
        $this->load_dependencies();
        $this->define_admin_hooks();
    }
    private function load_dependencies()
    {
        $plugin_path = plugin_dir_path(dirname(__FILE__));
        $dependencies = ['includes/class-wib-lqip-loader.php', 'admin/class-wib-lqip-admin.php'];
        foreach ($dependencies as $dependency) { require_once $plugin_path . $dependency; }
        $this->loader = new Wib_Lqip_Loader();
    }
    private function define_admin_hooks()
    {
        $plugin_admin = new Wib_Lqip_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('after_setup_theme', $plugin_admin, 'add_lqip_theme_support');
        $this->loader->add_filter('wp_handle_upload', $plugin_admin, 'check_image_type');
        $this->loader->add_filter('wp_generate_attachment_metadata', $plugin_admin, 'generate_attachment_metadata', 10, 2);
    }
    public function run() {$this->loader->run();}
    
    public function get_plugin_name() {return $this->plugin_name;}
    public function get_loader() {return $this->loader;}
    public function get_version() {return $this->version;}

}
