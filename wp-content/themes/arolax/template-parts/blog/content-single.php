
  <?php echo arolax_post_meta(); ?>
  <?php arolax_post_thumbnail() ?>
  
  <div class="default-details__content">
    <h2 class="blog__details_title pt-50"><?php the_title(); ?></h2>
  </div>
  
  <div class="info--post-details">
  <?php  
      if ( is_search() ) {
        the_excerpt();
      } else {
        the_content( esc_html__( 'Continue reading &rarr;', 'arolax' ) );
        arolax_link_pages();
      }        
  ?>
  </div>


    

  
 
  
  
    