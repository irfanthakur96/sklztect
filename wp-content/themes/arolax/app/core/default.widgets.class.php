<?php
namespace arolax\Core;

/**
 * Sidebar and footer. widget 
 */
class Blog_Widgets
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }

    /*
    *    Define the sidebar
    */
    public function widgets_init()
    {
       // Sidebar    
        register_sidebar( array(
                'name'          => esc_html__('Blog widget area', 'arolax'),
                'id'            => 'sidebar-1',
                'description'   => esc_html__('Appears on posts.', 'arolax'),
                'before_widget' => '<div id="%1$s" class="default-sidebar__content default-sidebar__widget widget mb-25 %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
        ) );

        // WooCommerce
	    register_sidebar( array(
		    'name'          => esc_html__('WooCommerce widget area', 'arolax'),
		    'id'            => 'woo',
		    'description'   => esc_html__('Appears on Shop.', 'arolax'),
		    'before_widget' => '<div id="%1$s" class="wcf-woo--widget %2$s">',
		    'after_widget'  => '</div>',
		    'before_title'  => '<h3 class="wcf-woo--title">',
		    'after_title'   => '</h3>',
	    ) );
        
        // Footer       
        register_sidebar(
            array(
                'name'          => esc_html__('Footer One', 'arolax'),
                'id'            => 'footer-one',
                'description'   => esc_html__('Footer one Widget.', 'arolax'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-1-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer Two', 'arolax'),
                'id'            => 'footer-two',
                'description'   => esc_html__('Footer  widget.', 'arolax'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-2-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer Three', 'arolax'),
                'id'            => 'footer-three',
                'description'   => esc_html__('Footer widget.', 'arolax'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-3-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );
        
        register_sidebar(
            array(
                'name'          => esc_html__('Footer Four', 'arolax'),
                'id'            => 'footer-four',
                'description'   => esc_html__('Footer widget.', 'arolax'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-4-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );    
      
    }
}
