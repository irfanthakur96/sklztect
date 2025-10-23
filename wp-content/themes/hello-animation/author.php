<?php
/**
 * The main template file
 */

	get_header(); 
	get_template_part( 'template-parts/banner/content', 'banner-blog' ); 
	$blog_sidebar               = get_theme_mod('ha_blog_sidebar');
	$blog_sidebar               = $blog_sidebar == ''? 'left-sidebar' : $blog_sidebar;
	$sidebar_cls[$blog_sidebar] = is_active_sidebar('sidebar-1') ? $blog_sidebar : 'no-sidebar';
?>
	<main id="content-area" role="main">
		<div class="default-blog__area pt-150 pb-150">
            <div class="container">
				<div class="default-blog__grid <?php echo esc_attr( implode(' ', $sidebar_cls) ); ?>">
	                <?php 
						if($blog_sidebar == 'left-sidebar'){
							get_sidebar();
						}
					?>
	                <div class="default-blog__item-content">
	          			<?php if ( have_posts() ) : ?>
	          				<?php while ( have_posts() ) : the_post(); ?>
	          					<?php get_template_part( 'template-parts/blog/content'); ?>
	          				<?php endwhile; ?>
	          				<?php else : ?>
	          				<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
	          			<?php endif; ?>
		        		<div class="blog__pagination pt-120">
		                  <?php get_template_part( 'template-parts/blog/paginations/pagination', 'style1' ); ?>
		                </div>
	                </div>
	                <?php 
					if($blog_sidebar == 'right-sidebar'){
						get_sidebar();
					}
					?>
                </div><!--grid -->
            </div><!-- container -->
        </div><!-- default-blog__area -->
    </main><!-- #main-content -->
	<?php get_footer(); ?>
