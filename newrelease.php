<?php 
/*
Plugin Name:New release
Version: 0.1
Plugin URI: http://www.codiva.com/
Description:  Testimonials is a plugin that lets you add  testimonials in WordPress
Author: Mayowa
Author URI: phoenix.sheridanc.on.ca/~ccit2717/
*/


add_action( 'init', 'create_post_type' );
function create_post_type() {
        $args = array();
	register_post_type( 'post_type_name', $args);
}

add_action( 'init', 'register_cpt_cp_name' );
 
function register_cpt_cp_name() {
 
    $labels = array( 
  	'name'               => __( 'New releases', 'text_domain' ),
		'singular_name'      => __( 'New releases', 'text_domain' ),
		'add_new'            => _x( 'Add New releases', '${4:Name}', 'text_domain' ),
		'add_new_item'       => __( 'Add New releases', 'text_domain}' ),
		'edit_item'          => __( 'Edit New releases', 'text_domain' ),
		'new_item'           => __( 'New car releases', 'text_domain' ),
		'view_item'          => __( 'View New releases', 'text_domain' ),
		'search_items'       => __( 'Search New releases', 'text_domain' ),
		'not_found'          => __( 'No New releases found', 'text_domain' ),
		'not_found_in_trash' => __( 'No New releases found in Trash', 'text_domain' ),
		'parent_item_colon'  => __( 'Parent sNew releases:', 'text_domain' ),
		'menu_name'          => __( 'New releases', 'text_domain' ),
    );
 
    $args = array( 
		'labels'              => $labels,
		'hierarchical'        => true,
		'description'         => 'description',
		'taxonomies'          => array( 'category' ),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		//'menu_icon'         => '',
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post', 
		'supports'            => array( 
									'title', 'editor', 'author', 'thumbnail', 
									'custom-fields', 'trackbacks', 'comments', 
									'revisions', 'page-attributes', 'post-formats'
								),
    );
 
    register_post_type( 'cp_name', $args );
}

function create_post_type_labels($singular, $plural = null) {

	if ($plural === null) {
		$plural = $singular.'s';
	}

	$labels = array(
		'name'               => __( $plural, 'text-domain'),
		'singular_name'      => __( $singular, 'text-domain'),
		'menu_name'          => __( $plural, 'text-domain'),
		'name_admin_bar'     => __( $singular, 'text-domain'),
		'add_new'            => __( 'Add New '.$singular, 'text-domain'),
		'add_new_item'       => __( 'Add New '.$singular, 'text-domain'),
		'new_item'           => __( 'New '.$singular, 'text-domain'),
		'edit_item'          => __( 'Edit '.$singular, 'text-domain'),
		'view_item'          => __( 'View '.$singular, 'text-domain'),
		'all_items'          => __( 'All '.$plural, 'text-domain'),
		'search_items'       => __( 'Search '.$plural, 'text-domain'),
		'parent_item_colon'  => __( 'Parent '.$plural.':', 'text-domain'),
		'not_found'          => __( 'No '.$plural.' found.', 'text-domain'),
		'not_found_in_trash' => __( 'No '.$plural.' found in Trash.', 'text-domain')
	);

	return $labels;
}




function n2wp_latest_cpt_init() {
if ( !function_exists( 'register_sidebar_widget' ))
return;

function n2wp_latest_cpt($args) {
global $post;
extract($args);

// These are our own options
$options = get_option( 'n2wp_latest_cpt' );
$title = $options['title']; // Widget title
$phead = $options['phead']; // Heading format
$ptype = $options['ptype']; // Post type
$pshow = $options['pshow']; // Number of Tweets

$beforetitle = '';
$aftertitle = '';

// Output
echo $before_widget;

if ($title) echo $beforetitle . $title . $aftertitle;

$pq = new WP_Query(array( 'post_type' => $ptype, 'showposts' => $pshow ));
if( $pq->have_posts() ) :
?>
<ul>
<ul><?php while($pq->have_posts()) : $pq->the_post(); ?>
    <li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
</ul>
</ul>
<?php wp_reset_query();
endwhile; ?>

<?php endif; ?>


<!-- 
<?php $obj = get_create_post_type_object($ptype); ?>
<div class="latest_cpt_icon"><a href="<?php site_url('/'.$obj->query_var); ?>" rel="bookmark"><?php _e( 'View all ' . $obj->labels->name . ' posts' ); ?>&rarr;</a></div>

 -->

<?php
// echo widget closing tag
echo $after_widget;
}

/**
* Widget settings form function
*/
function n2wp_latest_cpt_control() {

// Get options
$options = get_option( 'register_cpt_cp_name' );
// options exist? if not set defaults
if ( !is_array( $options ))
$options = array(
'title' => 'New Releases',
'phead' => 'h2',
'ptype' => 'post',
'pshow' => '5'
);
// form posted?
if ( $_POST ) {
$options['title'] = strip_tags( $_POST['latest-cpt-title'] );
$options['phead'] = $_POST['latest-cpt-phead'];
$options['ptype'] = $_POST['latest-cpt-ptype'];
$options['pshow'] = $_POST['latest-cpt-pshow'];
update_option( 'n2wp_latest_cpt', $options );
}
// Get options for form fields to show
$title = $options['title'];
$phead = $options['phead'];
$ptype = $options['ptype'];
$pshow = $options['pshow'];

// The widget form fields
?>

 
<label for="latest-cpt-title"><?php echo __( 'Widget Title' ); ?>
<input id="latest-cpt-title" type="text" name="latest-cpt-title" size="30" value="<?php echo $title; ?>" />
</label>

<label for="latest-cpt-phead"><?php echo __( 'Widget Heading Format' ); ?></label>

<select name="latest-cpt-phead"><option selected="selected" value="h2">H2 - <h2></h2></option><option selected="selected" value="h3">H3 - <h3></h3></option><option selected="selected" value="h4">H4 - <h4></h4></option><option selected="selected" value="strong">Bold - <strong></strong></option></select><select name="latest-cpt-ptype"><option value="">- <?php echo __( 'New releases' ); ?> -</option></select><?php $args = array( 'public' => true );
$post_types = get_post_types( $args, 'names' );
foreach ($post_types as $post_type ) { ?>

<select name="latest-cpt-ptype"><option selected="selected" value="<?php echo $post_type; ?>"><?php echo $post_type;?></option></select><?php } ?>
<!--
<label for="latest-cpt-pshow"><?php echo __( 'Number of posts to show' ); ?>
<input id="latest-cpt-pshow" type="text" name="latest-cpt-pshow" size="2" value="<?php echo $pshow; ?>" />
</label>

<input id="latest-cpt-submit" type="hidden" name="latest-cpt-submit" value="1" />
 -->
<?php
}


wp_register_sidebar_widget( 'widget_latest_cpt', __('Latest Custom Posts'), 'n2wp_latest_cpt' );
wp_register_widget_control( 'widget_latest_cpt', __('Latest Custom Posts'), 'n2wp_latest_cpt_control', 300, 200 );

}
add_action( 'widgets_init', 'n2wp_latest_cpt_init' );

?>
