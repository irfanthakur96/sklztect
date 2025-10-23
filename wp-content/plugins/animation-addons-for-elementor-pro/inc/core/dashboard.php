<?php

namespace WCFAddonsPro\core;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

class AAE_Admin_Init {

	/**
	 * [$_instance]
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * [instance] Initializes a singleton instance
	 * @return [_Admin_Init]
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->init();
	}

	/**
	 * [init] Assets Initializes
	 * @return [void]
	 */
	public function init() {
		add_action( 'wp_ajax_save_settings_dashboard_library_ajax', [ $this, 'save_settings_dashboard' ] );	
		add_action( 'wp_enqueue_scripts',[ $this,'gsap_library_push'],7 );
		add_filter( 'plugin_row_meta', [ $this, '_plugin_row_meta' ], 10, 2 ); 
	}
	public function gsap_library_push() {
		$opt = get_option('wcf_save_gsap_library');
		
		if (
			$opt &&
			is_array($opt) &&
			isset($opt['elements']['gsap-library']['elements']) &&
			is_array($opt['elements']['gsap-library']['elements'])
		) {
			$lib = $opt['elements']['gsap-library']['elements'];
			
			foreach ($lib as $handler => $item) {
				if (!empty($item['is_active']) && $item['is_active'] == 1) {
					$src  = isset($item['src']) ? esc_url($item['src']) : '';
					$deps = isset($item['deps']) ? $item['deps'] : [];
					if ($src)
					{
						wp_register_script(
							$handler,
							$src,
							$deps, 
							WCF_ADDONS_VERSION,
							true
						);	
					}
					if ($src)
					{					
						wp_enqueue_script(
							$handler							
						);
					}
				}
			}
		}
	}

	public function save_settings_dashboard() {		
		
		check_ajax_referer( 'wcf_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'you are not allowed to do this action', 'animation-addons-for-elementor-pro' ) );
		}

		if ( ! isset( $_POST['fields'] ) ) {
			return;
		}
		
		$actives       = [];
		$option_name   = 'wcf_save_gsap_library';
		$sanitize_data = sanitize_text_field( wp_unslash($_POST['fields']) );
		$settings      = json_decode( $sanitize_data , true );
		$updated = update_option( $option_name, $settings );		
	
		$return_message = [
				'status'  => $updated,
				'message' => esc_html__( 'Settings saved successfully!', 'animation-addons-for-elementor-pro' ),
			];	
		wp_send_json( $return_message );
	
	}

		
    function _plugin_row_meta( $meta, $plugin_file ) {

        if ( basename(WCF_ADDONS_PRO_BASE) !== basename($plugin_file) ) {
			return $meta;
		} 

		if(!function_exists('wcf_addon_pro_check_license')){
			return $meta;
		}
		
		$data = wcf_addon_pro_check_license(true);		
		if(!$data){
			return $meta;
		}

		if ( is_wp_error( $data ) ) {
			return;
		}

		if(isset($data->expires)){
			
			if($data->expires == 'lifetime'){ return $meta; }  

			$current_date = new \DateTime();
			$expiration   = new \DateTime($data->expires);
			$interval     = $current_date->diff($expiration);

			if ($current_date < $expiration) {
				if($interval->days > 30){
					return $meta;
				}			
			}

			if(isset($data->renew_url)){
				$meta[] = '<a target="_blank" style="color:#FF7A00;font-weight: 600;" href="'.$data->renew_url.'" target="_blank">' . esc_html__('Renew License', 'animation-addons-for-elementor-pro' ) . '</a>';
			}

		}	
		
        return $meta;
    }

}

AAE_Admin_Init::instance();
