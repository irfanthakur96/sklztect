<?php

namespace WCFAddonsPro;

use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class Ajax_Handler {

	public static function init() {
		add_action( 'wp_ajax_mailchimp_api', [ __CLASS__, 'mailchimp_lists' ] );
		add_action( 'wp_ajax_nopriv_mailchimp_api', [ __CLASS__, 'mailchimp_lists' ] );

		add_action( 'wp_ajax_wcf_mailchimp_ajax', [ __CLASS__, 'mailchimp_prepare_ajax' ] );
		add_action( 'wp_ajax_nopriv_wcf_mailchimp_ajax', [ __CLASS__, 'mailchimp_prepare_ajax' ] );
		
		add_action( 'wp_ajax_wcf_mailchimp_list_fields', [ __CLASS__, 'wcf_mailchimp_list_fields' ] );
		add_action( 'wp_ajax_nopriv_wcf_mailchimp_list_fields', [ __CLASS__, 'wcf_mailchimp_list_fields' ] );

		//popup action
		add_action( 'wp_ajax_wcf_load_popup_content', [ __CLASS__, 'wcf__popup_content' ] );
		add_action( 'wp_ajax_nopriv_wcf_load_popup_content', [ __CLASS__, 'wcf__popup_content' ] );
	}

	/**
	 * Mailchimp subscriber all list handler Ajax call
	 */
	public static function mailchimp_lists() {
	
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wcf-addons-editor' ) ) {
			exit( 'No naughty business please' );
		}

		$api = ! empty( $_REQUEST['api'] ) ? $_REQUEST['api'] : '';			
		update_option('aae_mailchimp_api', $api);
		$response = Widgets\Mailchimp\Mailchimp_Api::get_mailchimp_lists( $api );

		wp_send_json( $response );
	}
	
	public static function wcf_mailchimp_list_fields() {
	
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wcf-addons-editor' ) ) {
			exit( 'No naughty business please' );
		}

		$api = ! empty( $_REQUEST['api'] ) ? $_REQUEST['api'] : '';	
		$list_id = ! empty( $_REQUEST['list_id'] ) ? $_REQUEST['list_id'] : '';	

		$response = Widgets\Mailchimp\Mailchimp_Api::get_form_fields( $api, $list_id );

		wp_send_json( $response );
	}

	/**
	 * Mailchimp subscriber handler Ajax call
	 */
	public static function mailchimp_prepare_ajax() {
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wcf-addons-frontend' ) ) {
			exit( 'No naughty business please' );
		}

		parse_str( isset( $_POST['subscriber_info'] ) ? $_POST['subscriber_info'] : '', $subscriber );
	
		$response = Widgets\Mailchimp\Mailchimp_Api::insert_subscriber_to_mailchimp( $subscriber );

		wp_send_json( $response );
	}

	/**
	 * wcf popup content Ajax call
	 */
	public static function wcf__popup_content() {
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wcf-addons-frontend' ) ) {
			exit( 'No naughty business please' );
		}

		$post_id  = $_REQUEST["post_id"];
		$element_id  = $_REQUEST["element_id"];
		$settings = wcf_addons_get_widget_settings($post_id, $element_id);

		ob_start();
		if ( 'template' === $settings['popup_content_type'] ){
			echo Plugin::$instance->frontend->get_builder_content( $settings['popup_elementor_templates'] );
        } else{
		
			$content = $settings['popup_content'];
			$content = shortcode_unautop( $content );
			$content = do_shortcode( $content );
			$content = wptexturize( $content );

			if ( $GLOBALS['wp_embed'] instanceof \WP_Embed ) {
				$content = $GLOBALS['wp_embed']->autoembed( $content );
			}

		    echo $content;
        }
		$html = ob_get_contents();
		ob_end_clean();

		wp_send_json(
			array(
				'html'      => $html,
				'widget_attr' => 'test attr',
			)
		);

		die();
	}
}

Ajax_Handler::init();
