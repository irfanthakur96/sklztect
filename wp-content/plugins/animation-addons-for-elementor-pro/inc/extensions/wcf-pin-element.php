<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Controls_Manager;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class WCF_Pin_Effects {

	public static function init() {
		//ping area controls
		add_action( 'elementor/element/section/section_advanced/after_section_end', [
			__CLASS__,
			'register_ping_area_controls'
		] );

		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_ping_area_controls'
		] );
	}

	public static function register_ping_area_controls( $element ) {
		$element->start_controls_section(
			'_section_pin-area',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text">%s<span>', __( 'Pin Element', 'animation-addons-for-elementor-pro' ), __( 'Pro', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf_enable_pin_area',
			[
				'label'              => esc_html__( 'Enable Pin', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'render_type'        => 'none',
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_pin_alert',
			[
				'label'           => esc_html__( 'Important Note', 'animation-addons-for-elementor-pro' ),
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Please use full width Container to work properly and see the result in view mode.', 'animation-addons-for-elementor-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => [ 'wcf_enable_pin_area!' => '' ],
				'render_type'     => 'none',
			]
		);

		$element->add_control(
			'wcf_pin_area_trigger',
			[
				'label'       => esc_html__( 'Pin Wrapper', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''       => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'custom' => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				],
				'condition'   => [ 'wcf_enable_pin_area!' => '' ],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wcf_custom_pin_area',
			[
				'label'              => esc_html__( 'Custom Pin Area', 'animation-addons-for-elementor-pro' ),
				'description'        => esc_html__( 'Add the section class where the element will be pin. please use the parent section or container class.', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__( '.pin_area', 'animation-addons-for-elementor-pro' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_pin_area_trigger' => 'custom',
					'wcf_enable_pin_area!' => '',
				]
			]
		);

		$element->add_control(
			'wcf_pin_end_trigger',
			[
				'label'              => esc_html__( 'End Trigger', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__( '.end_trigger', 'animation-addons-for-elementor-pro' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
				],
				'separator'          => 'after',
			]
		);

		$element->add_control(
			'wcf_pin_status',
			[
				'label'     => esc_html__( 'Pin', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'true',
				'options'   => [
					'true'   => esc_html__( 'True', 'animation-addons-for-elementor-pro' ),
					'false'  => esc_html__( 'False', 'animation-addons-for-elementor-pro' ),
					'custom' => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_pin_custom',
			[
				'label'       => esc_html__( 'Custom Pin', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type' => 'none',
				'placeholder' => esc_html__( '.pin_class', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'wcf_pin_status' => 'custom',
					'wcf_enable_pin_area!' => '', 
				]
			]
		);

		$element->add_control(
			'wcf_pin_spacing',
			[
				'label'     => esc_html__( 'Pin Spacing', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'false',
				'options'   => [
					'true'   => esc_html__( 'True', 'animation-addons-for-elementor-pro' ),
					'false'  => esc_html__( 'False', 'animation-addons-for-elementor-pro' ),
					'custom' => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_pin_spacing_custom',
			[
				'label'       => esc_html__( 'Custom Pin Spacing', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type' => 'none',
				'placeholder' => esc_html__( '.custom-class', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'wcf_pin_spacing' => 'custom', 
					'wcf_enable_pin_area!' => '',
				]
			]
		);

		$element->add_control(
			'wcf_pin_type',
			[
				'label'     => esc_html__( 'Pin Type', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'transform',
				'options'   => [
					'fixed'   => esc_html__( 'Fixed', 'animation-addons-for-elementor-pro' ),
					'transform'  => esc_html__( 'Transform', 'animation-addons-for-elementor-pro' ),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_pin_scrub',
			[
				'label'     => esc_html__( 'Pin Scrub', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'false',
				'options'   => [
					'true'   => esc_html__( 'True', 'animation-addons-for-elementor-pro' ),
					'false'  => esc_html__( 'False', 'animation-addons-for-elementor-pro' ),
					'number'  => esc_html__( 'Number', 'animation-addons-for-elementor-pro' ),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_pin_scrub_number',
			[
				'label' => esc_html__( 'Scrub Number', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'default' => 1,
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'   => [ 
					'wcf_pin_scrub' => 'number',
					'wcf_enable_pin_area!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_pin_markers',
			[
				'label'     => esc_html__( 'Pin Markers', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'false',
				'options'   => [
					'true'   => esc_html__( 'True', 'animation-addons-for-elementor-pro' ),
					'false'  => esc_html__( 'False', 'animation-addons-for-elementor-pro' ),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_pin_area_start',
			[
				'label'              => esc_html__( 'Start', 'animation-addons-for-elementor-pro' ),
				'description'        => esc_html__( 'First value is element position, Second value is display position', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'default'            => 'top top',
				'frontend_available' => true,
				'options'            => [
					'top top'       => esc_html__( 'Top Top', 'animation-addons-for-elementor-pro' ),
					'top center'    => esc_html__( 'Top Center', 'animation-addons-for-elementor-pro' ),
					'top bottom'    => esc_html__( 'Top Bottom', 'animation-addons-for-elementor-pro' ),
					'center top'    => esc_html__( 'Center Top', 'animation-addons-for-elementor-pro' ),
					'center center' => esc_html__( 'Center Center', 'animation-addons-for-elementor-pro' ),
					'center bottom' => esc_html__( 'Center Bottom', 'animation-addons-for-elementor-pro' ),
					'bottom top'    => esc_html__( 'Bottom Top', 'animation-addons-for-elementor-pro' ),
					'bottom center' => esc_html__( 'Bottom Center', 'animation-addons-for-elementor-pro' ),
					'bottom bottom' => esc_html__( 'Bottom Bottom', 'animation-addons-for-elementor-pro' ),
					'custom'        => esc_html__( 'custom', 'animation-addons-for-elementor-pro' ),
				],
				'render_type'        => 'none',
				'condition'          => [ 'wcf_enable_pin_area!' => '' ],

			]
		);

		$element->add_control(
			'wcf_pin_area_start_custom',
			[
				'label'              => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__( 'top top', 'animation-addons-for-elementor-pro' ),
				'placeholder'        => esc_html__( 'top top+=100', 'animation-addons-for-elementor-pro' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
					'wcf_pin_area_start'   => 'custom',
				],
			]
		);

		$element->add_control(
			'wcf_pin_area_end',
			[
				'label'              => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
				'description'        => esc_html__( 'First value is element position, Second value is display position', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'default'            => 'bottom top',
				'frontend_available' => true,
				'render_type'        => 'none',
				'options'            => [
					'top top'       => esc_html__( 'Top Top', 'animation-addons-for-elementor-pro' ),
					'top center'    => esc_html__( 'Top Center', 'animation-addons-for-elementor-pro' ),
					'top bottom'    => esc_html__( 'Top Bottom', 'animation-addons-for-elementor-pro' ),
					'center top'    => esc_html__( 'Center Top', 'animation-addons-for-elementor-pro' ),
					'center center' => esc_html__( 'Center Center', 'animation-addons-for-elementor-pro' ),
					'center bottom' => esc_html__( 'Center Bottom', 'animation-addons-for-elementor-pro' ),
					'bottom top'    => esc_html__( 'Bottom Top', 'animation-addons-for-elementor-pro' ),
					'bottom center' => esc_html__( 'Bottom Center', 'animation-addons-for-elementor-pro' ),
					'bottom bottom' => esc_html__( 'Bottom Bottom', 'animation-addons-for-elementor-pro' ),
					'custom'        => esc_html__( 'custom', 'animation-addons-for-elementor-pro' ),
				],
				'condition'          => [ 'wcf_enable_pin_area!' => '' ],
			]
		);

		$element->add_control(
			'wcf_pin_area_end_custom',
			[
				'label'              => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => esc_html__( 'bottom top', 'animation-addons-for-elementor-pro' ),
				'placeholder'        => esc_html__( 'bottom top+=100', 'animation-addons-for-elementor-pro' ),
				'condition'          => [
					'wcf_enable_pin_area!' => '',
					'wcf_pin_area_end'     => 'custom',
				],
			]
		);

		$dropdown_options = [
			'' => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
		];

		$excluded_breakpoints = [
			'laptop',
			'tablet_extra',
			'widescreen',
		];

		foreach ( Plugin::$instance->breakpoints->get_active_breakpoints() as $breakpoint_key => $breakpoint_instance ) {
			// Exclude the larger breakpoints from the dropdown selector.
			if ( in_array( $breakpoint_key, $excluded_breakpoints, true ) ) {
				continue;
			}

			$dropdown_options[ $breakpoint_key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value. */
				esc_html__( '%1$s (%2$s %3$dpx)', 'animation-addons-for-elementor-pro' ),
				$breakpoint_instance->get_label(),
				'>',
				$breakpoint_instance->get_value()
			);
		}

		$element->add_control(
			'wcf_pin_breakpoint',
			[
				'label'              => esc_html__( 'Breakpoint', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'description'        => esc_html__( 'Note: Choose at which breakpoint Pin element will work.', 'animation-addons-for-elementor-pro' ),
				'options'            => $dropdown_options,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => 'mobile',
				'condition'          => [ 'wcf_enable_pin_area!' => '' ],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Pin_Effects::init();
