<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_ACF_Text extends \Elementor\Core\DynamicTags\Tag {
    use CustomPostIdTrait;    
    public function get_name() {
        return 'aae-acf-text';
    }

    public function get_title() {
        return esc_html__( 'ACF Text Field', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae' ];
    }

    public function get_categories(): array {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function register_controls() {
        $tax = $this->get_cpt_taxonomies();
        // Field Key Dropdown       
        if(!empty($tax)){
            $this->add_control(
                'field_key',
                [
                    'label' => __('Field Key', 'animation-addons-for-elementor-pro'),
                    'type' => \Elementor\Controls_Manager::TEXT,                   
                    'default' => '',
                ]
            );
            $this->add_control(
                'taxonomy',
                [
                    'label' => __('Taxonomy', 'animation-addons-for-elementor-pro'),
                    'type' => \Elementor\Controls_Manager::SELECT2, // SELECT2 for search functionality.
                    'options' => $tax,               
                    'default' => '',
                ]
            );
        }else{
            $options = $this->get_acf_fields_options();            
            if(!empty($options)){            
                $this->add_control(
                    'field_key',
                    [
                        'label' => __( 'Field Key', 'animation-addons-for-elementor-pro' ),
                        'type' => \Elementor\Controls_Manager::SELECT2, // SELECT2 for search functionality.
                        'options' => $options,
                        'description' => __( 'Search and select an ACF field.', 'animation-addons-for-elementor-pro' ),
                    ]
                );
            }else{
                $this->add_control(
                    'field_key',
                    [
                        'label' => __('Field Key', 'animation-addons-for-elementor-pro'),
                        'type' => \Elementor\Controls_Manager::TEXT,                   
                        'default' => '',
                    ]
                );
            }
            
           
        }
    }

    public function render() {
    
        if ( ! function_exists( 'get_field' ) || ! function_exists( 'get_field_objects' ) ) {
            echo esc_html__( 'ACF plugin is not active.', 'animation-addons-for-elementor-pro' );
            return;
        }

        $field_key = $this->get_settings( 'field_key' );   
        $taxonomy = $this->get_settings( 'taxonomy' );  
      
        if(!is_null($taxonomy) && $taxonomy !=''){
            $this->tax = $taxonomy;           
        }
       
        if ( ! $field_key ) {
            if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
                echo esc_html__( 'Field key is not selected.', 'animation-addons-for-elementor-pro' );
            }
            return;
        }
        
        $post_id = $this->get_custom_id();
     
        $value = get_field( $field_key , $post_id);

        if ( is_array( $value ) ) {
            $value = implode( ', ', $value );
        }
        
        $value = apply_filters( 'aae_acf_field_value', $value, $field_key );
        echo $value;
    }

    private function get_acf_fields_options() {
    
        if ( ! function_exists( 'get_field_objects' ) ) {
            return [];
        }        
               
        $post_id = $this->get_custom_id();      
     
        $fields = $post_id ? get_field_objects( $post_id ) : [];
        $options = [];
      
        if ( $fields ) {
            foreach ( $fields as $key => $field ) {
                if ( in_array( $field['type'], $this->get_supported_fields(), true ) ) {
                    $options[ $key ] = sprintf( '%s (%s)', $field['label'], $field['type'] );
                }
            }
        }
        // Include Global ACF Fields (Options Page).
        $global_fields = get_field_objects( 'options' );
        if ( $global_fields ) {
            foreach ( $global_fields as $key => $field ) {
                if ( in_array( $field['type'], $this->get_supported_fields(), true ) ) {
                    $options[ $key ] = sprintf( '[Global] %s (%s)', $field['label'], $field['type'] );
                }
            }
        }

        return $options;
    }
    
    public function get_supported_fields() {
        return [
            'text',
            'textarea',
            'number',
            'email',
            'password',
            'wysiwyg',
            'select',
            'checkbox',
            'radio',
            'true_false',

            // Pro          
            'oembed',
            'google_map',
            'date_picker',
            'time_picker',
            'date_time_picker',
            'color_picker',
        ];
    }
}
