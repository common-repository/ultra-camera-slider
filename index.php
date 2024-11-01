<?php
/*
	Plugin Name: Ultra Camera Slider
	Plugin URI: http://www.themeultra.com/ultra-camera-slider-demo/
	Description: This is a Awesome Responsive Slider Plugin. You can use it for various purpose via using this <pre><code>[ultra_camera_slider]</code></pre>  Shortcode and <pre><code> do_shortcode('[ultra_camera_slider]');</code></pre> PHP Code 
	Author: Khurshid Alam Mojumder
	Version: 2.1
	Author URI: http://www.themeultra.com
*/


/* Enqueue Stylesheet */
function ultra_camera_slider_css_main() {
	wp_enqueue_style( 'ultra-camera-style', plugins_url('/css/ultra-camera.css', __FILE__ ) );;
}
add_action('init','ultra_camera_slider_css_main');


/* Enqueue Java Script */
function ultra_camera_slider_plugin_main_js() {
	wp_enqueue_script('jquery');;	
	wp_enqueue_script( 'mobile-customize-js', plugins_url( '/js/jquery.mobile.customized.min.js', __FILE__ ), array('jquery'), false);;
	wp_enqueue_script( 'jquery-easing-js', plugins_url( '/js/jquery.easing.1.3.js', __FILE__ ), array('jquery'), false);;
	wp_enqueue_script( 'camera-js', plugins_url( '/js/camera.min.js', __FILE__ ), array('jquery'), false);;
}
add_action('wp_footer','ultra_camera_slider_plugin_main_js');





/* Active Slider */
 function ultra_camera_active () {
?>
	<script type="text/javascript">
		jQuery(function(){
			
			jQuery('#ultra_slider').camera({
				height: '450px',
				loader: 'bar',
				pagination: false,
				thumbnails: false
			});
		});	
	</script>
<?php
}
add_action('wp_head','ultra_camera_active');





/* Slider Custom Post */
function ultra_slider_post() {
	register_post_type( 'ultra_slider',
		array(
			'labels' => array(
				'name'                => __(  'Ultra Slider'),
				'singular_name'       => __(  'Slides' ),
				'menu_name'           => __(  'Ultra Slider' ),
				'parent_item_colon'   => __(  'Parent Slides' ),
				'all_items'           => __( 'All Slides'),
				'view_item'           => __( 'View Slide'),
				'add_new_item'        => __( 'Add New Slide Item' ),
				'add_new'             => __( 'Add New Slide' ),
				'edit_item'           => __( 'Edit Slide' ),
				'update_item'         => __( 'Update Slide' ),
				'search_items'        => __( 'Search Slide'),
				'not_found'           => __( 'Slide Not Found' ),
				'not_found_in_trash'  => __( 'Not found in Trash' )
			),
			'supports' => array('title', 'editor', 'thumbnail'),
			'rewrite' => array('slug' => 'ultra-slide'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 9,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'menu_icon' 		  => plugins_url() .'/ultra-camera-slider/images/ultra_slider.png',
			'publicly_queryable'  => true,
		)
	);
	
}
add_action( 'init', 'ultra_slider_post' ); 



//Set second featured image
add_theme_support( 'post-thumbnails', array( 'post', 'ultra_slider') );
add_image_size( 'ultra_slider_main', 1330, 450, true ); 
add_image_size( 'ultra_slider_sub', 100, 75, true );
add_filter( 'widget_text', 'do_shortcode');







 


/* Register Shortcode */
function ultra_slider_markup($atts){
	extract( shortcode_atts( array(
		'count' => '-1',
	), $atts, 'ultra-slider' ) );

	
	$q = new WP_Query(
		array('posts_per_page' => $count, 'post_type' => 'ultra_slider')
		);

		
		
	$list = '<div class="camera_wrap camera_magenta_skin" id="ultra_slider">';
	while($q->have_posts()) : $q->the_post();
		$idd = get_the_ID();
		$ultra_slider_main= wp_get_attachment_image_src( get_post_thumbnail_id($post->ID ), 'ultra_slider_main' );
		$ultra_slider_mains = $ultra_slider_main[0];
		$ultra_slider_sub= wp_get_attachment_image_src( get_post_thumbnail_id($post->ID ), 'ultra_slider_sub' );
		$ultra_slider_subs= $ultra_slider_sub[0];
		$list .= '
		
			
			<div data-thumb="'.$ultra_slider_subs.'" data-src="'.$ultra_slider_mains.'" >
                <div class="camera_caption fadeFromBottom">
                   '.get_the_content().'
                </div>
            </div>
			
		';        
	endwhile;
	$list.= '</div>';
	wp_reset_query();
	return $list;
}
add_shortcode('ultra_camera_slider', 'ultra_slider_markup'); 

?>