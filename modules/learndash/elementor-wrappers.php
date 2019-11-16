<?php
/* 

Integrate elementor wrappers to learndash templates

*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if


//simple class to add hooks
if(  ! class_exists( 'IAV_ELEMENTOR_WRAPPERS') ) :
class IAV_ELEMENTOR_WRAPPERS
{
    protected static $instance = NULL;
    
    public function __construct() {

        add_filter( 'learndash_template', array( $this, 'replacement_learndash_templates' ) , 90, 5);
        // wrappers to course/lessons - maybe check if elementor is active?
        add_action( 'learndash-course-before', array( $this, 'pre_elementor_wrapper'), 10 );
        add_action( 'learndash-course-after', array( $this, 'post_elementor_wrapper'), 10 );
        add_action( 'learndash-lesson-before', array( $this, 'pre_elementor_wrapper'), 10 );
        add_action( 'learndash-lesson-after', array( $this, 'post_elementor_wrapper'), 10 );

    }
    
    public static function get_instance() {

        // create an object
        NULL === self::$instance and self::$instance = new self;

        return self::$instance; // return the object
    }
    
    //opening wrapper
    function pre_elementor_wrapper(){
        echo '<div class="iav elementor-section elementor-section-boxed">
                <div class="iav elementor-container elementor-column-gap-default">
                    <div class="iav elementor-element-populated">';
    }
    //closing wrapper
    function post_elementor_wrapper(){
        echo '      </div>
                </div>
            </div>';
    }

    //override learndash course template file
    function replacement_learndash_templates( $filepath, $name, $args, $echo, $return_file_path){

        if ( 'course' == $name ){
          $filepath = IAVC_MODS . 'learndash/templates/course.php';
        }
        return $filepath;
        
       }
}

new IAV_ELEMENTOR_WRAPPERS();

endif;