<?php
/**
 * Horizontal Scroll extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class WCF_Horizontal_Scroll {

	public static function init() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_horizontal_scroll_controls'
		] );
	}

	public static function enqueue_scripts() {

	}

	public static function register_horizontal_scroll_controls( $element ) {

		$element->start_controls_section(
			'_section_wcf_horizontal_scroll_area',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text">%s<span>', __( 'Horizontal Scroll', 'animation-addons-for-elementor-pro' ), __( 'Pro', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'important_note',
			[
				'label'           => esc_html__( 'Important Note', 'animation-addons-for-elementor-pro' ),
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Please use full width Container to work properly.', 'animation-addons-for-elementor-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);

		$element->add_control(
			'wcf_enable_horizontal_scroll',
			[
				'label'              => esc_html__( 'Enable', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'horizontal_scroll_width',
			[
				'label'              => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'              => [
					'px' => [
						'min' => 100,
						'max' => 50000,
					],
					'%'  => [
						'min' => 10,
						'max' => 1000,
					],
				],
				'default'            => [
					'unit' => '%',
					'size' => 500,
				],
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [ 'wcf_enable_horizontal_scroll!' => '' ]
			]
		);

		$element->add_control(
			'horizontal_scroll_end',
			[
				'label'              => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min' => 100,
						'max' => 10000,
					],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [ 'wcf_enable_horizontal_scroll!' => '' ]
			]
		);

		$dropdown_options = [
			'' => esc_html__( 'All', 'animation-addons-for-elementor-pro' ),
		];

		foreach ( Plugin::$instance->breakpoints->get_active_breakpoints() as $breakpoint_key => $breakpoint_instance ) {

			$dropdown_options[ $breakpoint_key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value. */
				esc_html__( '%1$s (%2$dpx)', 'animation-addons-for-elementor-pro' ),
				$breakpoint_instance->get_label(),
				$breakpoint_instance->get_value()
			);
		}

		$element->add_control(
			'horizontal_scroll_breakpoint',
			[
				'label'              => esc_html__( 'Breakpoint', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => esc_html__( 'Note: Choose at which breakpoint horizontal scroll will work.', 'animation-addons-for-elementor-pro' ),
				'options'            => $dropdown_options,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => 'mobile',
				'condition'          => [
					'wcf_enable_horizontal_scroll!' => '',
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Horizontal_Scroll::init();
