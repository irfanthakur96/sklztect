<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WCF_ADDONS\WCF_Post_Query_Trait;
use WCFAddonsPro\WCF_Post_Handler_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Posts
 *
 * Elementor widget for Posts.
 *
 * @since 1.0.0
 */
class Posts_Pro extends Widget_Base {

	use WCF_Post_Query_Trait;
	use WCF_Post_Handler_Trait;

	/**
	 * @var \WP_Query
	 */
	protected $query = null;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'wcf--posts-pro';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'Advanced Posts','animation-addons-for-elementor-pro' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'wcf eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'weal-coder-addon', 'wcf-archive-addon' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_script_depends() {
		return [ 'swiper', 'wcf--posts', 'aae--filtering' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'swiper', 'wcf--post-pro' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_filter',
			[
				'label' => __( 'Filter','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'enable_filter',
			[
				'label'        => esc_html__( 'Enable Filter','animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show','animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide','animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'filter_all_label',
			[
				'label'     => esc_html__( 'Filter All Labels','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'All','animation-addons-for-elementor-pro' ),
				'condition' => [ 'enable_filter' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'filter_align',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .posts-filter' => 'justify-content: {{VALUE}};',
				],
				'condition' => [ 'enable_filter' => 'yes' ],
			]
		);

		$this->end_controls_section();

		//query
		$this->register_query_controls();

		//layout
		$this->register_layout_controls();

		// Audio Gallery
		$this->register_audio_gallery_controls();

		//settings
		$this->register_settings_controls();

		//Thumbnail style
		$this->register_thumbnail_controls();

		// Content style
		$this->register_content_wrap_controls();

		//title
		$this->register_title_controls();

		//excerpt
		$this->register_excerpt_controls();

		$this->register_taxonomy_controls();

		$this->register_meta_controls();

		//read more
		$this->register_read_more_controls();

		// Rating
		$this->register_post_rating_controls();


		//pagination
		$this->register_pagination_controls();

		//audio video play
		$this->register_audio_video_play_controls();

		// Filter
		$this->register_filter_style();
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings','animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns','animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SELECT,
				'render_type'    => 'template',
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'selectors'      => [
					'{{WRAPPER}} .wcf-posts' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Posts Per Page','animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_control(
			'show_thumb',
			[
				'label'     => esc_html__( 'Show Thumb','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Off','animation-addons-for-elementor-pro' ),
				'label_on'  => esc_html__( 'On','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail_size',
				'exclude'   => [ 'custom' ],
				'default'   => 'medium',
				'condition' => [
					'show_thumb' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'     => esc_html__( 'Show Title','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Off','animation-addons-for-elementor-pro' ),
				'label_on'  => esc_html__( 'On','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'     => esc_html__( 'Show Excerpt','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show','animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide','animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_taxonomy',
			[
				'label'     => esc_html__( 'Show Taxonomy','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show','animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide','animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'     => esc_html__( 'Show Meta','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show','animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide','animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_rating',
			[
				'label'     => esc_html__( 'Show Rating','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show','animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide','animation-addons-for-elementor-pro' ),
				'default'   => 'no',
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'     => esc_html__( 'Read More','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show','animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide','animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
			]
		);

		//masonry
		$this->add_control(
			'masonry',
			[
				'label'       => esc_html__( 'Masonry','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Off','animation-addons-for-elementor-pro' ),
				'label_on'    => esc_html__( 'On','animation-addons-for-elementor-pro' ),
				'separator'   => 'before',
				'condition'   => [
					'columns!' => '1',
				],
				'render_type' => 'template',
				'selectors'   => [
					'{{WRAPPER}} .wcf-posts' => 'grid-auto-flow: dense;',
				],
			]
		);

		$this->add_control(
			'masonry_large',
			[
				'label'       => esc_html__( 'Masonry Large Items','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '2, 6, 11','animation-addons-for-elementor-pro' ),
				'description' => esc_html__( 'Give the item sequence number with Coma separated.','animation-addons-for-elementor-pro' ),
				'ai'          => [
					'active' => false,
				],
				'condition'   => [
					'masonry'  => 'yes',
					'columns!' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'col_span',
			[
				'label'          => esc_html__( 'Col Span','animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => esc_html__( '1','animation-addons-for-elementor-pro' ),
					'2' => esc_html__( '2','animation-addons-for-elementor-pro' ),
					'3' => esc_html__( '3','animation-addons-for-elementor-pro' ),
					'4' => esc_html__( '4','animation-addons-for-elementor-pro' ),
					'5' => esc_html__( '5','animation-addons-for-elementor-pro' ),
				],
				'selectors'      => [
					'{{WRAPPER}} .item-masonry' => 'grid-column: span {{VALUE}};',
				],
				'condition'      => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'row_span',
			[
				'label'          => esc_html__( 'Row Span','animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '1',
				'mobile_default' => '1',
				'options'        => [
					'1' => esc_html__( '1','animation-addons-for-elementor-pro' ),
					'2' => esc_html__( '2','animation-addons-for-elementor-pro' ),
					'3' => esc_html__( '3','animation-addons-for-elementor-pro' ),
					'4' => esc_html__( '4','animation-addons-for-elementor-pro' ),
					'5' => esc_html__( '5','animation-addons-for-elementor-pro' ),
				],
				'selectors'      => [
					'{{WRAPPER}} .item-masonry' => 'grid-row: span {{VALUE}};',
				],
				'condition'      => [
					'masonry' => 'yes',
				],
			]
		);

		//post format audio/video
		$this->add_control(
			'post_format_a_v',
			[
				'label'        => esc_html__( 'Post Audio, Video & Gallery','animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off','animation-addons-for-elementor-pro' ),
				'label_on'     => esc_html__( 'On','animation-addons-for-elementor-pro' ),
				'separator'    => 'before',
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function register_audio_video_play_controls() {
		$this->start_controls_section(
			'section_audio_video_play',
			[
				'label'     => esc_html__( 'Post Popup','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'post_format_a_v' => 'yes' ]
			]
		);

		$this->add_control(
			'audio_video_play',
			[
				'label'       => esc_html__( 'Video Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-play-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'audio_icon',
			[
				'label'       => esc_html__( 'Audio Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-music',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'gallery_icon',
			[
				'label'       => esc_html__( 'Gallery Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'far fa-images',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_responsive_control(
			'audio_video_play_size',
			[
				'label'      => esc_html__( 'Play Icon Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .play' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'audio_video_play_color',
			[
				'label'     => esc_html__( 'Play Icon Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .play' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'audio_video_play_offset_x',
			[
				'label'      => esc_html__( 'Offset X','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .play' => '--offset-x: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'audio_video_play_offset_y',
			[
				'label'      => esc_html__( 'Offset Y','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .play' => '--offset-y: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout','animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// Image selector
		$this->add_control(
			'post_layout',
			[
				'label'   => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'layout-normal'    => [
						'title' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-2.jpg',
					],
					'layout-aside'     => [
						'title' => esc_html__( 'Aside', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-1.jpg',
					],
					'layout-overlay'   => [
						'title' => esc_html__( 'Overlay', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-3.jpg',
					],
					'layout-overlay-2' => [
						'title' => esc_html__( 'Overlay Two', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-3.jpg',
					],
					'layout-audio'     => [
						'title' => esc_html__( 'Audio Gallery', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-3.jpg',
					],
				],
				'default' => 'layout-normal',
			]
		);

		$this->add_responsive_control(
			'layout-aside-position',
			[
				'label'          => esc_html__( 'Aside Position', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::CHOOSE,
				'toggle'         => false,
				'default'        => 'row',
				'mobile_default' => 'column',
				'options'        => [
					'row'         => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column'      => [
						'title' => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-top',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'condition'      => [
					'post_layout' => 'layout-aside',
				],
				'selectors'      => [
					'{{WRAPPER}} .wcf-post' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'layout_audio_notice',
			[
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content'    => esc_html__( 'This is only for audio type posts.', 'animation-addons-for-elementor-pro' ),
				'condition'  => [ 'post_layout' => 'layout-audio' ],
			]
		);

		//layout overlay/aside
		$layout_one = new Repeater();

		$layout_one->add_control(
			'post_item',
			[
				'label'   => esc_html__( 'Item', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
					'taxonomy'  => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
					'excerpt'   => esc_html__( 'Excerpt', 'animation-addons-for-elementor-pro' ),
					'meta'      => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
					'rating'    => esc_html__( 'Rating', 'animation-addons-for-elementor-pro' ),
					'read_more' => esc_html__( 'Read More', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'post_layout_one',
			[
				'label'        => esc_html__( 'Layout Aside/Overlay','animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $layout_one->get_controls(),
				'item_actions' => [
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => true,
				],
				'default'      => [
					[
						'post_item' => 'rating',
					],
					[
						'post_item' => 'taxonomy',
					],
					[
						'post_item' => 'title',
					],
					[
						'post_item' => 'excerpt',
					],
					[
						'post_item' => 'meta',
					],
					[
						'post_item' => 'read_more',
					],
				],
				'title_field'  => '{{{ post_item }}}',
				'condition'    => [ 'post_layout' => [ 'layout-aside', 'layout-overlay', 'layout-overlay-2' ] ]
			]
		);

		//layout normal
		$layout_two = new Repeater();

		$layout_two->add_control(
			'post_item',
			[
				'label'   => esc_html__( 'Item', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'thumb'     => esc_html__( 'Thumbnail', 'animation-addons-for-elementor-pro' ),
					'title'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
					'taxonomy'  => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
					'excerpt'   => esc_html__( 'Excerpt', 'animation-addons-for-elementor-pro' ),
					'meta'      => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
					'rating'    => esc_html__( 'Rating', 'animation-addons-for-elementor-pro' ),
					'read_more' => esc_html__( 'Read More', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'post_layout_two',
			[
				'label'        => esc_html__( 'Layout Normal','animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $layout_two->get_controls(),
				'item_actions' => [
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => true,
				],
				'default'      => [
					[
						'post_item' => 'rating',
					],
					[
						'post_item' => 'taxonomy',
					],
					[
						'post_item' => 'thumb',
					],
					[
						'post_item' => 'title',
					],
					[
						'post_item' => 'excerpt',
					],
					[
						'post_item' => 'meta',
					],
					[
						'post_item' => 'read_more',
					],
				],
				'title_field'  => '{{{ post_item }}}',
				'condition'    => [ 'post_layout' => [ 'layout-normal' ] ]
			]
		);

		$this->add_control(
			'post_layout_audio',
			[
				'label'        => esc_html__( 'Audio Layout','animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $layout_one->get_controls(),
				'item_actions' => [
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => true,
				],
				'default'      => [
					[
						'post_item' => 'taxonomy',
					],
					[
						'post_item' => 'title',
					],
					[
						'post_item' => 'excerpt',
					],
					[
						'post_item' => 'meta',
					],
					[
						'post_item' => 'read_more',
					],
				],
				'title_field'  => '{{{ post_item }}}',
				'condition'    => [ 'post_layout' => [ 'layout-audio' ] ]
			]
		);

		$this->end_controls_section();

		//layout style
		$this->register_layout_style_controls();
	}

	protected function register_layout_style_controls() {
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => esc_html__( 'Layout','animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'      => esc_html__( 'Columns Gap','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 30,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-posts' => 'column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'      => esc_html__( 'Rows Gap','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 35,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-posts' => 'row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'post_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .wcf-post',
			]
		);

		$this->add_responsive_control(
			'post_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'aabackground',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wcf-post',
			]
		);

		//hover effect
		$this->add_control(
			'el_hover_effects',
			[
				'label'        => esc_html__( 'Hover Effect','animation-addons-for-elementor-pro' ),
				'description'  => esc_html__( 'This effect will work only on image tags.','animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'effect-zoom-in',
				'options'      => [
					''                => esc_html__( 'None','animation-addons-for-elementor-pro' ),
					'effect-zoom-in'  => esc_html__( 'Zoom In','animation-addons-for-elementor-pro' ),
					'effect-zoom-out' => esc_html__( 'Zoom Out','animation-addons-for-elementor-pro' ),
					'left-move'       => esc_html__( 'Left Move','animation-addons-for-elementor-pro' ),
					'right-move'      => esc_html__( 'Right Move','animation-addons-for-elementor-pro' ),
				],
				'prefix_class' => 'wcf--image-',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'     => esc_html__( 'Alignment','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'default'   => 'start',
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Left','animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center','animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'Right','animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-post'          => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wcf-post-taxonomy' => 'align-self: {{VALUE}};',
					'{{WRAPPER}} .wcf-post-meta'     => 'align-self: {{VALUE}};',
					'{{WRAPPER}} .wcf-post-link'     => 'align-self: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_thumbnail_controls() {
		$this->start_controls_section(
			'section_style_post_image',
			[
				'label'     => esc_html__( 'Thumbnail','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumb'   => 'yes',
					'post_layout!' => 'layout-overlay',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_width',
			[
				'label'      => esc_html__( 'Width','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_height',
			[
				'label'      => esc_html__( 'Height','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'masonry_thumb_height',
			[
				'label'      => esc_html__( 'Masonry Height','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post.item-masonry .thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_border_radius',
			[
				'label'      => esc_html__( 'Border Radius','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_wrap_controls() {
		$this->start_controls_section(
			'section_style_post_content',
			[
				'label'     => esc_html__( 'Content','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'post_layout!' => 'layout-normal' ]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'content_background',
				'types'     => [ 'classic', 'gradient' ],
				'exclude'   => [ 'image' ],
				'selector'  => '{{WRAPPER}} .content',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_title_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label'     => esc_html__( 'Title','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_length',
			[
				'label' => esc_html__( 'Title Length','animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 2,
				'max'   => 100,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default'   => 'h3',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title_highlight',
			[
				'label'              => esc_html__( 'Show Highlight','animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'label_on'           => esc_html__( 'Show','animation-addons-for-elementor-pro' ),
				'label_off'          => esc_html__( 'Hide','animation-addons-for-elementor-pro' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'highlight_title_length',
			[
				'label'              => esc_html__( 'Highlight Length','animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5,
				'min'                => 2,
				'max'                => 100,
				'condition'          => [
					'show_title_highlight' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_title_style',
			[
				'label'     => esc_html__( 'Title','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);


		$this->start_controls_tabs( 'tabs_title' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => esc_html__( 'Normal','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .wcf-post-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_tile_hover',
			[
				'label' => esc_html__( 'Hover','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'title_hover_style',
			[
				'label'        => esc_html__( 'Hover Type', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'prefix_class' => 'wcf--title-',
				'default'      => '',
				'options'      => [
					''          => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'underline' => esc_html__( 'Underline', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography_hover',
				'selector'  => '{{WRAPPER}} .wcf-post-title:hover',
				'condition' => [ 'title_hover_style' => '' ],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title:hover, .item-masonry .wcf-post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_under_color',
			[
				'label'     => esc_html__( 'Underline Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title a' => '--underline-color: {{VALUE}};',
				],
				'condition' => [ 'title_hover_style' => 'underline' ],
			]
		);

		$this->add_responsive_control(
			'title_under_thickness',
			[
				'label'      => esc_html__( 'Thickness', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-title a' => '--underline-thickness: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'title_hover_style' => 'underline' ],
			]
		);

		$this->add_control(
			'title_under_transition',
			[
				'label'     => esc_html__( 'Transition', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 3,
				'step'      => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title a' => '--underline-transition:  {{VALUE}}s;',
				],
				'condition' => [ 'title_hover_style' => 'underline' ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_highlight',
			[
				'label'     => esc_html__( 'Highlight','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_title_highlight' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_h_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title .highlight' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title_highlight' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_h_typography',
				'selector'  => '{{WRAPPER}} .wcf-post-title .highlight',
				'condition' => [
					'show_title_highlight' => 'yes',
				],
			]
		);

		// Masonry title

		$this->add_control(
			'heading_masonry',
			[
				'label'     => esc_html__( 'Masonry Title','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_masonry_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-masonry .wcf-post-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_masonry_typography',
				'selector'  => '{{WRAPPER}} .item-masonry .wcf-post-title',
				'condition' => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_masonry_h_color',
			[
				'label'     => esc_html__( 'Highlight Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-masonry .wcf-post-title .highlight' => 'color: {{VALUE}};',
				],
				'condition' => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_masonry_high_typography',
				'selector'  => '{{WRAPPER}} .item-masonry .wcf-post-title .highlight',
				'condition' => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'masonry_title_margin',
			[
				'label'      => esc_html__( 'Masonry Title Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .item-masonry .wcf-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'masonry' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_excerpt_controls() {
		$this->start_controls_section(
			'section_post_excerpt',
			[
				'label'     => esc_html__( 'Excerpt','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'     => esc_html__( 'Excerpt Length','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'default'   => 30,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_style_post_excerpt',
			[
				'label'     => esc_html__( 'Excerpt','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .wcf-post-excerpt',
			]
		);

		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_taxonomy_controls() {

		$this->start_controls_section(
			'section_taxonomy',
			[
				'label'     => esc_html__( 'Taxonomy','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_taxonomy' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_taxonomy',
			[
				'label'       => esc_html__( 'Taxonomy','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => 'category',
				'options'     => $this->get_taxonomies(),
			]
		);

		$this->add_control(
			'taxonomy_limit',
			[
				'label'   => esc_html__( 'Limit','animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => - 1,
				'max'     => 5,
				'default' => 1,
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_taxonomy_style',
			[
				'label'     => esc_html__( 'Taxonomy','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_taxonomy' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'taxonomy_typography',
				'selector' => '{{WRAPPER}} .wcf-post-taxonomy a',
			]
		);

		$this->add_responsive_control(
			'taxonomy_spacing',
			[
				'label'      => esc_html__( 'Space Between','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_taxonomy' );

		$this->start_controls_tab(
			'tab_taxonomy_normal',
			[
				'label' => esc_html__( 'Normal','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'taxonomy_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'taxonomy_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wcf-post-taxonomy a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_taxonomy_hover',
			[
				'label' => esc_html__( 'Hover','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'tax_hover_style',
			[
				'label'        => esc_html__( 'Hover Type', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'prefix_class' => 'wcf--taxonomy-',
				'default'      => '',
				'options'      => [
					''          => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'underline' => esc_html__( 'Underline', 'animation-addons-for-elementor-pro' ),
				],
				'condition'    => [ 'post_layout!' => 'layout-overlay-2' ],
			]
		);

		$this->add_control(
			'taxonomy_hover_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [ 'tax_hover_style' => '' ],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'taxonomy_hover_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .wcf-post-taxonomy a:hover',
				'condition' => [ 'tax_hover_style' => '' ],
			]
		);

		$this->add_control(
			'tax_under_color',
			[
				'label'     => esc_html__( 'Underline Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => '--tax-ul-color: {{VALUE}};',
				],
				'condition' => [ 'tax_hover_style' => 'underline' ],
			]
		);

		$this->add_responsive_control(
			'tax_under_thickness',
			[
				'label'      => esc_html__( 'Thickness', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => '--tax-ul-thickness: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'tax_hover_style' => 'underline' ],
			]
		);

		$this->add_responsive_control(
			'tax_under_btm',
			[
				'label'      => esc_html__( 'Position', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 10,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => '--tax-btm-position: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'tax_hover_style' => 'underline' ],
			]
		);

		$this->add_control(
			'tax_under_transition',
			[
				'label'     => esc_html__( 'Transition', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 3,
				'step'      => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => '--tax-ul-transition:  {{VALUE}}s;',
				],
				'condition' => [ 'tax_hover_style' => 'underline' ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'taxonomy_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .wcf-post-taxonomy a',
			]
		);

		$this->add_responsive_control(
			'taxonomy_border_radius',
			[
				'label'      => esc_html__( 'Border Radius','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'taxonomy_box_shadow',
				'selector' => '{{WRAPPER}} .wcf-post-taxonomy a',
			]
		);

		$this->add_responsive_control(
			'taxonomy_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//offset
		$this->add_control(
			'taxonomy_position',
			[
				'label'     => esc_html__( 'Position', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''         => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'absolute' => esc_html__( 'Absolute', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy' => 'position: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'taxonomy_offset_x',
			[
				'label'      => esc_html__( 'Offset X', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => 0,
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} .wcf-post-taxonomy' => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .wcf-post-taxonomy'       => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [ 'taxonomy_position!' => '' ],
			]
		);

		$this->add_responsive_control(
			'taxonomy_offset_y',
			[
				'label'      => esc_html__( 'Offset Y', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
				'default'    => [
					'size' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [ 'taxonomy_position!' => '', ],
			]
		);

		$this->add_responsive_control(
			'masonry_taxonomy_offset_x',
			[
				'label'      => esc_html__( 'Masonry Offset X', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => 0,
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} .item-masonry .wcf-post-taxonomy' => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .item-masonry .wcf-post-taxonomy'       => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [ 'taxonomy_position!' => '', 'masonry' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'masonry_taxonomy_offset_y',
			[
				'label'      => esc_html__( 'Masonry Offset Y', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
				'default'    => [
					'size' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .item-masonry .wcf-post-taxonomy' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [ 'taxonomy_position!' => '', 'masonry' => 'yes' ],
			]
		);


		$this->end_controls_section();
	}

	protected function register_meta_controls() {

		$this->start_controls_section(
			'section_meta',
			[
				'label'     => esc_html__( 'Meta','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'post_meta',
			[
				'label'   => esc_html__( 'Meta','animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'comments',
				'options' => [
					'author'   => esc_html__( 'Author', 'animation-addons-for-elementor-pro' ),
					'view'     => esc_html__( 'View', 'animation-addons-for-elementor-pro' ),
					'date'     => esc_html__( 'Date', 'animation-addons-for-elementor-pro' ),
					'time'     => esc_html__( 'Time', 'animation-addons-for-elementor-pro' ),
					'comments' => esc_html__( 'Comments', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$repeater->add_control(
			'meta_icon',
			[
				'label'       => esc_html__( 'Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'far fa-flag',
					'library' => 'fa-regular',
				],
			]
		);

		$this->add_control(
			'post_meta_data',
			[
				'label'       => esc_html__( 'Meta Data','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'post_meta' => 'view',
					],
					[
						'post_meta' => 'date',
					],
				],
				'title_field' => '{{{ post_meta }}}',
			]
		);

		$this->add_control(
			'meta_separator',
			[
				'label'     => esc_html__( 'Separator Between', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '///',
				'ai'        => [
					'active' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-post-meta span + span:before' => 'content: "{{VALUE}}"',
				],
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'post_by',
			[
				'label'   => esc_html__( 'Author By','animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'By','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label'        => esc_html__( 'Author Avatar','animation-addons-for-elementor-pro' ),
				'description'  => esc_html__( 'If you want to use the author avatar, you must chose "Author" in the meta data.','animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show','animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide','animation-addons-for-elementor-pro' ),
				'separator'    => 'before',
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label'      => esc_html__( 'Avatar Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .post-author img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'show_avatar' => 'yes' ]
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_meta_style',
			[
				'label'     => esc_html__( 'Meta','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'meta_gap',
			[
				'label'      => esc_html__( 'Gap','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-meta span + span:before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wcf-post-meta'                    => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-meta' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .wcf-post-meta',
			]
		);

		$this->add_responsive_control(
			'meta_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon',
			[
				'label'      => esc_html__( 'Icon Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'separator'  => 'before',
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-meta .meta-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-meta .meta-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon_gap',
			[
				'label'      => esc_html__( 'Icon Gap','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-meta .meta-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//style admin
		$this->start_controls_section(
			'section_meta_admin_style',
			[
				'label'     => esc_html__( 'Meta Admin','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'author_gap',
			[
				'label'      => esc_html__( 'Gap','animation-addons-for-elementor-pro' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-author' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'author_by_heading',
			[
				'label' => esc_html__( 'Author By','animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'admin_by_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-meta .post-by' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'admin_by_typography',
				'selector' => '{{WRAPPER}} .wcf-post-meta .post-by',
			]
		);

		$this->add_control(
			'author_heading',
			[
				'label'     => esc_html__( 'Author','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'admin_typography',
				'selector' => '{{WRAPPER}} .post-author a',
			]
		);

		$this->start_controls_tabs( 'tabs_author' );

		$this->start_controls_tab(
			'tab_author_normal',
			[
				'label' => esc_html__( 'Normal','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'author_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-author a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_author_hover',
			[
				'label' => esc_html__( 'Hover','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'author_color_hover',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-author a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_read_more_controls() {

		$this->start_controls_section(
			'section_post_read_more',
			[
				'label'     => esc_html__( 'Read More','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label'   => esc_html__( 'Read More Text','animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Read More','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'       => esc_html__( 'Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => esc_html__( 'Icon Position','animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => esc_html__( 'Before','animation-addons-for-elementor-pro' ),
					'right' => esc_html__( 'After','animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'icon_indend',
			[
				'label'     => esc_html__( 'Icon Spacing','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-post-link' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'style_post_read_more',
			[
				'label'     => esc_html__( 'Read More','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typography',
				'selector' => '{{WRAPPER}} .wcf-post-link',
			]
		);

		$this->add_responsive_control(
			'read_more_icon_size',
			[
				'label'      => esc_html__( 'Icon Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-link i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wcf-post-link svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'tabs_read_more',
		);

		$this->start_controls_tab(
			'tab_read_more_normal',
			[
				'label' => esc_html__( 'Normal','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'     => esc_html__( 'Text Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wcf-post-link' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'read_more_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-post-link',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_read-more_hover',
			[
				'label' => esc_html__( 'Hover','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'read_more_text_hover_color',
			[
				'label'     => esc_html__( 'Text Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-link:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'read_more_hover_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-post-link:hover',
			]
		);

		$this->add_control(
			'read_more_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-link:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'read_more_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'read_more_border',
				'selector'  => '{{WRAPPER}} .wcf-post-link',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'read_more_border_radius',
			[
				'label'      => esc_html__( 'Border Radius','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'read_more_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'read_more_shadow',
				'selector' => '{{WRAPPER}} .wcf-post-link',
			]
		);

		$this->add_responsive_control(
			'read_more_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_pagination_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => esc_html__( 'Pagination','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'   => esc_html__( 'Pagination','animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''                      => esc_html__( 'None','animation-addons-for-elementor-pro' ),
					'numbers'               => esc_html__( 'Numbers','animation-addons-for-elementor-pro' ),
					'prev_next'             => esc_html__( 'Previous/Next','animation-addons-for-elementor-pro' ),
					'numbers_and_prev_next' => esc_html__( 'Numbers','animation-addons-for-elementor-pro' ) . ' + ' . esc_html__( 'Previous/Next','animation-addons-for-elementor-pro' ),
					'load_on_click'         => esc_html__( 'Load On Click','animation-addons-for-elementor-pro' ),
					'infinite_scroll'       => esc_html__( 'Infinite Scroll','animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'pagination_page_limit',
			[
				'label'     => esc_html__( 'Page Limit','animation-addons-for-elementor-pro' ),
				'default'   => '5',
				'condition' => [
					'pagination_type' => [ 'numbers_and_prev_next', 'numbers', 'prev_next' ],
				],
			]
		);

		$this->add_control(
			'pagination_numbers_shorten',
			[
				'label'     => esc_html__( 'Shorten','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => [
					'pagination_type' => [ 'numbers_and_prev_next', 'numbers' ],
				],
			]
		);

		$this->add_control(
			'navigation_prev_icon',
			[
				'label'         => esc_html__( 'Previous','animation-addons-for-elementor-pro' ),
				'type'          => Controls_Manager::ICONS,
				'skin'          => 'inline',
				'label_block'   => false,
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon'  => 'eicon-chevron-left',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'   => [
					'fa-regular' => [
						'arrow-alt-circle-left',
						'caret-square-left',
					],
					'fa-solid'   => [
						'angle-double-left',
						'angle-left',
						'arrow-alt-circle-left',
						'arrow-circle-left',
						'arrow-left',
						'caret-left',
						'caret-square-left',
						'chevron-circle-left',
						'chevron-left',
						'long-arrow-alt-left',
					],
				],
				'condition'     => [
					'pagination_type' => [ 'prev_next', 'numbers_and_prev_next' ],
				],
			]
		);

		$this->add_control(
			'navigation_next_icon',
			[
				'label'         => esc_html__( 'Next','animation-addons-for-elementor-pro' ),
				'type'          => Controls_Manager::ICONS,
				'skin'          => 'inline',
				'label_block'   => false,
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon'  => 'eicon-chevron-right',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'   => [
					'fa-regular' => [
						'arrow-alt-circle-right',
						'caret-square-right',
					],
					'fa-solid'   => [
						'angle-double-right',
						'angle-right',
						'arrow-alt-circle-right',
						'arrow-circle-right',
						'arrow-right',
						'caret-right',
						'caret-square-right',
						'chevron-circle-right',
						'chevron-right',
						'long-arrow-alt-right',
					],
				],
				'condition'     => [
					'pagination_type' => [ 'prev_next', 'numbers_and_prev_next' ],
				],
			]
		);

		$this->add_responsive_control(
			'pagination_align',
			[
				'label'     => esc_html__( 'Alignment','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Left','animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center','animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'Right','animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .wcf-post-pagination' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .wcf-post-load-more'  => 'align-self: {{VALUE}};',
				],
				'condition' => [
					'pagination_type!' => [ '', 'infinite_scroll' ],
				],
			]
		);

		$this->add_responsive_control(
			'pagination_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wcf-post-load-more'  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'pagination_type!' => [ '', 'infinite_scroll' ],
				],
			]
		);

		$this->end_controls_section();

		// Pagination style controls for prev/next and numbers pagination.
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label'     => esc_html__( 'Pagination','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination_type' => [ 'numbers_and_prev_next', 'numbers', 'prev_next' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .wcf-post-pagination .page-numbers',
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label'      => esc_html__( 'Space Between','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'separator'  => 'before',
				'default'    => [
					'size' => 10,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-pagination' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tab_pagination' );

		$this->start_controls_tab(
			'tab_pagination_normal',
			[
				'label' => esc_html__( 'Normal','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-pagination .page-numbers' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'nabackground',
				'types'    => [ 'classic' ],
				'selector' => '{{WRAPPER}} .wcf-post-pagination .page-numbers',
			]
		);

		$this->add_responsive_control(
			'pagination_number_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'border',
				'selector' => '{{WRAPPER}} .wcf-post-pagination .page-numbers',
			]
		);

		$this->add_control(
			'pagination_number_border_radius',
			[
				'label'      => esc_html__( 'Border Radius','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_color_hover',
			[
				'label' => esc_html__( 'Hover','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'pagination_hover_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-pagination .page-numbers:not(.dots):hover, {{WRAPPER}} .wcf-post-pagination .page-numbers.current' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_hover_bg_color',
			[
				'label'     => esc_html__( 'Background','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-pagination .page-numbers:not(.dots):hover, {{WRAPPER}} .wcf-post-pagination .page-numbers.current' => 'background: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->register_load_more_controls();
	}

	protected function register_load_more_controls() {
		$this->start_controls_section(
			'section_load_more',
			[
				'label'     => esc_html__( 'Load More','animation-addons-for-elementor-pro' ),
				'condition' => [
					'pagination_type' => [
						'load_on_click',
						'infinite_scroll',
					],
				],
			]
		);

		$this->add_control(
			'load_more_spinner',
			[
				'label'                  => esc_html__( 'Spinner', 'animation-addons-for-elementor-pro' ),
				'type'                   => Controls_Manager::ICONS,
				'default'                => [
					'value'   => 'fas fa-spinner',
					'library' => 'fa-solid',
				],
				'exclude_inline_options' => [ 'svg' ],
				'recommended'            => [
					'fa-solid' => [
						'spinner',
						'cog',
						'sync',
						'sync-alt',
						'asterisk',
						'circle-notch',
					],
				],
				'skin'                   => 'inline',
				'label_block'            => false,
			]
		);

		$this->add_responsive_control(
			'load_more_spinner_size',
			[
				'label'      => esc_html__( 'Spinner Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .load-more-spinner' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_load_more_button',
			[
				'label'     => esc_html__( 'Button','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'pagination_type' => 'load_on_click',
				],
			]
		);

		$this->add_control(
			'load_more_btn_text',
			[
				'label'       => esc_html__( 'Text','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Load More','animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Load More','animation-addons-for-elementor-pro' ),
				'condition'   => [
					'pagination_type' => 'load_on_click',
				],
			]
		);

		$this->add_control(
			'load_more_btn_icon',
			[
				'label'       => esc_html__( 'Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => [
					'pagination_type' => 'load_on_click',
				],
			]
		);

		$this->add_control(
			'btn_icon_position',
			[
				'label'     => esc_html__( 'Icon Position','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'row',
				'options'   => [
					'row'         => esc_html__( 'After','animation-addons-for-elementor-pro' ),
					'row-reverse' => esc_html__( 'Before','animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf__posts-pro .wcf-post-load-more .load-more-text' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'pagination_type' => 'load_on_click',
				],
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_load_more_style',
			[
				'label'     => esc_html__( 'Load More','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination_type' => [ 'load_on_click' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'load_more_typography',
				'selector' => '{{WRAPPER}} .wcf-post-load-more',
			]
		);

		$this->add_responsive_control(
			'load_more_icon_size',
			[
				'label'      => esc_html__( 'Icon Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .load-more-text i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .load-more-text svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'tabs_load_more',
		);

		$this->start_controls_tab(
			'tab_load_more_normal',
			[
				'label' => esc_html__( 'Normal','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'load_more_color',
			[
				'label'     => esc_html__( 'Text Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wcf-post-load-more' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'load_more_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-post-load-more',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_load_more_hover',
			[
				'label' => esc_html__( 'Hover','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'load_more_text_hover_color',
			[
				'label'     => esc_html__( 'Text Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-load-more:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'load_more_hover_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-post-load-more:hover',
			]
		);

		$this->add_control(
			'load_more_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-load-more:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'load_more_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'load_more_border',
				'selector'  => '{{WRAPPER}} .wcf-post-load-more',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'load_more_border_radius',
			[
				'label'      => esc_html__( 'Border Radius','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'load_more_shadow',
				'selector' => '{{WRAPPER}} .wcf-post-load-more',
			]
		);

		$this->add_responsive_control(
			'load_more_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-load-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_filter_style() {
		$this->start_controls_section(
			'section_filter_style',
			[
				'label'     => __( 'Filter','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'enable_filter' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'filter_item_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .posts-filter li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_item_gap',
			[
				'label'      => esc_html__( 'Tab Item Gap','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .posts-filter' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'filter_item_gap_content',
			[
				'label'      => esc_html__( 'Content Gap','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .posts-filter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'filter_item_border',
				'selector' => '{{WRAPPER}} .posts-filter li',
			]
		);

		$this->add_responsive_control(
			'filter_item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .posts-filter li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'filter_typography',
				'selector' => '{{WRAPPER}} .posts-filter li',
			]
		);

		$this->start_controls_tabs(
			'filter_style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'filter_text_color',
			[
				'label'     => esc_html__( 'Text Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .posts-filter li' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'tab_item_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .posts-filter li',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Active','animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'filter_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .posts-filter li.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'tab_item_hover_active_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .posts-filter li.active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function get_current_page() {
		if ( '' === $this->get_settings_for_display( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	protected function register_audio_gallery_controls() {
		$this->start_controls_section(
			'style_audio_gallery',
			[
				'label'     => __( 'Audio Gallery','animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'post_layout' => 'layout-audio' ],
			]
		);

		$this->add_control(
			'audio_play_icon',
			[
				'label'       => esc_html__( 'Play Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-play',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'audio_pause_icon',
			[
				'label'       => esc_html__( 'Pause Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-pause',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_responsive_control(
			'duration_icon_gap',
			[
				'label'      => esc_html__( 'Gap','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .audio-duration-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'duration_icon_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .audio-thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Duration Heading
		$this->add_control(
			'duration_heading',
			[
				'label'     => esc_html__( 'Duration','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'a_duration_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .audio-duration' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'a_duration_typo',
				'selector' => '{{WRAPPER}} .audio-duration',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'a_duration_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .audio-duration',
			]
		);

		$this->add_responsive_control(
			'a_duration_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .audio-duration' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Icon Heading
		$this->add_control(
			'a_icon_heading',
			[
				'label'     => esc_html__( 'Icon','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'a_icon_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .audio-icon' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'a_icon_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .audio-icon',
			]
		);

		$this->add_responsive_control(
			'a_icon_size',
			[
				'label'      => esc_html__( 'Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .audio-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'a_icon_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .audio-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();
	}

	protected function register_post_rating_controls() {
		$this->start_controls_section(
			'section_post_rating',
			[
				'label'     => esc_html__( 'Rating', 'animation-addons-for-elementor-pro' ),
				'condition' => [ 'show_rating' => 'yes' ],
			]
		);

		$this->add_control(
			'post_rating_style',
			[
				'label'   => esc_html__( 'Style', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'circle'  => esc_html__( 'Circle', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'rating_icon',
			[
				'label'       => esc_html__( 'Rating Icon','animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'style_post_rating',
			[
				'label'     => esc_html__( 'Rating', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_rating' => 'yes' ],
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rating, {{WRAPPER}} .no-rating' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'rating_typo',
				'selector' => '{{WRAPPER}} .rating, {{WRAPPER}} .no-rating',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'rating_border',
				'selector'  => '{{WRAPPER}} .rating',
				'condition' => [ 'post_rating_style' => 'circle' ]
			]
		);

		$this->add_responsive_control(
			'rating_b_radius',
			[
				'label'      => esc_html__( 'Border Radius','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rating' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ 'post_rating_style' => 'circle' ]
			]
		);

		$this->add_responsive_control(
			'rating_padding',
			[
				'label'      => esc_html__( 'Padding','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'circle_size',
			[
				'label'      => esc_html__( 'Circle Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rating' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'post_rating_style' => 'circle' ]
			]
		);

		$this->add_responsive_control(
			'rating_icon_gap',
			[
				'label'      => esc_html__( 'Gap','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rating' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'post_rating_style' => 'circle' ]
			]
		);

		$this->add_control(
			'Rating_icon_heading',
			[
				'label'     => esc_html__( 'Icon','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'rating_icon_color',
			[
				'label'     => esc_html__( 'Color','animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rating .icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_icon_size',
			[
				'label'      => esc_html__( 'Icon Size','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rating .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_icon_margin',
			[
				'label'      => esc_html__( 'Margin','animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rating .icon' => 'margin: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'post_rating_style' => 'default' ]
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		//wrapper class
		$this->add_render_attribute( 'wrapper', 'class', [
			'wcf__posts-pro',
			$settings['post_layout'],
		] );

		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>><?php
		$this->render_filter( $settings );

		$this->render_loop_header();

		$i = 1;
		while ( $query->have_posts() ) {
			$query->the_post();

			if ( 'layout-normal' === $settings['post_layout'] ) {
				$this->render_post_layout_normal( $settings, $i );
			} elseif ( 'layout-overlay-2' === $settings['post_layout'] ) {
				$this->render_post_layout_overlay_2( $settings, $i );
			} elseif ( 'layout-audio' === $settings['post_layout'] ) {
				$this->render_post_layout_audio( $settings, $i );
			} else {
				$this->render_post_layout_side_overlay( $settings, $i );
			}

			$i ++;
		}

		$this->render_loop_footer();

		?></div><?php

		wp_reset_postdata();
	}

	protected function render_filter( $settings ) {
		if ( 'yes' != $settings['enable_filter'] ) {
			return;
		}
		?>
        <ul class="posts-filter">
			<?php if ( ! empty( $settings['filter_all_label'] ) ): ?>
                <li data-filter="all" class="active">
					<?php echo esc_html( $settings['filter_all_label'] ); ?>

                </li>
			<?php endif; ?>
			<?php
			$categories = get_categories( array(
				'taxonomy'   => 'category',
				'orderby'    => 'name',
				'parent'     => 0,
				'hide_empty' => 1,
			) );

			foreach ( $categories as $item ): ?>
                <li data-filter="category-<?php echo esc_attr( $item->slug ); ?>">
					<?php echo esc_html( $item->name ) ?>
                </li>
			<?php endforeach; ?>
        </ul>
		<?php
	}

	protected function render_loop_header() {
		?>
        <div class="wcf-posts">
		<?php
	}

	protected function render_loop_footer() {
		?></div><?php
		$this->render_pagination();
	}

	protected function render_post_layout_normal( $settings, $i ) {
		$post_classes        = [ 'wcf-post' ];
		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );
		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'item-masonry';
		}
		?>
        <article <?php post_class( $post_classes ); ?>>

			<?php
			foreach ( $settings['post_layout_two'] as $item ) {
				if ( 'thumb' === $item['post_item'] ) {
					$this->render_thumbnail( $settings );
				}

				if ( 'rating' === $item['post_item'] ) {
					$this->render_post_rating( $settings );
				}

				if ( 'taxonomy' === $item['post_item'] ) {
					$this->render_post_taxonomy();
				}

				if ( 'title' === $item['post_item'] ) {
					$this->render_title();
				}

				if ( 'excerpt' === $item['post_item'] ) {
					$this->render_excerpt();
				}

				if ( 'meta' === $item['post_item'] ) {
					$this->render_meta_data();
				}

				if ( 'read_more' === $item['post_item'] ) {
					$this->render_read_more();
				}
			}
			?>
        </article>
		<?php
	}

	protected function render_post_layout_overlay_2( $settings, $i ) {

		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );
		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'item-masonry';
		}
		?>

        <article class="layout-overlay-2">
			<?php $this->render_thumbnail( $settings ); ?>
            <div class="content">
				<?php
				foreach ( $settings['post_layout_one'] as $item ) {
					if ( 'rating' === $item['post_item'] ) {
						$this->render_post_rating( $settings );
					}

					if ( 'taxonomy' === $item['post_item'] ) {
						$this->render_post_taxonomy();
					}

					if ( 'title' === $item['post_item'] ) {
						$this->render_title();
					}

					if ( 'excerpt' === $item['post_item'] ) {
						$this->render_excerpt();
					}

					if ( 'meta' === $item['post_item'] ) {
						$this->render_meta_data();
					}

					if ( 'read_more' === $item['post_item'] ) {
						$this->render_read_more();
					}
				}
				?>
            </div>
        </article>
		<?php
	}

	protected function render_post_layout_side_overlay( $settings, $i ) {
		$post_classes        = [ 'wcf-post' ];
		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );
		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'item-masonry';
		}
		?>
        <article <?php post_class( $post_classes ); ?>>
			<?php $this->render_thumbnail( $settings ); ?>
            <div class="content">
				<?php
				foreach ( $settings['post_layout_one'] as $item ) {
					if ( 'rating' === $item['post_item'] ) {
						$this->render_post_rating( $settings );
					}

					if ( 'taxonomy' === $item['post_item'] ) {
						$this->render_post_taxonomy();
					}

					if ( 'title' === $item['post_item'] ) {
						$this->render_title();
					}

					if ( 'excerpt' === $item['post_item'] ) {
						$this->render_excerpt();
					}

					if ( 'meta' === $item['post_item'] ) {
						$this->render_meta_data();
					}

					if ( 'read_more' === $item['post_item'] ) {
						$this->render_read_more();
					}
				}
				?>
            </div>
        </article>
		<?php
	}

	protected function render_post_layout_audio( $settings, $i ) {
		$post_classes        = [ 'wcf-post' ];
		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );
		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'item-masonry';
		}

		$format = get_post_format();
		if ( 'audio' === $format ) {
			$link = get_post_meta( get_the_ID(), '_audio_url', true );
		}
		?>
        <article <?php post_class( $post_classes ); ?>>
            <div class="audio-thumb">
                <div class="audio-duration-wrapper" data-link="<?php echo esc_url( $link ); ?>">
                    <div class="audio-duration">Loading...</div>
                    <div class="audio-icon">
                        <span class="play-icon">
                            <?php Icons_Manager::render_icon( $settings['audio_play_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                        <span class="pause-icon">
                            <?php Icons_Manager::render_icon( $settings['audio_pause_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                    </div>
                </div>
            </div>
			<?php
			foreach ( $settings['post_layout_audio'] as $item ) {
				if ( 'rating' === $item['post_item'] ) {
					$this->render_post_rating( $settings );
				}

				if ( 'taxonomy' === $item['post_item'] ) {
					$this->render_post_taxonomy();
				}

				if ( 'title' === $item['post_item'] ) {
					$this->render_title();
				}

				if ( 'excerpt' === $item['post_item'] ) {
					$this->render_excerpt();
				}

				if ( 'meta' === $item['post_item'] ) {
					$this->render_meta_data();
				}

				if ( 'read_more' === $item['post_item'] ) {
					$this->render_read_more();
				}
			}
			?>
        </article>
		<?php
	}

	protected function render_post_rating( $settings ) {
		if ( ! $this->get_settings( 'show_rating' ) ) {
			return;
		}

		$post_id = get_the_ID();

		$ratings = get_posts( [
			'post_type'  => 'aaeaddon_post_rating',
			'meta_query' => [
				[
					'key'   => 'post_id',
					'value' => $post_id,
				]
			]
		] );

		$total_ratings = count( $ratings );
		$total_stars   = 0;

		foreach ( $ratings as $rating ) {
			$total_stars += get_post_meta( $rating->ID, 'rating', true );
		}
		?>
        <div class="post-rating <?php echo $settings['post_rating_style']; ?>">
			<?php
			if ( $total_ratings > 0 ) {
				$average_rating = round( $total_stars / $total_ratings, 1 );
				?>
                <div class="rating">
					<?php
					if ( 'circle' === $settings['post_rating_style'] ) {
						?>
                        <div class="icon">
							<?php Icons_Manager::render_icon( $settings['rating_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </div>
                        <p>
                            <span><?php echo $average_rating; ?></span>/<?php echo esc_html__( '5', 'animation-addons-for-elementor-pro' ); ?>
                        </p>
						<?php
					} else {
						?>
						<?php echo $average_rating; ?>
                        <span class="icon">
                            <?php Icons_Manager::render_icon( $settings['rating_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </span>
                        (<?php echo $total_ratings; ?>)
						<?php
					}
					?>
                </div>
				<?php
			} else {
				?>
                <p class="no-rating"><?php echo esc_html__( 'No ratings yet.', 'animation-addons-for-elementor-pro' ); ?></p>
				<?php
			}
			?>
        </div>
		<?php
	}
}
