<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class WCF_Text_Animation_Effects {

	private static function get_text_elements() {
		return [
			[ 'name' => 'heading', 'section' => 'section_title' ],
			[ 'name' => 'text-editor', 'section' => 'section_editor' ],
			[ 'name' => 'wcf--title', 'section' => 'section_content' ],
			[ 'name' => 'wcf--text', 'section' => 'section_content' ],
		];
	}
	public static function init() {
		
		foreach ( self::get_text_elements() as $element ) {
			add_action( 'elementor/element/' . $element['name'] . '/' . $element['section'] . '/after_section_end', [
				__CLASS__,
				'register_text_animation_controls',
			], 10, 2 );		
		}
		// fix optimize markup issue
		add_filter('elementor/widget/render_content', [__CLASS__,
		'render_content'],10,2);
	}

	public static function render_content($content, $element){
		
		if ( $element->get_type() === 'widget' && in_array( $element->get_name(), array_column( self::get_text_elements(), 'name' ) ) ) {			
			if(Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' )){
				$content =  '<div class="elementor-widget-container">'.$content . '</div>';
			}
		}
		return $content;
	}
	
	public static function register_text_animation_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_text_animation',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text">%s<span>', __( 'Text Animation', 'animation-addons-for-elementor-pro' ), __( 'Pro', 'animation-addons-for-elementor-pro' ) ),
			]
		);

		$animation = [
			'none'        => esc_html__( 'none', 'animation-addons-for-elementor-pro' ),
			'char'        => esc_html__( 'Character', 'animation-addons-for-elementor-pro' ),
			'word'        => esc_html__( 'Word', 'animation-addons-for-elementor-pro' ),
			'text_move'   => esc_html__( 'Text Move', 'animation-addons-for-elementor-pro' ),
			'text_reveal' => esc_html__( 'Text Reveal', 'animation-addons-for-elementor-pro' ),
		];

		if ( in_array( $element->get_name(), [ 'heading', 'wcf--title' ] ) ) {
			$animation['text_invert'] = esc_html__( 'Text Invert', 'animation-addons-for-elementor-pro' );
			$animation['text_spin']   = esc_html__( '3D Spin', 'animation-addons-for-elementor-pro' );
		}

		$element->add_control(
			'wcf_text_animation',
			[
				'label'              => esc_html__( 'Animation', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'separator'          => 'before',
				'options'            => $animation,
				'render_type'        => 'none',
				'prefix_class'       => 'wcf-t-animation-',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf_text_animation_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'animation-addons-for-elementor-pro' ),
				'description'        => esc_html__( 'For better performance in editor mode, keep the setting turned off.', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf_text_animation!' => 'none',
				],
			]
		);

		$element->add_control(
			'play_image_animation',
			[
				'label' => esc_html__( 'Play Animation', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::BUTTON,
				'separator' => 'before',
				'button_type' => 'success',
				'text' => esc_html__( 'Play', 'animation-addons-for-elementor-pro' ),
				'event' => 'wcf:editor:play_animation',
				'condition'          => [
					'wcf_text_animation!' => 'none',
					'wcf_text_animation_editor' => 'yes'
				],
			]
		);

		$element->add_control(
			'text_delay',
			[
				'label'              => esc_html__( 'Delay', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.1,
				'default'            => 0.15,
				'condition'          => [
					'wcf_text_animation' => [ 'char', 'word', 'text_reveal', 'text_move', 'text_spin' ],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'text_duration',
			[
				'label'              => esc_html__( 'Duration', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.1,
				'default'            => 1,
				'condition'          => [
					'wcf_text_animation' => [ 'char', 'word', 'text_reveal', 'text_move' ],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'text_stagger',
			[
				'label'              => esc_html__( 'Stagger', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.01,
				'default'            => 0.02,
				'condition'          => [
					'wcf_text_animation' => [ 'char', 'word', 'text_reveal', 'text_move' ],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'text_on_scroll',
			[
				'label'              => esc_html__( 'Animation on scroll', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
				'label_off'          => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'condition'          => [
					'wcf_text_animation' => [ 'char', 'word', 'text_reveal', 'text_move', 'text_spin' ],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'text_translate_x',
			[
				'label'              => esc_html__( 'Transform-X', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 20,
				'condition'          => [
					'wcf_text_animation' => [ 'char', 'word' ],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'text_translate_y',
			[
				'label'              => esc_html__( 'Transform-Y', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0,
				'condition'          => [
					'wcf_text_animation' => [ 'char', 'word' ],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'text_rotation_di',
			[
				'label'              => esc_html__( 'Rotation Direction', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'x',
				'separator'          => 'before',
				'options'            => [
					'x' => esc_html__( 'X', 'animation-addons-for-elementor-pro' ),
					'y' => esc_html__( 'Y', 'animation-addons-for-elementor-pro' ),
				],
				'condition'          => [
					'wcf_text_animation' => [ 'text_move' ],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'text_rotation',
			[
				'label'              => esc_html__( 'Rotation Value', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => '-80',
				'condition'          => [
					'wcf_text_animation' => [ 'text_move' ],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'text_transform_origin',
			[
				'label'              => esc_html__( 'transformOrigin', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => esc_html__( 'top center -50', 'animation-addons-for-elementor-pro' ),
				'placeholder'        => esc_html__( 'top center', 'animation-addons-for-elementor-pro' ),
				'condition'          => [
					'wcf_text_animation' => [ 'text_move' ],
				],
				'render_type'        => 'none',
			]
		);

		//3d spin
		$element->add_control(
			'spin_text_color',
			[
				'label'     => esc_html__( 'Spin Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .duplicate-text' => 'color: {{VALUE}} !important',
				],
				'condition'          => [
					'wcf_text_animation' => [ 'text_spin' ],
				],
			]
		);

		$element->add_control(
			'spin_text_start',
			[
				'label'              => esc_html__( 'Start', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => esc_html__( 'top 50%', 'animation-addons-for-elementor-pro' ),
				'placeholder'        => esc_html__( 'top center', 'animation-addons-for-elementor-pro' ),
				'condition'          => [
					'wcf_text_animation' => [ 'text_spin' ],
					'text_on_scroll' => 'yes',
				],
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'spin_text_end',
			[
				'label'              => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => esc_html__( 'bottom 30%', 'animation-addons-for-elementor-pro' ),
				'placeholder'        => esc_html__( 'bottom 30%', 'animation-addons-for-elementor-pro' ),
				'condition'          => [
					'wcf_text_animation' => [ 'text_spin' ],
					'text_on_scroll' => 'yes',
				],
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'spin_text_scrub',
			[
				'label'              => esc_html__( 'Scrub', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf_text_animation' => [ 'text_spin' ],
					'text_on_scroll' => 'yes',
				],
			]
		);

		$element->add_control(
			'spin_text_toggle_action',
			[
				'label'              => esc_html__( 'toggleActions', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => esc_html__( 'play none none reverse', 'animation-addons-for-elementor-pro' ),
				'placeholder'        => esc_html__( 'play none none reverse', 'animation-addons-for-elementor-pro' ),
				'condition'          => [
					'wcf_text_animation' => [ 'text_spin' ],
					'text_on_scroll' => 'yes',
				],
				'render_type'        => 'none',
			]
		);

		//breakpoint
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
			'text_animation_breakpoint',
			[
				'label'              => esc_html__( 'Breakpoint', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => esc_html__( 'Note: Choose at which breakpoint animation will work.', 'animation-addons-for-elementor-pro' ),
				'options'            => $dropdown_options,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => '',
				'condition'          => [
					'wcf_text_animation!' => 'none',
				],
			]
		);

		$element->add_control(
			'text_breakpoint_min_max',
			[
				'label'              => esc_html__( 'Breakpoint Min/Max', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'min',
				'options'            => [
					'min' => esc_html__( 'Min(>)', 'animation-addons-for-elementor-pro' ),
					'max' => esc_html__( 'Max(<)', 'animation-addons-for-elementor-pro' ),
				],
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_text_animation!'        => 'none',
					'text_animation_breakpoint!' => '',
				],
			]
		);

		$element->end_controls_section();
	}

}

WCF_Text_Animation_Effects::init();
