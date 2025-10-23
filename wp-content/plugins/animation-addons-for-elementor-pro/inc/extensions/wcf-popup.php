<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class WCF_Popup {

	public static function init() {
		//popup controls
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_popup_controls'
		] );
	}

	public static function register_popup_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_popup_area',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text">%s<span>', __( 'Popup', 'animation-addons-for-elementor-pro' ), __( 'Pro', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'wcf_enable_popup',
			[
				'label'              => esc_html__( 'Enable Popup', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_enable_popup_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [ 'wcf_enable_popup!' => '' ]
			]
		);

		$element->add_control(
			'popup_content_type',
			[
				'label'     => esc_html__( 'Content Type', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'content'  => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
					'template' => esc_html__( 'Saved Templates', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => 'content',
				'condition' => [ 'wcf_enable_popup!' => '' ]
			]
		);

		$element->add_control(
			'popup_elementor_templates',
			[
				'label'       => esc_html__( 'Save Template', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => false,
				'multiple'    => false,
				'options'     => wcf_addons_get_saved_template_list(),
				'condition'   => [
					'popup_content_type' => 'template',
					'wcf_enable_popup!'  => '',
				],
			]
		);

		$element->add_control(
			'popup_content',
			[
				'label'     => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'default'   => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::WYSIWYG,
				'condition' => [
					'popup_content_type' => 'content',
					'wcf_enable_popup!'  => '',
				],
			]
		);

		$element->add_control(
			'popup_trigger_cursor',
			[
				'label'     => esc_html__( 'Cursor', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default'  => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'none'     => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'pointer'  => esc_html__( 'Pointer', 'animation-addons-for-elementor-pro' ),
					'grabbing' => esc_html__( 'Grabbing', 'animation-addons-for-elementor-pro' ),
					'move'     => esc_html__( 'Move', 'animation-addons-for-elementor-pro' ),
					'text'     => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}}' => 'cursor: {{VALUE}};',
				],
				'condition' => [ 'wcf_enable_popup!' => '' ],
			]
		);

		$element->add_control(
			'popup_animation',
			[
				'label'              => esc_html__( 'Animation', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'default'            => 'default',
				'options'            => [
					'default'             => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'mfp-zoom-in'         => esc_html__( 'Zoom', 'animation-addons-for-elementor-pro' ),
					'mfp-zoom-out'        => esc_html__( 'Zoom-out', 'animation-addons-for-elementor-pro' ),
					'mfp-newspaper'       => esc_html__( 'Newspaper', 'animation-addons-for-elementor-pro' ),
					'mfp-move-horizontal' => esc_html__( 'Horizontal move', 'animation-addons-for-elementor-pro' ),
					'mfp-move-from-top'   => esc_html__( 'Move from top', 'animation-addons-for-elementor-pro' ),
					'mfp-3d-unfold'       => esc_html__( '3d unfold', 'animation-addons-for-elementor-pro' ),
				],
				'condition'          => [ 'wcf_enable_popup!' => '' ],
			]
		);

		$element->add_control(
			'popup_animation_delay',
			[
				'label'              => esc_html__( 'Removal Delay', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'frontend_available' => true,
				'default'            => 500,
				'condition'          => [ 'wcf_enable_popup!' => '' ],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Popup::init();
