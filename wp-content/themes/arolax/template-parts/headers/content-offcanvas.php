  <?php
  
   $light_logo               = AROLAX_IMG . '/logo-dark.svg';
   $offcanvas_logo           = arolax_option( 'offcanvas_logo' , $light_logo );
   $offcanvas_content        = arolax_option( 'offcanvas_content' );
   $offcanvas_gallery_enable = arolax_option( 'offcanvas_gallery_enable' );
    
   $offcanvas_gallery        = arolax_option( 'offcanvas_gallery' );
   $offcanvas_gallery_title  = arolax_option( 'offcanvas_gallery_title' );
   $social_link              = arolax_option( 'social_link' );
   $offcanvas_social_heading = arolax_option( 'offcanvas_social_heading' );
   $offcanvas_social         = arolax_option( 'offcanvas_social' );
   $target                   = isset($args['target']) ? $args['target'] : 'offcanvasOne';
   $direction_align          = isset($args['align']) ? $args['align'] : 'offcanvas-end';
   
  ?>
  <!-- Offcanves start -->
  <div class="offcanvas__area wcf-theme-default-offcanvas">
    <div class="offcanvas <?php echo esc_attr($direction_align); ?>" tabindex="-1" id="<?php echo esc_attr($target); ?>">
      <button class="offcanvas__close" data-bs-dismiss="offcanvas"><i class="icon-wcf-close"></i></button>
      <div class="offcanvas__body">
        <div class="offcanvas__logo">
          <?php  if($offcanvas_logo): ?> 
            <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($offcanvas_logo); ?>" alt="<?php echo esc_attr__('Offcanvas Logo','arolax') ?>"></a>
          <?php endif; ?>
          <?php echo wp_kses_post( wpautop( $offcanvas_content ) ); ?>
        </div>

        <div class="offcanvas__menu-area">
          <div class="offcanvas__menu-wrapper "> 
          <nav class="arolax-mb-mobile-menu" aria-label="<?php esc_attr_e( 'Mobile menu', 'helo' ); ?>">
            <?php get_template_part( 'template-parts/navigations/nav', 'mobile' ); ?>
          </nav>           
          </div>
        </div>
        <?php if ( ! empty( $offcanvas_gallery ) && $offcanvas_gallery_enable) { ?>
        <div class="offcanvas__gallery">
          <h2 class="offcanvas__title"><?php echo esc_html($offcanvas_gallery_title); ?></h2>
          <div class="gallery__items">
            <?php foreach ( $offcanvas_gallery as $gallery_item ) { ?>
            <div class="gallery__item">
              <a href="<?php echo esc_url($gallery_item['url']); ?>"><img src="<?php echo esc_url($gallery_item['image']['thumbnail']); ?>" alt="<?php echo esc_attr__('gallery Image','arolax'); ?>">
                <span><i class="icon-wcf-instragram"></i></span></a>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php } ?>
        <?php if(! empty($offcanvas_social && is_array($social_link)) ){ ?>
          <div class="offcanvas__media">
            <h2 class="offcanvas__title"><?php echo esc_html($offcanvas_social_heading); ?></h2>
            <ul>
            <?php foreach ( $social_link as $social_item ) { ?>
              <li><a target="<?php echo isset( $social_item['opt_new_tab'] ) && $social_item['opt_new_tab'] == 1 ? esc_attr('_blank'): esc_attr('_parent'); ?>" href="<?php echo esc_url($social_item['bookmark_url']); ?>"><i class="<?php echo esc_attr($social_item['bookmark_icon']); ?>"></i></a></li>
            <?php } ?>
           </ul>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- Offcanves end -->