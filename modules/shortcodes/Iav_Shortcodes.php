<?php

// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if

if ( ! class_exists( 'Iav_Shortcodes' ) ) :

    class Iav_Shortcodes {

        protected static $instance = NULL;
    
        public function __construct() {
            add_shortcode ( 'iav_search' , array( $this, 'iav_search_shortcode') );
            //register ajax scripts
            wp_register_script( 'iav-ajax-search' ,
                                get_stylesheet_directory_uri() . '/modules/shortcodes/assets/js/iav-search.js',
                                array( 'jquery' ),
                                null,
                                true
            );
            //register masonry and images loaded
            wp_register_script( 'iav-masonry' ,
                                get_stylesheet_directory_uri() . '/modules/shortcodes/assets/js/masonry.pkgd.min.js',
                                array( 'jquery' ),
                                null,
                                true
            );
            wp_register_script( 'iav-imagesloaded' ,
                                get_stylesheet_directory_uri() . '/modules/shortcodes/assets/js/imagesloaded.pkgd.min.js',
                                array( 'jquery' ),
                                null,
                                true
            );
            //register loading overlay style
            wp_register_style( 'iav-loading-overlay' ,
                                get_stylesheet_directory_uri() . '/modules/shortcodes/assets/css/iavloader.css'
            );
            //register font awesome icons 
            wp_register_style( 'iav-font-awesome' ,
                                get_stylesheet_directory_uri() . '/modules/shortcodes/assets/css/font-awesome-all.min.css'
            );
            //register styles
            wp_register_style( 'iav-search-shortcode' ,
                                get_stylesheet_directory_uri() . '/modules/shortcodes/assets/css/iav-search-shortcode.css'
            );
            //add functions for ajax to use
            add_action( 'wp_ajax_nopriv_search_for_results' , array( $this, 'search_for_results' ) );
            add_action( 'wp_ajax_search_for_results' , array( $this, 'search_for_results' ) );
            
        }
        // remove url for if 
        public static function get_instance() {

            // create an object
            NULL === self::$instance and self::$instance = new self;

            return self::$instance; // return the object
        }

       /*  shortcode [iav_search type="research" category="cat1,cat2,cat3"]
           data-swplive="true" is used for wpsearch live ajax
           will only work searching for one type of 'post_type'

           MUST INCLUDE THIS DIV SOMEWHERE ON PAGE TO INCLUDE RESULTS
           <div id="<?php echo 'resultsid-' . $atts['form_id']; ?>" class="iav-search-results-wrapper">
        */
        
        public function iav_search_shortcode( $atts ) {
            //enqueue loading overlay
            wp_enqueue_style( 'iav-loading-overlay' );
            wp_enqueue_style( 'iav-font-awesome' );
            wp_enqueue_style( 'iav-search-shortcode' );
            
           
            //enqueue ajax js
            wp_enqueue_script( 'iav-ajax-search' );
            $atts = shortcode_atts(
                array(
                    'type' => 'post',
                    'category_filters' => "",
                    'form_id' => get_the_ID(), // if, for some reason, more than 1 shortcodes in one page is used
                    'force_category_slug' => "",
                ),
                $atts,
                'iav_search'
            );
            
            wp_localize_script( 'iav-ajax-search',
                                'iav_ajax_search_obj_' . $atts['form_id'],
                                array(
                                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                                    'post_type' => $atts['type'],
                                    'category' => $atts['force_category_slug'],
                                )
            );
            
            $initial_keyword = ( $_REQUEST["keyword"] ) ? sanitize_text_field( $_REQUEST["keyword"] ) : "";
            $initial_page = ( $_REQUEST["paged"] && is_numeric( $_REQUEST["paged"] ) ) ? $_REQUEST["paged"] : get_query_var('paged');
            $initial_cat = ( $_REQUEST["categ"] ) ? sanitize_text_field( $_REQUEST["categ"] ) : "";
            $initial_tag = ( $_REQUEST["tagged"] ) ? sanitize_text_field( $_REQUEST["tagged"] ) : "";
            $initial_layout = ( $_REQUEST["layout"] ) ? sanitize_text_field( $_REQUEST["layout"] ) : "";
            //output
            $nonce = wp_create_nonce( 'search_' . get_the_ID() );
            ob_start();
            ?>
            <form id="<?php echo 'formid-' . $atts['form_id']; ?>" data-formid="<?php echo $atts['form_id']; ?>" data-searchid= "<?php echo get_the_ID(); ?>" class="iav-search-form" action="" method="post" target="_self" enctype="multipart/form-data" data-nonce="<?php echo $nonce; ?>">
                <div class="iav-search-field">
                    <div class="iav-search-category">
                        <?php
                        $cat_array = explode( ',' , $atts['category_filters'] );
                        if ( ! empty( $cat_array ) ) :
                            echo '<fieldset>';
                            echo '<legend>'. _( 'Narrow Your Search' ) . '</legend>';
                            // category name is passed on shortcode but we need category slug for the id
                            foreach ( $cat_array as $cat_filter ) {
                                $slug = get_term_by( "name", $cat_filter, "category" );
                                
                                if ( $slug ) : 
                                    $checked_category = ( $_REQUEST["category"] &&  $_REQUEST["category"] == $slug->slug ) ? "checked" : "";
                                    echo '<input type="checkbox" name="category" value="' . $slug->slug  . '" id="cat-' . $slug->slug . '" ' .$checked_category. '><label class="cat-' . $slug->slug . '" for="cat-' . $slug->slug . '"><span>' . $cat_filter . '</span></label>';
                                endif;
                            }
                            echo '</fieldset>';
                        endif;
                        ?>
                    </div>
                    <fieldset>
                        <legend><?php _e('Enter Keyword'); ?></legend>
                        <label for="keyword"><?php _e( 'Search' ); ?> </label>
                        <input type="text" name="keyword" id="search-field" value="<?php echo $initial_keyword; ?>" placeholder="START SEARCH HERE" data-swplive="true">
                        <button class="nav-results-button inside-form-button" data-page="1" type="submit" for="<?php echo 'formid-' . $atts['form_id']; ?>" ><?php _e( 'Search' ); ?></button>
                    </fieldset>
                </div>
                <input type="hidden" value="<?php echo $initial_page ?>" name="paged">
                <input type="hidden" value="<?php echo $initial_cat ?>" name="categ">
                <input type="hidden" value="<?php echo $initial_tag ?>" name="tagged">
                <input type="hidden" value="<?php echo $initial_layout ?>" name="layout">
            </form>
            </div>            
            <?php
            return ob_get_clean();
        }

        //do the search and print results
        public function search_for_results() {
            
            $img_placeholder = get_stylesheet_directory_uri() . '/modules/shortcodes/assets/img/placeholder-image.jpg';
             // check the nonce
            if ( check_ajax_referer( 'search_' . $_REQUEST['searchid'], 'nonce', false ) == false ) :
                wp_send_json_error();
            endif;

            // WP_Query arguments
            $query_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $paged = ( isset($_POST['paged']) ) ? sanitize_text_field( $_POST['paged'] ) : $query_paged ;
            $args = array(
                'posts_per_page' => 12,
                's'                      => '',
                'paged' => $paged ,
                'order' => 'DESC',
                'orderby' => 'date',
            );
            $category = ( isset( $_POST['categ'] ) && "" !== $_POST['categ'] ) ? sanitize_text_field( $_POST['categ'] ) : ""; //empty string for category
            $tag = ( isset( $_POST['tagged'] ) && "" !== $_POST['tagged'] ) ? sanitize_text_field( $_POST['tagged'] ) : ""; //empty string for category
            $is_grid = true; //( isset( $_POST['layout'] ) && "grid" == $_POST['layout'] ) ? true : false; //empty string for category
            if( isset( $_POST['layout'] ) && $_POST['layout'] != "" ) {
                if( $_POST['layout'] == "list" ){
                    $is_grid = false;
                }
            }
            if ( isset( $_POST['keyword'] ) && "" !== $_POST['keyword'] ) :
                $args['s'] = sanitize_text_field( $_POST['keyword'] );
                /* $args['tax_query'] = array(
                    'relation' => 'OR',
                    array(
                        'taxonomy'         => 'post_tag',
                        'terms'            => sanitize_text_field( $_POST['keyword'] ),
                        'field'            => 'name',
                        'include_children' => false,
                    ),
                    array(
                        'taxonomy'         => 'post_tag',
                        'terms'            => sanitize_text_field( $_POST['keyword'] ),
                        'field'            => 'name',
                        'operator'         => 'NOT IN',
                        'include_children' => false,
                    ),
                ); */
            endif;
            if ( isset( $_POST['post_type'] ) && "" !== $_POST['post_type'] ) :
                $args['post_type'][] = sanitize_text_field( $_POST['post_type'] );
            endif;
            /* if ( isset( $_POST['category'] ) && !empty($_POST['category']) ):
                $category = implode( '+' , $_POST['category'] );
            endif;  */
            if ( isset( $_POST['category'] ) && "" !== $_POST['category'] ):
                $category = sanitize_text_field(  $_POST['category'] );
            endif; 
            //force_cat is useless for now due to client changes request
            if ( isset( $_POST['force_cat'] ) && "" != $_POST['force_cat'] ):
                $category = ($category == "" ) ? $_POST['force_cat'] : $_POST['force_cat'] . "+" . $category;
            endif;
            if ( "" != $category ) :
                $args["category_name"] = sanitize_text_field($category);
            endif;
            if ( "" != $tag ) :
                $args["tag"] = sanitize_text_field($tag);
            endif;
            // The Query
            //$query = new WP_Query( $args ); // use query_posts because WP_Query will ignore pagination

            add_filter( 'posts_search', array($this, 'sc_research_filter'), 500, 2 );

            query_posts ($args);
            // The Loop
            //if ( $query->have_posts() ) {
            if ( have_posts() ) {
                echo '<ul class="iav-search-results grid">';
                while ( have_posts() ) {
                    the_post();
                    ob_start();
                    ?>
                    <li class="iav-result-row <?php if( $is_grid ){ echo "grid-item"; }?>">
                        <!-- <article> -->
                            <?php
                            if (  get_the_post_thumbnail_url( get_the_ID() , 'full' ) && $is_grid ) {
                            ?>
                            <div>
                                <a href="<?php echo get_the_permalink(); ?>" rel="nofollow" >
                                <?php the_post_thumbnail('large'); ?>
                                </a>
                            </div>
                            <?php
                            } elseif( $is_grid ) {
                            ?>
                            <div>
                                <a href="<?php echo get_the_permalink(); ?>" rel="nofollow" >
                                    <img src="<?php echo $img_placeholder; ?>" alt="">
                                </a>
                            </div>
                            <?php
                            }
                            ?>
                            <div>
                                <h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title();?></a></h3>
                            </div>
                            <div class="time-holder">
                                <time><?php echo get_the_date(); ?></time>
                            </div>
                            <?php
                            $cat_list = get_the_category();
                            //if(  ! empty( $cat_list ) ) :
                            if(  false ) : // skip this for now
                                foreach ( $cat_list as $cat ) {
                                    ?>
                                    <span class="post-category <?php echo $cat->slug;?>"><?php echo $cat->name; ?></span>
                                    <?php
                                }
                            endif;
                            ?>
                        <!-- </article> -->
                    </li>
                    <?php
                    echo ob_get_clean();
                }
                echo '</ul>';
                echo '<div class="iav-pagination">';
                $page_args = array(
                    'base' => " ", //remove base link because we will handle this via ajax, bad for SEO? maybe
                    'prev_next' => false,
                    'mid_size' => 5
                );
                $links = paginate_links($page_args);
                $links = str_replace('<a ', '<a rel="nofollow" ', $links);
                echo $links;
                echo '</div>';
            } else {
                echo '<span class="notice">Sorry, we can\'t find any result. Try search with another keyword. </span>';
            }
            // Restore original Post Data
            wp_reset_postdata();
            wp_die();
        }

        //include tags when searching  - taken from wp-extended-search
        function sc_research_filter( $search, $wp_query ) {
            global $wpdb;
            
            if ( empty( $search ) || !empty($wp_query->query_vars['suppress_filters']) ) {
                return $search; // skip processing - If no search term in query or suppress_filters is true
            }
            
            $q = $wp_query->query_vars;
            $n = !empty($q['exact']) ? '' : '%';
            $search = $searchand = '';
            
            
            //$terms_relation_type = apply_filters('wp_es_terms_relation_type', NULL);
            /* 
            if (!in_array($terms_relation_type, array('AND', 'OR'), TRUE)) {
                $terms_relation_type = (intval($this->WP_ES_settings['terms_relation']) === 2) ? 'OR' : 'AND';
            }
             */
            foreach ((array)$q['search_terms'] as $term ) {
                
                $term = $n . $wpdb->esc_like( $term ) . $n;

                /* change query as per plugin settings */
                $OR = '';
                //if (!empty($this->WP_ES_settings)) {
                if ( true ) {
                    $search .= "{$searchand} (";
                    
                    // if post title search is enabled
                    //if (!empty($this->WP_ES_settings['title'])) {
                    if ( true ) {
                        $search .= $wpdb->prepare("($wpdb->posts.post_title LIKE '%s')", $term);
                        $OR = ' OR ';
                    }
                    
                    //if content search is enabled
                    //if (!empty($this->WP_ES_settings['content'])) {
                    if ( true ) {
                        $search .= $OR;
                        $search .= $wpdb->prepare("($wpdb->posts.post_content LIKE '%s')", $term);
                        $OR = ' OR ';
                    }
                    
                    //if excerpt search is enabled
                    //if (!empty($this->WP_ES_settings['excerpt'])) {
                    if ( true ) {
                        $search .= $OR;
                        $search .= $wpdb->prepare("($wpdb->posts.post_excerpt LIKE '%s')", $term);
                        $OR = ' OR ';
                    }

                    // if post meta search is enabled
                    //if (!empty($this->WP_ES_settings['meta_keys'])) {
                    if ( false ) {
                        $meta_key_OR = '';

                        foreach ($this->WP_ES_settings['meta_keys'] as $key_slug) {
                            $search .= $OR;
                            $search .= $wpdb->prepare("$meta_key_OR (espm.meta_key = '%s' AND espm.meta_value LIKE '%s')", $key_slug, $term);
                            $OR = '';
                            $meta_key_OR = ' OR ';
                        }
                        
                        $OR = ' OR ';
                    }
                    
                    // if taxonomies search is enabled
                    //if (!empty($this->WP_ES_settings['taxonomies'])) {
                    $include_search = array( 'post_tag' );
                    if ( true ) {
                        $tax_OR = '';
                        
                        foreach ($include_search as $tax) {
                            $search .= $OR;
                            $search .= $wpdb->prepare("$tax_OR (estt.taxonomy = '%s' AND est.name LIKE '%s')", $tax, $term);
                            $OR = '';
                            $tax_OR = ' OR ';
                        }
                        
                        $OR = ' OR ';
                    }
                    
                    // If authors search is enabled
                    //if (!empty($this->WP_ES_settings['authors'])) {
                    if ( false ) {
                        $search .= $OR;
                        $search .= $wpdb->prepare("(esusers.display_name LIKE '%s')", $term);
                    }
                    
                    $search .= ")";
                } else {
                    // If plugin settings not available return the default query
                    $search .= $wpdb->prepare("{$searchand} (($wpdb->posts.post_title LIKE '%s') OR ($wpdb->posts.post_content LIKE '%s') OR ($wpdb->posts.post_excerpt LIKE '%s'))", $term, $term, $term);
                }

                $searchand = " $terms_relation_type ";
            }

            if ( ! empty( $search ) ) {
                $search = " AND ({$search}) ";
                if ( ! is_user_logged_in() )
                    $search .= " AND ($wpdb->posts.post_password = '') ";
            }
            
            /* Join Table */
            add_filter('posts_join_request', array($this, 'wp_es_join_table'));

            /* Request distinct results */
            add_filter('posts_distinct_request', array($this, 'WP_ES_distinct'));
            
            /**
             * Filter search query return by plugin
             * @since 1.0.1
             * @param string $search SQL query
             * @param object $wp_query global wp_query object
             */
            //return apply_filters('wpes_posts_search', $search, $wp_query); // phew :P All done, Now return everything to wp.
            return $search;
        }

        public function wp_es_join_table($join){
            global $wpdb;
            
            //join post meta table
           /*  if (!empty($this->WP_ES_settings['meta_keys'])) {
                $join .= " LEFT JOIN $wpdb->postmeta espm ON ($wpdb->posts.ID = espm.post_id) ";
            } */
            
            //join taxonomies table
            //if (!empty($this->WP_ES_settings['taxonomies'])) {
            if ( true ) {
                $join .= " LEFT JOIN $wpdb->term_relationships estr ON ($wpdb->posts.ID = estr.object_id) ";
                $join .= " LEFT JOIN $wpdb->term_taxonomy estt ON (estr.term_taxonomy_id = estt.term_taxonomy_id) ";
                $join .= " LEFT JOIN $wpdb->terms est ON (estt.term_id = est.term_id) ";
            }
            
            // Joint the users table
            /* if (!empty($this->WP_ES_settings['authors'])) {
                $join .= " LEFT JOIN $wpdb->users esusers ON ($wpdb->posts.post_author = esusers.ID) ";
            }
             */
            return $join;
        }

        public function WP_ES_distinct($distinct) {
            $distinct = 'DISTINCT';
            return $distinct;
        }        
        

    }    
    
    new Iav_Shortcodes();

endif;