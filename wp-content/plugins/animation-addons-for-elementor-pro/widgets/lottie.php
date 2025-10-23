<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class Lottie extends Widget_Base {

	public function get_name() {
		return 'wcf--lottie-animation';
	}

	public function get_title() {
		return esc_html__( 'Lottie', 'animation-addons-for-elementor-pro' );
	}

	public function get_icon() {
		return 'wcf eicon-animation';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_keywords() {
		return [ 'animation', 'lottie' ];
	}
	
	public function get_script_depends() {
	   
		return [ 'wcf-lottie' ];
	}
		
    protected function register_controls() {
        $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Settings', 'animation-addons-for-elementor-pro'),
			]
		);
		
        $this->add_control(
			'source',
			[
				'label' => esc_html__( 'Source', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'media_file',
				'options' => [
					'media_file' => esc_html__( 'Media File', 'animation-addons-for-elementor-pro'),
					'external_url' => esc_html__( 'External URL', 'animation-addons-for-elementor-pro'),
				],
				
			]
		);

		$this->add_control(
			'source_external_url',
			[
				'label' => esc_html__( 'External URL', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::URL,
				'condition' => [
					'source' => 'external_url',
				],
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your URL', 'animation-addons-for-elementor-pro'),				
			]
		);

		$this->add_control(
			'source_json',
			[
				'label' => esc_html__( 'Upload JSON File', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'media_types' => [ 'application/json' ],				
				'condition' => [
					'source' => 'media_file',
				],
			]
		);
		
		$this->add_control(
			'wcf_interactivity_event',
			[
				'label' => esc_html__( 'Trigger', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => '',		
				'options' => [
					'' => esc_html__( 'None', 'animation-addons-for-elementor-pro'),
					'scroll' => esc_html__( 'On Scroll', 'animation-addons-for-elementor-pro'),
					'hover' => esc_html__( 'On Hover', 'animation-addons-for-elementor-pro'),
					'cursor_move'  => esc_html__( 'Mouse Cursor', 'animation-addons-for-elementor-pro'),					
					'click'  => esc_html__( 'On Click', 'animation-addons-for-elementor-pro'),					
					'viewport'  => esc_html__( 'Viewport', 'animation-addons-for-elementor-pro'),					
				],				
			]
		);
		
		$this->add_control(
			'wcf_interactivity_event_pause',
			[
				'label' => esc_html__( 'Pause', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => '',	
				'condition' => ['wcf_interactivity_event' => ['click','hover','viewport']],
				'options' => [
					'' => esc_html__( 'None', 'animation-addons-for-elementor-pro'),
					'onmouseleave' => esc_html__( 'On Mouseleave', 'animation-addons-for-elementor-pro'),
					'onclick' => esc_html__( 'On Click', 'animation-addons-for-elementor-pro'),						
										
				],				
			]
		);
		
		$this->add_control(
			'wcf_interactivity_event_replay',
			[
				'label' => esc_html__( 'Replay', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'condition' => ['wcf_interactivity_event' => ['click','hover','viewport'], 'wcf_interactivity_event_pause!' => ['']],
				'default' => '',				
				'options' => [
					'' => esc_html__( 'None', 'animation-addons-for-elementor-pro'),
					'onhover' => esc_html__( 'On Hover', 'animation-addons-for-elementor-pro'),
					'onclick' => esc_html__( 'On Click', 'animation-addons-for-elementor-pro'),
					'inview'  => esc_html__( 'Viewport', 'animation-addons-for-elementor-pro')		
				],				
			]
		);
		
		$this->add_control(
			'start_point',
			[
				'label' => esc_html__( 'Start Point', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'condition' => ['wcf_interactivity_event' => ['scroll','cursor_move']],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 5,
					],					
				]				
				
			]
		);
		
		$this->add_control(
			'end_point',
			[
				'label' => esc_html__( 'End Point', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SLIDER,
				'condition' => ['wcf_interactivity_event' => ['scroll','cursor_move']],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 1500,
						'step' => 5,
					],					
				]				
				
			]
		);
		
		
		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay?', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro'),
				'label_off' => esc_html__( 'No', 'animation-addons-for-elementor-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => ['wcf_interactivity_event' => ['']]
			]
		);
		
		$this->add_control(
			'controls',
			[
				'label' => esc_html__( 'Controls?', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro'),
				'label_off' => esc_html__( 'No', 'animation-addons-for-elementor-pro'),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop?', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro'),
				'label_off' => esc_html__( 'No', 'animation-addons-for-elementor-pro'),
				'condition' => ['wcf_interactivity_event!' => ['scroll']],
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->add_control(
			'loop_count',
			[
				'label' => esc_html__( 'Times', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 10,
				'condition' => ['loop' => ['yes']]
			]
		);
		
		
		
		$this->add_control(
			'backward',
			[
				'label' => esc_html__( 'Backward Direction?', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro'),
				'label_off' => esc_html__( 'No', 'animation-addons-for-elementor-pro'),
				'condition' => ['wcf_interactivity_event' => ['hover']],
				'return_value' => 'yes',
				'description' => esc_html__('Play it backward.','animation-addons-for-elementor-pro'),
				'default' => '',
			]
		);		
		
		
		
		$this->add_control(
			'wcf_speed',
			[
				'label' => esc_html__( 'Speed', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 5,
					],					
				]				
				
			]
		);
		
	
		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_properties_section',
			[
				'label' => esc_html__( 'Additional Properties', 'animation-addons-for-elementor-pro'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		
		$this->add_control(
			'wcf_renderer',
			[
				'label' => esc_html__( 'Renderer', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'animation-addons-for-elementor-pro'),
					'svg' => esc_html__( 'Svg', 'animation-addons-for-elementor-pro'),
					// 'html' => esc_html__( 'Html', 'animation-addons-for-elementor-pro'),
					'canvas'  => esc_html__( 'Canvas', 'animation-addons-for-elementor-pro'),					
				],				
			]
		);		
		
		$this->add_control(
			'intermission',
			[
				'label' => esc_html__( 'Intermission', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'condition' => ['wcf_interactivity_event' => ['']],
				'description' => esc_html__('Duration (in milliseconds) to pause before playing each cycle in a looped animation. Set this parameter to 0 (no pause) or any positive number. ','animation-addons-for-elementor-pro'),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 5,
					],					
				]				
				
			]
		);		
		
		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::COLOR,
			]
		);


		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'animation-addons-for-elementor-pro'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'con_width',
				[
					'label' => esc_html__( 'Width', 'animation-addons-for-elementor-pro'),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],					
					'selectors' => [
						'{{WRAPPER}} lottie-player' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'con_height',
				[
					'label' => esc_html__( 'Height', 'animation-addons-for-elementor-pro'),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],					
					'selectors' => [
						'{{WRAPPER}} lottie-player' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
					

		$this->end_controls_section();

	}

	protected function render() {
	
        $settings = $this->get_settings_for_display();
        $source = $settings['source'];
        $url = '';
        if($source == 'media_file'){
            $source_json = $settings['source_json'];
            $url = isset($source_json['url']) ? $source_json['url'] : $url;
        }elseif($source == 'external_url'){
           $source_json = $settings['source_external_url'];
           $url = isset($source_json['url']) ? $source_json['url'] : $url;
        }	
       
				// Check if SSL is supported on the current server
		$is_ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

		//$url = set_url_scheme($url, $is_ssl ? 'https' : 'http');

        $id = $this->get_id();      
		$this->add_render_attribute(
			'wrapper',
			[
				'id' => 'wcf-lottie-player-'.esc_attr($id),
				'class' => [ 'wcf-lottie-wrp' ],					
				'loop' => $settings['loop'] == 'yes' ? true: false,			
				'background' => $settings['background_color'] !== '' ? $settings['background_color']: 'transparent',
				'src' => esc_url($url),
				'data-settings' => json_encode(
					[ 
						'event'         => $settings['wcf_interactivity_event'],
						'pause'         => $settings['wcf_interactivity_event_pause'], 
						'play'          => $settings['wcf_interactivity_event_replay'] ,
						'start_point'   => isset( $settings['start_point']['size'] ) ?$settings['start_point']['size'] : 0, 
						'end_point'     => isset($settings['end_point']['size']) ? $settings['end_point']['size'] : 300
					] 
				),
			]
		);
		
		$this->set_properties_option();		
		
		?>
          <lottie-player <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>></lottie-player>
		<?php
	}
	
	public function set_properties_option(){

		$settings = $this->get_settings_for_display();

		if($settings['autoplay'] == 'yes')
		{
			$this->add_render_attribute(
				'wrapper',
				[					
					'autoplay' => true,				
				]
			);
		}
		
		if($settings['controls'] == 'yes')
		{
			$this->add_render_attribute(
				'wrapper',
				[					
					'controls' => true,				
				]
			);
		}
		
		if( $settings[ 'loop' ] == 'yes' )
		{		
			$this->add_render_attribute(
				'wrapper',
				[					
					'count' => $settings['loop_count'],				
				]
			);	
		}
		
		if($settings['wcf_interactivity_event'] == 'hover')
		{		
			$this->add_render_attribute(
				'wrapper',
				[					
					'hover' => true,				
				]
			);	
		}
		
		if($settings['backward'] == 'yes'){
		
			$this->add_render_attribute(
				'wrapper',
				[					
					'direction' => -1,				
				]
			);	
		}		
	
		if($settings['wcf_renderer'] != ''){
		
			$this->add_render_attribute(
				'wrapper',
				[					
					'renderer' => $settings['wcf_renderer'],				
				]
			);
			
		}
				
		
		if( isset( $settings['wcf_speed']['size'] ) &&  is_numeric( $settings['wcf_speed']['size'] ) ){
		
			$this->add_render_attribute(
				'wrapper',
				[					
					'speed' => $settings['wcf_speed']['size']				
				]
			);				
		}
	}
}