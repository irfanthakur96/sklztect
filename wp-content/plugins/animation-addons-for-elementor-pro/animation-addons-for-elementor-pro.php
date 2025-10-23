<?php
/**
 * Plugin Name: Animation Addons for Elementor Pro
 * Description: Animation Addons for Elementor comes with GSAP Animation Builder, Customizable Widgets, Header Footer, Single Post, Archive Page Builder, and more.
 * Plugin URI:  https://animation-addons.com
 * Version:     2.3.5
 * Author:      WealCoder
 * Author URI:  https://animation-addons.com
 * Text Domain: animation-addons-for-elementor-pro
 * Elementor tested up to: 3.28.3
 * Elementor Pro tested up to: 3.28.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! defined( 'WCF_ADDONS_PRO_VERSION' ) ) {
	/**
	 * Plugin Version.
	 */
	define( 'WCF_ADDONS_PRO_VERSION', '2.3.5' );
	

if ( ! defined( 'WCF_ADDONS_PRO_STATUS_CACH_KEY' ) ) {
	define( 'WCF_ADDONS_PRO_STATUS_CACH_KEY', 'wcf_24anim357status' );
}

if ( ! defined( 'WCF_ADDONS_PRO_FILE' ) ) {
	/**
	 * Plugin File Ref.
	 */
	define( 'WCF_ADDONS_PRO_FILE', __FILE__ );
}
if ( ! defined( 'WCF_ADDONS_PRO_BASE' ) ) {
	/**
	 * Plugin Base Name.
	 */
	define( 'WCF_ADDONS_PRO_BASE', plugin_basename( WCF_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'WCF_ADDONS_PRO_PATH' ) ) {
	/**
	 * Plugin Dir Ref.
	 */
	define( 'WCF_ADDONS_PRO_PATH', plugin_dir_path( WCF_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'WCF_ADDONS_PRO_URL' ) ) {
	/**
	 * Plugin URL.
	 */
	define( 'WCF_ADDONS_PRO_URL', plugin_dir_url( WCF_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'WCF_ADDONS_PRO_WIDGETS_PATH' ) ) {
	/**
	 * Widgets Dir Ref.
	 */
	define( 'WCF_ADDONS_PRO_WIDGETS_PATH', WCF_ADDONS_PRO_PATH . 'widgets/' );
}

/**
 * Main AAE_ADDONS_Plugin_Pro Class
 *
 * The init class that runs the Hello World plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 *
 * @since 1.2.0
 */
final class AAE_ADDONS_Plugin_Pro {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '2.3.2';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.4';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Init Plugin
		add_action( 'plugins_loaded', array( $this , 'init' ), 11 );
		add_action( 'plugins_loaded', array( $this , 'text_domain_init' ), 11 );
		add_action( 'admin_enqueue_scripts' , [$this , 'enqueue_elementor_install_script' ]);
		
		add_action('wp_ajax_wcf_pro_install_elementor_plugin', [$this,'install_elementor_plugin_handler']);
		if(function_exists('wcf_theme_required_plugins')){
			add_filter('wcf_theme_required_plugins', [$this,'wcf_theme_required_plugins']);
		}
		add_action('upgrader_process_complete', [$this,'after_update_hook'], 10, 2);
	}
	
	function install_elementor_plugin_handler() {
		// Verify the AJAX nonce for security
		check_ajax_referer('wcfinstall_elementor_nonce', '_ajax_nonce');

		if ( !current_user_can( 'install_plugins' ) ) {
			wp_send_json_error(['message' => esc_html__('You have no access to install plugin','animation-addons-for-elementor-pro')]);
		}

		// Include required WordPress files
		if (!class_exists('Plugin_Upgrader')) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		if (!class_exists('WP_Ajax_Upgrader_Skin')) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
		}

		if (!function_exists('plugins_api')) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Include the plugins_api function
		}
	
		$plugin_slug = sanitize_text_field( $_REQUEST['name'] );
		$plugin_file = sanitize_text_field( $_REQUEST['slug'] );
	
		// Check if the plugin is already active
		if (is_plugin_active($plugin_file)) {
			wp_send_json_success(['message' => esc_html__('Plugin is already active.','animation-addons-for-elementor-pro')]);
		}
	
		// Fetch plugin information dynamically using the WordPress Plugin API
		$api = plugins_api('plugin_information', [
			'slug'   => $plugin_slug,
			'fields' => [
				'sections' => false,
			],
		]);
	
		if (is_wp_error($api)) {
			wp_send_json_error(['message' => esc_html__('Failed to retrieve plugin information.','animation-addons-for-elementor-pro')]);
		}
	
		// Get the download URL for the plugin
		$download_url = $api->download_link;
	
		if (empty($download_url)) {
			wp_send_json_error(['message' => esc_html__('Failed to retrieve plugin download URL.','animation-addons-for-elementor-pro')]);
		}
	
		// Install the plugin using the retrieved download URL
		$upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
		$installed = $upgrader->install($download_url);
	
		if (is_wp_error($installed)) {
			error_log('Plugin installation error: ' . $installed->get_error_message());
			wp_send_json_error(['message' => $installed->get_error_message()]);
		}
	
		// Activate the plugin if installed successfully
		if (file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)) {
			$activated = activate_plugin($plugin_file);
	
			if (is_wp_error($activated)) {
				error_log('Plugin activation error: ' . $activated->get_error_message());
				wp_send_json_error(['message' => $activated->get_error_message()]);
			}
	
			wp_send_json_success(['message' => esc_html__('Elementor has been successfully installed and activated.','animation-addons-for-elementor-pro')]);
		}
	
		// If the plugin file is not found, send an error
		wp_send_json_error(['message' => esc_html__('Plugin installation failed.','animation-addons-for-elementor-pro')]);
	}

	function after_update_hook($upgrader_object, $options) {
		// Check if it's a plugin being updated
		if ($options['type'] === 'plugin' && $options['action'] === 'update') {
			// Path to your plugin's main file
			$my_plugin = plugin_basename(__FILE__);
	
			foreach ($options['plugins'] as $plugin) {
				if ($plugin === $my_plugin) {
					// Do your stuff here after plugin update
					set_transient('aae_addon_pro_plugin_update_notice', true, 30);					
				}
			}
		}
	}

	public function text_domain_init() {
		load_plugin_textdomain( 'animation-addons-for-elementor-pro' );
		load_plugin_textdomain( 'animation-addons-for-elementor-pro' , false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	public function init() {

		if (get_transient('aae_addon_pro_plugin_update_notice')) {
			?>
			<div class="notice notice-success is-dismissible">
				<h2><?php _e('Animation Addons For Elementor Pro was successfully updated!', 'animation-addons-for-elementor-pro'); ?></h2>
				<p><?php _e('Please check you Animation addon dashboard widget and extension settings ', 'animation-addons-for-elementor-pro'); ?></p>
			</div>
			<?php
			delete_transient('aae_addon_pro_plugin_update_notice');
		}
	
		// Check if Animation Addon for Elementor installed and activated
		if ( ! class_exists( 'WCF_ADDONS_Plugin' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_wcf_addons_plugin' ) );
			return;
		}

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}		
		require_once 'class-plugin.php';
		do_action( 'wcf_plugins_pro_loaded' );
	}
	
	function enqueue_elementor_install_script() {

		if ( !current_user_can( 'install_plugins' ) ) {
			return;
		}		
		// Check if the plugin is not active
		if ( !is_plugin_active('elementor/elementor.php') || !is_plugin_active('animation-addons-for-elementor/animation-addons-for-elementor.php')) {
		
			wp_enqueue_script(
				'wcf-pro-install-elementor-script',
				plugin_dir_url(__FILE__) . 'assets/js/install-elementor.js', // Replace with your JS file path
				['jquery'], // Dependencies
				'1.0', // Version
				true // Load in footer
			);	
			// Localize script to pass AJAX data
			wp_localize_script('wcf-pro-install-elementor-script', 'wcfelementorAjax', [
				'ajax_url'    => admin_url('admin-ajax.php'),
				'nonce'       => wp_create_nonce('wcfinstall_elementor_nonce'),
			]);			
		}
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( !current_user_can( 'install_plugins' ) ) {
			return;
		}
		
		if(is_plugin_active('animation-addons-for-elementor/animation-addons-for-elementor.php')){
			return;
		}
		
		if ( !is_plugin_active('elementor/elementor.php') ) {
			echo '<div class="notice notice-error" id="elementor-install-notice">';
			echo '<p><strong>Animation Addons Pro for Elementor</strong> requires Elementor plugin to be installed and activated.</p>';
			echo '<p><button name="elementor" slug="elementor/elementor.php" id="wcf-install-pro-elementor" class="button button-primary">Install and Activate Elementor</button></p>';
			echo '</div>';
		}
		
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Animation Addon for Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_wcf_addons_plugin() {
		
		if ( !current_user_can( 'install_plugins' ) ) {
			return;
		}
		
        ?>
        <style>
            #elementor-install-notice{
				background-image: linear-gradient(45deg, #FF7A00 0%, #FFD439 100%);
				color: #fff;
				padding: 15px;
				display: flex;
                flex-direction: column;
                gap: 3px;
            }
            #elementor-install-notice span{
                font-size: 18px;
                font-weight: 600;
            }
            #wcf-install-pro-elementor{
				background: #1b1b1b;
				border: 0;
            }
        </style>
        <?php
		if ( !is_plugin_active('animation-addons-for-elementor/animation-addons-for-elementor.php') ) {
			echo '<div class="notice notice-error" id="elementor-install-notice">';
			echo '<p><strong>Animation Addons Pro for Elementor</strong> requires <span>animation addons for elementor</span> plugin to be installed and activated.</p>';
			echo '<p><button name="animation-addons-for-elementor" slug="animation-addons-for-elementor/animation-addons-for-elementor.php" id="wcf-install-pro-elementor" class="button button-primary">Activate</button></p>';
			echo '</div>';
		}
	}

} // end class
	
	// Instantiate AAE_ADDONS_Plugin_Pro.
	new AAE_ADDONS_Plugin_Pro();

}
if(!function_exists('aae_anim_addon_pro_flush_rewrite_rules')){
	
	function aae_anim_addon_pro_flush_rewrite_rules() {		
		if(class_exists('\WCF_ADDONS\Extensions\Animation_Builder')){
			\WCF_ADDONS\Extensions\Animation_Builder::instance()->custom_rewrite_rules();
			flush_rewrite_rules();
		}	
		if (!wp_next_scheduled('AaeAddon_Single_Error_Check')) {
			wp_schedule_event(time(), 'aae_addon2025_error_check', 'AaeAddon_Single_Error_Check');
		}	
		if ( function_exists('deactivate_plugins') ) {
	        deactivate_plugins('extension-for-animation-addons/extension-for-animation-addons.php');
	        deactivate_plugins('wcf-addons-pro/wcf-addons-pro.php');
	        if ( function_exists('is_plugin_active') && is_plugin_active('extension-for-animation-addons/extension-for-animation-addons.php') ) {
				deactivate_plugins('extension-for-animation-addons/extension-for-animation-addons.php');			
			}
	    }	 
	}
}

add_filter('cron_schedules', function($schedules) {
	$time = 12 * 60 * 60;
    $schedules['aae_addon2025_error_check'] = [
        'interval' => $time, 
        'display'  => sprintf( __('Every %s Minutes','animation-addons-for-elementor-pro'), $time ),
    ];
    return $schedules;
});

register_activation_hook(__FILE__, 'aae_anim_addon_pro_flush_rewrite_rules');
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
register_deactivation_hook(__FILE__, 'wcf_clear_single_error_schedule');

function wcf_clear_single_error_schedule() {
    wp_clear_scheduled_hook('AaeAddon_Single_Error_Check');
}

function aae_addon_pro_sche_maybe_update() {

    $stored_version = get_option('wcf_addon_version');    
    if ($stored_version !== WCF_ADDONS_PRO_VERSION) {  
		wp_clear_scheduled_hook('AaeAddon_Single_Error_Check');
		if (!wp_next_scheduled('AaeAddon_Single_Error_Check')) {
			wp_schedule_event(time(), 'aae_addon2025_error_check', 'AaeAddon_Single_Error_Check');
		}
       
        update_option('wcf_addon_version', WCF_ADDONS_PRO_VERSION);
    }
}
add_action('plugins_loaded', 'aae_addon_pro_sche_maybe_update');


