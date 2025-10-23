<?php

namespace WCFAddonsPro;

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;
use CSF;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

trait WCF_Post_Handler_Trait {

	function wcf_wrap_first_n_words( $text, $n, $class = 'highlight' ) {
		// Split the text into an array of words
		$words = explode( ' ', $text );
		// Check if the text has enough words to wrap
		if ( count( $words ) >= $n ) {
			// Extract the first N words and wrap them in a span tag
			$wrapped_words   = array_slice( $words, 0, $n );
			$remaining_words = array_slice( $words, $n );
			// Create the wrapped portion
			$wrapped = '<span class="' . $class . '">' . implode( ' ', $wrapped_words ) . '</span>';

			// Combine the wrapped portion with the remaining words
			return $wrapped . ' ' . implode( ' ', $remaining_words );
		}

		// If there are fewer words than N, wrap the whole text
		return '<span class="' . $class . '">' . $text . '</span>';
	}

	protected function render_title() {
		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_settings( 'title_tag' );
		?>
        <<?php Utils::print_validated_html_tag( $tag ); ?> class="wcf-post-title">
        <a href="<?php echo esc_url( get_the_permalink() ); ?>">
			<?php
			global $post;
			// Force the manually-generated Excerpt length as well if the user chose to enable 'apply_to_custom_excerpt'.
			if ( ! empty( $post->post_title ) ) {
				$max_length             = (int) $this->get_settings( 'title_length' );
				$title                  = $this->trim_words( get_the_title(), $max_length );
				$highlight_title_length = (int) $this->get_settings( 'highlight_title_length' );

				echo $this->wcf_wrap_first_n_words( $title, $highlight_title_length ); // Wrap first 2 words

			} else {
				the_title();
			}
			?>
        </a>
        </<?php Utils::print_validated_html_tag( $tag ); ?>>
		<?php
	}

	protected function render_excerpt() {
		if ( ! $this->get_settings( 'show_excerpt' ) ) {
			return;
		}
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		?>
        <div class="wcf-post-excerpt">
			<?php
			global $post;
			// Force the manually-generated Excerpt length as well if the user chose to enable 'apply_to_custom_excerpt'.
			if ( empty( $post->post_excerpt ) ) {
				$max_length = (int) $this->get_settings( 'excerpt_length' );
				$excerpt    = apply_filters( 'the_excerpt', get_the_excerpt() );
				$excerpt    = $this->trim_words( $excerpt, $max_length );
				echo wp_kses_post( $excerpt );
			} else {
				the_excerpt();
			}
			?>
        </div>
		<?php
		remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
	}

	protected function render_thumbnail( $settings ) {

		if ( ! $settings['show_thumb'] ) {

			$this->render_audio_video_play_icon();

			return;
		}

		$settings['thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];

		?>
        <div class="thumb <?php echo 'wcf--format-' . get_post_format(); ?>">
			<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumbnail_size' ); ?>
			<?php $this->render_audio_video_play_icon(); ?>
        </div>
		<?php ;
	}

	protected function render_audio_video_play_icon() {
		$format = get_post_format();

		if ( ! $this->get_settings( 'post_format_a_v' ) ) {
			return;
		}

		if ( ! $format ) {
			return;
		}

		$link = '';

		if ( 'audio' === $format ) {
			$link = get_post_meta( get_the_ID(), '_audio_url', true );
		} elseif ( 'video' === $format ) {
			$link = get_post_meta( get_the_ID(), '_video_url', true );
		} elseif ( 'gallery' === $format ) {
			$link = '#';
		}

		if ( empty( $link ) ) {
			return;
		}

		// Youtube Link Checking
		if ( strpos( $link, "https://www.youtube.com/" ) === 0 ) {
			parse_str( parse_url( $link, PHP_URL_QUERY ), $query );

			if ( isset( $query['v'] ) ) {
				$ytVideoId = $query['v'];
				$link      = "https://www.youtube.com/embed/" . $ytVideoId;
			}
		}

		// Vimeo Link Checking
		if ( strpos( $link, "https://vimeo.com/" ) === 0 ) {
			$videoId = str_replace( "https://vimeo.com/", "", $link );
			$link    = "https://player.vimeo.com/video/" . $videoId;
		}
		?>

		<?php if ( 'gallery' === $format ) {
			$gallery_images = get_post_meta( get_the_ID(), '_gallery_images', true );
			$gallery_images = is_array( $gallery_images ) ? $gallery_images : [];
			?>
            <div class="aae-post-gallery-wrapper">
                <div class="swiper gallery-slider">
                    <div class="swiper-wrapper">
						<?php foreach ( $gallery_images as $img ) { ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url( $img ); ?>"
                                     alt="<?php echo esc_attr__( 'Gallery Image', 'animation-addons-for-elementor-pro' ) ?>">
                            </div>
						<?php } ?>
                    </div>

                    <div class="aae-gallery-btn btn-next"> > </div>
                    <div class="aae-gallery-btn btn-prev"> < </div>
                </div>
            </div>
		<?php } ?>

        <button data-src="<?php echo esc_url( $link ); ?>"
                class="wcf-post-popup play <?php echo esc_attr( $format ); ?>">
            <span class="screen-reader-text"><?php echo esc_html__( 'play' , 'animation-addons-for-elementor-pro' ); ?></span>
			<?php
			if ( 'gallery' === $format ) {
				Icons_Manager::render_icon( $this->get_settings( 'gallery_icon' ), [ 'aria-hidden' => 'true' ] );
			} elseif ( 'audio' === $format ) {
				Icons_Manager::render_icon( $this->get_settings( 'audio_icon' ), [ 'aria-hidden' => 'true' ] );
			} else {
				Icons_Manager::render_icon( $this->get_settings( 'audio_video_play' ), [ 'aria-hidden' => 'true' ] );
			}
			?>
        </button>
		<?php
	}

	protected function render_read_more() {
		if ( ! $this->get_settings( 'show_read_more' ) ) {
			return;
		}

		$read_more       = $this->get_settings( 'read_more_text' );
		$aria_label_text = sprintf(
		/* translators: %s: Post title. */
			esc_attr__( 'Read more about %s','animation-addons-for-elementor-pro' ),
			get_the_title()
		);
		?>
        <a class="wcf-post-link <?php echo esc_attr( $this->get_settings( 'icon_align' ) ); ?>"
           href="<?php echo esc_url( get_the_permalink() ); ?>" tabindex="-1">
            <span class="screen-reader-text"><?php echo esc_html( $aria_label_text ); ?></span>
			<?php Icons_Manager::render_icon( $this->get_settings( 'selected_icon' ), [ 'aria-hidden' => 'true' ] ); ?>
			<?php echo wp_kses_post( $read_more ); ?>
        </a>
		<?php
	}


	protected function render_read_more_feature() {
		if ( ! $this->get_settings( 'show_read_more' ) ) {
			return;
		}

		$read_more       = $this->get_settings( 'read_more_text' );
		$aria_label_text = sprintf(
		/* translators: %s: Post title. */
			esc_attr__( 'Read more about %s','animation-addons-for-elementor-pro' ),
			get_the_title()
		);
		?>
        <a class="wcf-post-link feature <?php echo esc_attr( $this->get_settings( 'icon_align' ) ); ?>"
           href="<?php echo esc_url( get_the_permalink() ); ?>" tabindex="-1">
            <span class="screen-reader-text"><?php echo esc_html( $aria_label_text ); ?></span>
			<?php Icons_Manager::render_icon( $this->get_settings( 'selected_icon' ), [ 'aria-hidden' => 'true' ] ); ?>
			<?php echo wp_kses_post( $read_more ); ?>
        </a>
		<?php
	}

	protected function render_author_avatar() {
		if ( ! $this->get_settings( 'show_author' ) ) {
			return;
		}

		?>
        <div class="author">
            <div class="author-img">
				<?php echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), 60 ) ); ?>
            </div>
            <div class="author-bio">
                <p>
					<?php
					esc_html_e( 'Written by ','animation-addons-for-elementor-pro' );
					$this->render_author();
					?>
                </p>
            </div>
        </div>
		<?php
	}

	protected function render_post_taxonomy() {
		if ( ! $this->get_settings( 'show_taxonomy' ) ) {
			return;
		}

		$taxonomy = $this->get_settings( 'post_taxonomy' );
		$limit    = $this->get_settings( 'taxonomy_limit' );

		if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );

		if ( empty( $terms ) ) {
			return;
		}

		?>
        <div class="wcf-post-taxonomy">
			<?php
			foreach ( $terms as $key => $term ) {
				if ( $key == intval( $limit ) ) {
					break;
				}
				printf( '<a class="aae-cat-%3$s" href="%1$s">%2$s</a>',
					esc_url( get_term_link( $term->slug, $taxonomy ) ),
					esc_html( $term->name ),
					esc_html($term->slug)
				);
			}
			?>
        </div>
		<?php
	}

	protected function render_meta_data_block() {
		if ( ! $this->get_settings( 'show_meta' ) ) {
			return;
		}

		/** @var array $metas e.g. [ 'author', 'date', ... ] */
		$metas = $this->get_settings( 'post_meta_data' );
		if ( empty( $metas ) ) {
			return;
		}
		?>
        <div class="wcf-post-meta">
			<?php
			foreach ( $metas as $meta ) {
				if ( 'view' === $meta['post_meta'] ) {
					$this->render_view_count( $meta );
				}

				if ( 'author' === $meta['post_meta'] ) {
					$this->render_author_block( $meta );
				}

				if ( 'date' === $meta['post_meta'] ) {
					$this->render_date_by_type( $meta );
				}

				if ( 'time' === $meta['post_meta'] ) {
					$this->render_time( $meta );
				}

				if ( 'comments' === $meta['post_meta'] ) {
					$this->render_comments( $meta );
				}
			}
			?>
        </div>
		<?php
	}

	protected function render_meta_data() {
		if ( ! $this->get_settings( 'show_meta' ) ) {
			return;
		}

		/** @var array $metas e.g. [ 'author', 'date', ... ] */
		$metas = $this->get_settings( 'post_meta_data' );
		if ( empty( $metas ) ) {
			return;
		}
		?>
        <div class="wcf-post-meta">
			<?php
			foreach ( $metas as $meta ) {
				if ( 'view' === $meta['post_meta'] ) {
					$this->render_view_count( $meta );
				}

				if ( 'author' === $meta['post_meta'] ) {
					$this->render_author( $meta );
				}

				if ( 'date' === $meta['post_meta'] ) {
					$this->render_date_by_type( $meta );
				}

				if ( 'time' === $meta['post_meta'] ) {
					$this->render_time( $meta );
				}

				if ( 'comments' === $meta['post_meta'] ) {
					$this->render_comments( $meta );
				}
			}
			?>
        </div>
		<?php
	}

	protected function render_author( $meta ) {
		?>
        <span class="post-author">
            <?php $this->render_meta_icon( $meta ); ?>
			<?php
			if ( $this->get_settings( 'show_avatar' ) ) {
				echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), 60 ) );
			}
			if ( ! empty( $this->get_settings( 'post_by' ) ) ) { ?>
                <span class="post-by"><?php echo esc_html( $this->get_settings( 'post_by' ) ); ?></span>
				<?php
			}
			?>
        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
			<?php the_author(); ?>
        </a>
		</span>
		<?php
	}

	protected function render_author_block( $meta ) {
		?>
        <div class="post-athor-area">
            <div class="post-author-images">
				<?php
				if ( $this->get_settings( 'show_avatar' ) ) {
					echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), 60 ) );
				}
				?>
            </div>
            <div class="author-date">
				<span class="post-author block">
					<?php $this->render_meta_icon( $meta ); ?>
					<?php
					if ( ! empty( $this->get_settings( 'post_by' ) ) ) { ?>
                        <span class="post-by"><?php echo esc_html( $this->get_settings( 'post_by' ) ); ?></span>
						<?php
					}
					?>
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
					<?php the_author(); ?>
				</a>
				</span>
                <span class="post-date">
					<?php $this->render_meta_icon( $meta ); ?>
					<?php
					$date = get_the_date();
					/** This filter is documented in wp-includes/general-template.php */
					// PHPCS - The date is safe.
					echo apply_filters( 'the_date', $date, get_option( 'date_format' ), '', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</span>
            </div>
        </div>
		<?php
	}

	protected function render_date_by_type( $meta ) {
		?>
        <span class="post-date">
            <?php $this->render_meta_icon( $meta ); ?>
            <?php
            $date = get_the_date();
            /** This filter is documented in wp-includes/general-template.php */
            // PHPCS - The date is safe.
            echo apply_filters( 'the_date', $date, get_option( 'date_format' ), '', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
		</span>
		<?php
	}

	protected function render_time( $meta ) {
		?>
        <span class="post-time">
            <?php $this->render_meta_icon( $meta ); ?>
            <?php the_time(); ?>
		</span>
		<?php
	}

	protected function render_comments( $meta ) {
		?>
        <span class="post-comment">
            <?php $this->render_meta_icon( $meta ); ?>
            <?php comments_number(); ?>
		</span>
		<?php
	}

	protected function render_view_count( $meta ) {
		?>
        <span class="post-views">
            <?php $this->render_meta_icon( $meta ); ?>
            <?php echo esc_html( get_post_meta( get_the_id(), 'wcf_post_views_count', true ) ); ?>
            <?php echo esc_html__( 'Views','animation-addons-for-elementor-pro' ); ?>
        </span>
		<?php
	}

	protected function render_meta_icon( $meta ) {
		if ( ! empty( $meta['meta_icon']['value'] ) ) {
			?>
            <span class="meta-icon">
			    <?php Icons_Manager::render_icon( $meta['meta_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		    </span>
			<?php
		}
	}

	private function render_next_prev_button( $type ) {
		$direction     = 'next' === $type ? 'right' : 'left';
		$icon_settings = $this->get_settings( 'navigation_' . $type . '_icon' );

		if ( empty( $icon_settings['value'] ) ) {
			$icon_settings = [
				'library' => 'eicons',
				'value'   => 'eicon-chevron-' . $direction,
			];
		}

		if ( 'next' === $type ) {
			return esc_html( $type ) . ' ' . Icons_Manager::try_get_icon_html( $icon_settings, [ 'aria-hidden' => 'true' ] );
		} else {
			return Icons_Manager::try_get_icon_html( $icon_settings, [ 'aria-hidden' => 'true' ] ) . ' ' . esc_html( $type );
		}
	}

	protected function render_pagination() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['pagination_type'] ) ) {
			return;
		}

		//load more
		if ( 'load_on_click' === $settings['pagination_type'] || 'infinite_scroll' === $settings['pagination_type'] ) {
			$current_page = $this->get_current_page();
			$next_page    = intval( $current_page ) + 1;

			$this->add_render_attribute( 'load_more_anchor', [
				'data-page'      => $current_page,
				'data-max-page'  => $this->get_query()->max_num_pages,
				'data-next-page' => $this->next_page_link( $next_page ),
			] );

			?>
            <div class="load-more-anchor" <?php $this->print_render_attribute_string( 'load_more_anchor' ); ?>></div>

			<?php if ( 'infinite_scroll' === $settings['pagination_type'] ) { ?>
                <span class="load-more-spinner eicon-animation-spin">
                        <?php Icons_Manager::render_icon( $settings['load_more_spinner'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
			<?php } ?>

            <button class="wcf-post-load-more" data-type="<?php echo esc_attr( $settings['pagination_type'] ) ?>">
				<?php if ( 'load_on_click' === $settings['pagination_type'] ) { ?>
                    <span class="load-more-spinner eicon-animation-spin">
                        <?php Icons_Manager::render_icon( $settings['load_more_spinner'], [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
				<?php } ?>
                <span class="load-more-text">
                    <?php $this->print_unescaped_setting( 'load_more_btn_text' ); ?>
                    <?php Icons_Manager::render_icon( $settings['load_more_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </span>
            </button>
			<?php
			return;
		}

		$page_limit = $this->get_query()->max_num_pages;

		if ( ! empty( $settings['pagination_page_limit'] ) ) {
			$page_limit = min( $settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$has_numbers   = in_array( $settings['pagination_type'], [ 'numbers', 'numbers_and_prev_next' ] );
		$has_prev_next = in_array( $settings['pagination_type'], [ 'prev_next', 'numbers_and_prev_next' ] );

		//number and prev next
		if ( in_array( $settings['pagination_type'], [ 'numbers', 'prev_next', 'numbers_and_prev_next' ] ) ) {

			$links = [];

			if ( $has_numbers ) {
				$paginate_args = [
					'type'               => 'array',
					'current'            => $this->get_current_page(),
					'total'              => $page_limit,
					'prev_next'          => false,
					'show_all'           => 'yes' !== $settings['pagination_numbers_shorten'],
					'before_page_number' => '<span class="elementor-screen-only">' . esc_html__( 'Page', 'animation-addons-for-elementor-pro' ) . '</span>',
				];

				$links = paginate_links( $paginate_args );
			}

			if ( $has_prev_next ) {
				$prev_next = $this->get_posts_nav_link( $page_limit );
				array_unshift( $links, $prev_next['prev'] );
				$links[] = $prev_next['next'];
			}
			?>
            <nav class="wcf-post-pagination"
                 aria-label="<?php esc_attr_e( 'Pagination','animation-addons-for-elementor-pro' ); ?>">
				<?php echo implode( PHP_EOL, $links ); ?>
            </nav>
			<?php

			return;
		}

	}

	public function get_posts_nav_link( $page_limit = null ) {
		$return            = [];
		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';
		$current_page      = $this->get_current_page();

		if ( $current_page > 1 ) {
			$next_page = intval( $current_page ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}
			$return['prev'] = sprintf( $link_template, 'prev', $this->next_page_link( $next_page ), $this->render_next_prev_button( 'prev' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $this->render_next_prev_button( 'prev' ) );
		}

		$next_page = intval( $current_page ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->next_page_link( $next_page ), $this->render_next_prev_button( 'next' ) );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->render_next_prev_button( 'next' ) );
		}

		return $return;
	}

	public function filter_excerpt_length() {
		return (int) $this->get_settings( 'excerpt_length' );
	}

	public static function trim_words( $text, $length ) {
		if ( $length && str_word_count( $text ) > $length ) {
			$text = explode( ' ', $text, $length + 1 );
			unset( $text[ $length ] );
			$text = implode( ' ', $text );
		}

		return $text;
	}
}
