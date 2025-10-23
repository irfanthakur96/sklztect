<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use WCF_ADDONS\WCF_Post_Query_Trait;
use WCF_ADDONS\WCF_Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

/**
 * Portfolio
 *
 * Elementor widget for Posts.
 *
 * @since 1.0.0
 */
class Portfolio extends Widget_Base {

	use WCF_Post_Query_Trait;
	use  WCF_Button_Trait;

	/**
	 * @var \WP_Query
	 */
	protected $query = null;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wcf--portfolio';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Portfolio', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wcf eicon-gallery-grid';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'weal-coder-addon' ];
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
		return [ 'wcf--portfolio' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'wcf--portfolio',
			'wcf--button'
		);
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
		//layout
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'element_list',
			[
				'label'   => esc_html__( 'Style', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'One', 'animation-addons-for-elementor-pro' ),
					'2' => esc_html__( 'Two', 'animation-addons-for-elementor-pro' ),
					'3' => esc_html__( 'Three', 'animation-addons-for-elementor-pro' ),
					'4' => esc_html__( 'Four', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'animation-addons-for-elementor-pro' ),
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
				'label'   => esc_html__( 'Posts Per Page', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'exclude'      => [ 'custom' ],
				'default'      => 'medium',
				'prefix_class' => 'elementor-portfolio--thumbnail-size-',
			]
		);

		$this->add_control(
			'masonry',
			[
				'label'       => esc_html__( 'Masonry', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'label_on'    => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'columns!'      => '1',
					'element_list!' => '4',
				],
				'render_type' => 'template',
				'selectors'   => [
					'{{WRAPPER}} .wcf-posts' => 'grid-auto-flow: dense;',
				],
			]
		);

		$this->add_responsive_control(
			'item_ratio',
			[
				'label'      => esc_html__( 'Item Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 450,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-posts .thumb img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'element_list!' => '4' ]
			]
		);

		$this->add_control(
			'masonry_large',
			[
				'label'       => esc_html__( 'Masonry Large Items', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '2, 6, 11', 'animation-addons-for-elementor-pro' ),
				'description' => esc_html__( 'Give the item sequence number with Coma separated.', 'animation-addons-for-elementor-pro' ),
				'ai'          => [
					'active' => false,
				],
				'condition'   => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'     => esc_html__( 'Show Title', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'label_on'  => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'title_length',
			[
				'label'     => esc_html__( 'Title Length', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'animation-addons-for-elementor-pro' ),
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
			'show_meta',
			[
				'label'     => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_taxonomy',
			[
				'label'       => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => 'category',
				'options'     => $this->get_taxonomies(),
				'condition'   => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_date',
			[
				'label'     => esc_html__( 'Date', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
				'condition'   => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_taxonomy',
			[
				'label'     => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
				'condition'   => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		//query
		$this->register_query_controls();

		//layout style
		$this->register_design_layout_controls();

		//filter
		$this->register_filter_section_controls();

		//pagination
		$this->register_pagination_section_controls();

		// Content style
		$this->start_controls_section(
			'section_style_testimonial_content',
			[
				'label'      => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'show_title',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'show_meta',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .title, {{WRAPPER}} .title a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'selector'  => '{{WRAPPER}} .title, {{WRAPPER}} .title a',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_meta_style',
			[
				'label'     => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .meta' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'meta_typography',
				'selector'  => '{{WRAPPER}} .meta',
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'meta_spacing',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_design_layout_controls() {
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'      => esc_html__( 'Columns Gap', 'animation-addons-for-elementor-pro' ),
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
				'label'      => esc_html__( 'Rows Gap', 'animation-addons-for-elementor-pro' ),
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

		//hover effect
		$this->add_control(
			'el_hover_effects',
			[
				'label'        => esc_html__( 'Hover Effect', 'animation-addons-for-elementor-pro' ),
				'description'  => esc_html__( 'This effect will work only on image tags.', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'effect-zoom-in',
				'options'      => [
					''            => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'effect-zoom-in' => esc_html__( 'Zoom In', 'animation-addons-for-elementor-pro' ),
					'effect-zoom-out'  => esc_html__( 'Zoom Out', 'animation-addons-for-elementor-pro' ),
					'left-move'   => esc_html__( 'Left Move', 'animation-addons-for-elementor-pro' ),
					'right-move'  => esc_html__( 'Right Move', 'animation-addons-for-elementor-pro' ),
				],
				'prefix_class' => 'wcf--image-',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
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
				'selectors' => [
					'{{WRAPPER}} .wcf-post' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .content'  => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}


	public function register_pagination_section_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => esc_html__( 'Pagination', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'   => esc_html__( 'Pagination', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''                      => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'numbers_and_prev_next' => esc_html__( 'Numbers', 'animation-addons-for-elementor-pro' ) . ' + ' . esc_html__( 'Previous/Next', 'animation-addons-for-elementor-pro' ),
					'load_more'             => esc_html__( 'Load More', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'pagination_page_limit',
			[
				'label'     => esc_html__( 'Page Limit', 'animation-addons-for-elementor-pro' ),
				'default'   => '5',
				'condition' => [
					'pagination_type!' => [
						'load_more',
						'',
					],
				],
			]
		);

		$this->add_control(
			'pagination_numbers_shorten',
			[
				'label'     => esc_html__( 'Shorten', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => [
					'pagination_type' => [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'navigation_previous_icon',
			[
				'label'            => esc_html__( 'Previous Arrow Icon', 'animation-addons-for-elementor-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
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
				'recommended'      => [
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
				'condition'        => [
					'pagination_type' => [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'navigation_next_icon',
			[
				'label'            => esc_html__( 'Next Arrow Icon', 'animation-addons-for-elementor-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
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
				'recommended'      => [
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
				'condition'        => [
					'pagination_type' => [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .pf-pagination' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .pf-load-more'  => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'pagination_type!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing_top',
			[
				'label'      => esc_html__( 'Spacing', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 70,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pf-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pf-load-more'  => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'pagination_type!' => '',
				],
			]
		);

		$this->end_controls_section();

		//Load More
		$this->start_controls_section(
			'section_load_more',
			[
				'label'     => esc_html__( 'Load More', 'animation-addons-for-elementor-pro' ),
				'condition' => [
					'pagination_type' => 'load_more',
				],
			]
		);

		$this->register_button_content_controls( [ 'btn_text' => 'Load More Works' ], [ 'btn_link' => false ] );

		$this->end_controls_section();

		// Pagination style controls for prev/next and numbers pagination.
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label'     => esc_html__( 'Pagination', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination_type' => 'numbers_and_prev_next',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .pf-pagination .page-numbers',
			]
		);

		$this->add_control(
			'pagination_color_heading',
			[
				'label'     => esc_html__( 'Colors', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'pagination_colors' );

		$this->start_controls_tab(
			'pagination_color_normal',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pf-pagination .page-numbers' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_hover',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'pagination_hover_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pf-pagination .page-numbers:not(.dots):hover, {{WRAPPER}} .pf-pagination .page-numbers.current' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pf-pagination .page-numbers.current, {{WRAPPER}} .pf-pagination .page-numbers:not(.prev, .next, .dots):hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label'      => esc_html__( 'Space Between', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .pf-pagination' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Pagination style controls for on-load pagination with type on-click
		$this->start_controls_section(
			'section_load_more_style',
			[
				'label'     => esc_html__( 'Pagination', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination_type' => 'load_more',
				],
			]
		);

		$this->add_control(
			'heading_load_more_style_button',
			[
				'label' => esc_html__( 'Load More', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->register_button_style_controls();

		$this->end_controls_section();
	}

	public function register_filter_section_controls() {

		do_action('wcf_addon_pro_portfolio_filter', $this);
	}

	/// Query Related
	protected function get_posts_tags() {
		$taxonomy = $this->get_settings('taxonomy');
		if(isset($this->query->posts)){
			foreach ( $this->query->posts as $post ) {
				if ( ! $taxonomy ) {
					$post->tags = [];	
					continue;
				}
	
				$tags = wp_get_post_terms( $post->ID, $taxonomy );
	
				$tags_slugs = [];
	
				foreach ( $tags as $tag ) {
					if(isset($tag->term_id)){
						$tags_slugs[ $tag->term_id ] = $tag;		
					}					
				}
	
				$post->tags = $tags_slugs;
			}
		}
	
	}

	public function get_current_page() {
		if ( '' === $this->get_settings_for_display( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	public static function trim_words( $text, $length ) {
		if ( $length && str_word_count( $text ) > $length ) {
			$text = explode( ' ', $text, $length + 1 );
			unset( $text[ $length ] );
			$text = implode( ' ', $text );
		}

		return $text;
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
		$wrap_classes = [
			'wcf__portfolio',
			'style-' . $settings['element_list'],
		];
		if ( $this->get_settings( 'show_filter_bar' ) ) {
			$wrap_classes[] = 'enable-filter';
		}
		$this->add_render_attribute( 'wrapper', 'class', $wrap_classes );
		?><div <?php $this->print_render_attribute_string( 'wrapper' ); ?>><?php

		$this->get_posts_tags();

		$this->render_loop_header();

		$i = 1;
        while ( $query->have_posts() ) {
            $query->the_post();
            $this->render_post( $settings, $i );
            $i ++;
        }

		$this->render_loop_footer();

        ?></div><?php

		wp_reset_postdata();
	}

	protected function render_filter_menu() {
		if(!wcf_addons_get_settings( 'wcf_save_extensions', 'portfolio-filter' )){
		   return;
        }
		$taxonomy = $this->get_settings( 'taxonomy' );

		if ( ! $taxonomy ) {
			return;
		}

		$terms = [];

		foreach ( $this->query->posts as $post ) {
			$terms += $post->tags;
		}

		if ( empty( $terms ) ) {
			return;
		}

		usort( $terms, function ( $a, $b ) {
			return strcmp( $a->name, $b->name );
		} );

		?>
		<div class="filter">
			<button data-filter="all" class="mixitup-control-active">
				<span><?php echo esc_html( $this->query->found_posts ); ?></span>
				<?php echo esc_html__( 'All', 'animation-addons-for-elementor-pro' ); ?>
			</button>
			<?php
			foreach ( $terms as $term ) {
				$term_class = sanitize_html_class( $term->slug, $term->term_id );

				if ( is_numeric( $term_class ) || ! trim( $term_class, '-' ) ) {
					$term_class = $term->term_id;
				}

				// 'post_tag' uses the 'tag' prefix for backward compatibility.
				if ( 'post_tag' === $taxonomy ) {
					$classes = 'tag-' . $term_class;
				} else {
					$classes = sanitize_html_class( $taxonomy . '-' . $term_class, $taxonomy . '-' . $term->term_id );
				}
				?>
				<button data-filter="<?php echo esc_attr( $classes ); ?>">
					<span><?php echo esc_html( $term->count ); ?></span>
					<?php echo esc_html( $term->name ); ?>
				</button>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_loop_header() {
		if ( $this->get_settings( 'show_filter_bar' ) ) {
			$this->render_filter_menu();
		}
		?>
		<div class="wrapper">
		   <div class="wcf-posts">
		<?php
	}

	protected function render_loop_footer() {
		?></div><?php

		$settings = $this->get_settings_for_display();

		// If the skin has no pagination, there's nothing to render in the loop footer.
		if ( ! isset( $settings['pagination_type'] ) ) {
			return;
		}

		if ( '' === $settings['pagination_type'] ) {
			?></div><?php
			return;
		}

		//load more
		if ( 'load_more' === $settings['pagination_type'] ) {
			$current_page = $this->get_current_page();
			$next_page    = intval( $current_page ) + 1;

			$this->add_render_attribute( 'load_more_anchor', [
				'data-e-id'      => $this->get_id(),
				'data-page'      => $current_page,
				'data-max-page'  => $this->get_query()->max_num_pages,
				'data-next-page' => $this->next_page_link( $next_page ),
			] );
			?>

            <div class="load-more-anchor" <?php $this->print_render_attribute_string( 'load_more_anchor' ); ?>></div>
            <div class="pf-load-more">
                <?php $this->render_button( $settings ); ?>
                <span class="load-more-spinner"></span>
            </div>
			<?php
		}

		$page_limit = $this->get_query()->max_num_pages;

		// Page limit control should not effect in load more mode.
		if ( '' !== $settings['pagination_page_limit'] && 'load_more' !== $settings['pagination_type'] ) {
			$page_limit = min( $settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		//number and prev next
		if ( 'numbers_and_prev_next' === $settings['pagination_type'] ) {
			$paginate_args = [
				'current'            => $this->get_current_page(),
				'total'              => $page_limit,
				'prev_next'          => true,
				'prev_text'          => sprintf( '%1$s', $this->render_next_prev_button( 'previous' ) ),
				'next_text'          => sprintf( '%1$s', $this->render_next_prev_button( 'next' ) ),
				'show_all'           => 'yes' !== $settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . esc_html__( 'Page', 'animation-addons-for-elementor-pro' ) . '</span>',
			];
			?>
            <nav class="pf-pagination" aria-label="<?php esc_attr_e( 'Pagination', 'animation-addons-for-elementor-pro' ); ?>">
				<?php echo paginate_links( $paginate_args ); // phpcs:ignore ?>
            </nav>
			<?php
		}

		?></div><?php
	}

	private function render_next_prev_button( $type ) {
		$direction     = 'next' === $type ? 'right' : 'left';
		$icon_settings = $this->get_settings_for_display( 'navigation_' . $type . '_icon' );

		if ( empty( $icon_settings['value'] ) ) {
			$icon_settings = [
				'library' => 'eicons',
				'value'   => 'eicon-chevron-' . $direction,
			];
		}

		return Icons_Manager::try_get_icon_html( $icon_settings, [ 'aria-hidden' => 'true' ] );
	}

	protected function render_thumbnail( $settings ) {
		$settings['thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];
		// PHPCS - `get_permalink` is safe.
		?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="thumb" aria-label="<?php echo esc_attr__('Portfolio Thumbnail', 'animation-addons-for-elementor-pro'); ?>">
			<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumbnail_size' ); ?>
		</a>
		<?php
	}

	protected function render_title() {
		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_settings( 'title_tag' );
		?>
        <<?php Utils::print_validated_html_tag( $tag ); ?> class="title">
		<?php
		global $post;
		// Force the manually-generated Excerpt length as well if the user chose to enable 'apply_to_custom_excerpt'.
		if ( ! empty( $post->post_title ) ) {
			$max_length = (int) $this->get_settings( 'title_length' );
			$title    = $this->trim_words( get_the_title(), $max_length );
			echo wp_kses_post( $title );
		} else {
			the_title();
		}
		?>
        </<?php Utils::print_validated_html_tag( $tag ); ?>>
		<?php
	}

	protected function render_date_by_type( $type = 'publish' ) {
		if ( empty( $this->get_settings( 'show_date' ) ) ){
		    return;
		}
		?>
		<time>
			<?php
			switch ( $type ) :
				case 'modified':
					$date = get_the_modified_date();
					break;
				default:
					$date = get_the_date();
			endswitch;
			/** This filter is documented in wp-includes/general-template.php */
			// PHPCS - The date is safe.
			echo apply_filters( 'the_date', $date, get_option( 'date_format' ), '', '' ); // phpcs:ignore
			?>
		</time>
		<?php
	}

	protected function render_post_category() {

		if ( empty( $this->get_settings( 'show_taxonomy' ) ) ){
			return;
		}

		$taxonomy = $this->get_settings( 'post_taxonomy' );
		if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( empty( $terms[0] ) ) {
			return;
		}

		echo esc_html( $terms[0]->name );

		if ( ! empty( $this->get_settings( 'show_date' ) ) ) {
			echo esc_html( ',&nbsp;' );
		}
	}

	protected function render_post_meta() {
		if ( ! $this->get_settings( 'show_meta' ) ) {
			return;
		}
		?>
		<div class="meta">
			<?php
			$this->render_post_category();
			$this->render_date_by_type();
			?>
		</div>
		<?php
	}

	protected function render_post( $settings, $i ) {
		$post_classes        = [ 'wcf-post' ];
		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );

		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'large';
		}
		?>
        <article <?php post_class( $post_classes ); ?>>
            <div class="post-wrapper">
				<?php $this->render_thumbnail( $settings ); ?>
                <div class="content">
					<?php
					$this->render_title();
					$this->render_post_meta();
					?>
                </div>
            </div>
        </article>
		<?php
	}

}
