<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class WCF_Image_Animation_Effects {

	public static function init() {

		$image_elements = [
			[
				'name'    => 'image',
				'section' => 'section_image',
			],
			[
				'name'    => 'wcf--image',
				'section' => 'section_content',
			],
		];
		foreach ( $image_elements as $element ) {
			add_action( 'elementor/element/' . $element['name'] . '/' . $element['section'] . '/after_section_end', [
				__CLASS__,
				'register_image_animation_controls',
			], 10, 2 );
		}

		//image reveal
		$image_reveal_elements = [
			[
				'name'    => 'wcf--image-box',
				'section' => 'section_button_content',
			],
			[
				'name'    => 'wcf--timeline',
				'section' => 'section_timeline',
			],
		];
		foreach ( $image_reveal_elements as $element ) {
			add_action( 'elementor/element/' . $element['name'] . '/' . $element['section'] . '/after_section_end', [
				__CLASS__,
				'register_image_reveal_animation_controls',
			], 10, 2 );
		}
	}

	public static function register_image_animation_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_image_animation',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text">%s<span>', __( 'Image Animation', 'animation-addons-for-elementor-pro' ), __( 'Pro', 'animation-addons-for-elementor-pro' ) ),
			]
		);

		$element->add_control(
			'wcf-image-animation',
			[
				'label'              => esc_html__( 'Animation', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'separator'          => 'before',
				'options'            => [
					'none'    => esc_html__( 'none', 'animation-addons-for-elementor-pro' ),
					'reveal'  => esc_html__( 'Reveal', 'animation-addons-for-elementor-pro' ),
					'scale'   => esc_html__( 'Scale', 'animation-addons-for-elementor-pro' ),
					'stretch' => esc_html__( 'Stretch', 'animation-addons-for-elementor-pro' ),
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf_img_animation_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'animation-addons-for-elementor-pro' ),
				'description'        => esc_html__( 'For better performance in editor mode, keep the setting turned off.', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf-image-animation!' => 'none',
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
					'wcf-image-animation!' => 'none',
					'wcf_img_animation_editor' => 'yes'
				],
			]
		);

		$element->add_control(
			'wcf-scale-start',
			[
				'label'     => esc_html__( 'Start Scale', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0.7,
				'condition' => [ 'wcf-image-animation' => 'scale' ],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf-scale-end',
			[
				'label'     => esc_html__( 'End Scale', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => [ 'wcf-image-animation' => 'scale' ],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf-animation-start',
			[
				'label'              => esc_html__( 'Animation Start', 'animation-addons-for-elementor-pro' ),
				'description'        => esc_html__( 'First value is element position, Second value is display position', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'top top',
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
					'custom'        => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				],
				'condition'          => [ 'wcf-image-animation' => 'scale' ],
			]
		);

		$element->add_control(
			'wcf_animation_custom_start',
			[
				'label'       => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'top 90%', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'top 90%', 'animation-addons-for-elementor-pro' ),
				'render_type'        => 'none',
				'condition'   => [
					'wcf-image-animation' => 'scale',
					'wcf-animation-start' => 'custom'
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'image-ease',
			[
				'label'              => esc_html__( 'Data ease', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'power2.out',
				'options'            => [
					'power2.out' => esc_html__( 'Power2.out', 'animation-addons-for-elementor-pro' ),
					'bounce'     => esc_html__( 'Bounce', 'animation-addons-for-elementor-pro' ),
					'back'       => esc_html__( 'Back', 'animation-addons-for-elementor-pro' ),
					'elastic'    => esc_html__( 'Elastic', 'animation-addons-for-elementor-pro' ),
					'slowmo'     => esc_html__( 'Slowmo', 'animation-addons-for-elementor-pro' ),
					'stepped'    => esc_html__( 'Stepped', 'animation-addons-for-elementor-pro' ),
					'sine'       => esc_html__( 'Sine', 'animation-addons-for-elementor-pro' ),
					'expo'       => esc_html__( 'Expo', 'animation-addons-for-elementor-pro' ),
				],
				'condition'          => [ 'wcf-image-animation' => 'reveal' ],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}

	public static function register_image_reveal_animation_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_image_animation',
			[				
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text">%s<span>', __( 'Image Animation', 'animation-addons-for-elementor-pro' ), __( 'Pro', 'animation-addons-for-elementor-pro' ) ),
			]
		);

		$element->add_control(
			'wcf-image-animation',
			[
				'label'              => esc_html__( 'Animation', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'separator'          => 'before',
				'options'            => [
					'none'   => esc_html__( 'none', 'animation-addons-for-elementor-pro' ),
					'reveal' => esc_html__( 'Reveal', 'animation-addons-for-elementor-pro' ),
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'image-ease',
			[
				'label'              => esc_html__( 'Data ease', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'power2.out',
				'options'            => [
					'power2.out' => esc_html__( 'Power2.out', 'animation-addons-for-elementor-pro' ),
					'bounce'     => esc_html__( 'Bounce', 'animation-addons-for-elementor-pro' ),
					'back'       => esc_html__( 'Back', 'animation-addons-for-elementor-pro' ),
					'elastic'    => esc_html__( 'Elastic', 'animation-addons-for-elementor-pro' ),
					'slowmo'     => esc_html__( 'Slowmo', 'animation-addons-for-elementor-pro' ),
					'stepped'    => esc_html__( 'Stepped', 'animation-addons-for-elementor-pro' ),
					'sine'       => esc_html__( 'Sine', 'animation-addons-for-elementor-pro' ),
					'expo'       => esc_html__( 'Expo', 'animation-addons-for-elementor-pro' ),
				],
				'condition'          => [ 'wcf-image-animation' => 'reveal' ],
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}

}

WCF_Image_Animation_Effects::init();
