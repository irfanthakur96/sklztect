<?php
    $enable_form        = arolax_option( 'enable_search_form',1);  
    $not_found_title    = arolax_option( 'search_not_found_title',esc_html__('Nothing found!','arolax'));  
    $not_found_content  = arolax_option( 'search_not_found_content', esc_html__('It looks like nothing was found here. Maybe try a search?','arolax'));  
?>

<div class="default-search__again-form">
    <div class="default-search-title-wrapper pb-20 text-center">
        <h2 class="default-search-title"><?php echo esc_html($not_found_title); ?></h2>
    </div>
    <?php echo wp_kses_post( wpautop($not_found_content) ); ?>
    <?php if($enable_form): ?>
    <?php get_search_form(); ?>
    <?php endif; ?>
</div>
 