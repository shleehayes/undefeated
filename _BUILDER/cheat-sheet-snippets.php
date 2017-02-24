/*-------------------------------------
|
| Session 1: HTML/CSS to Theme Conversion
|
-------------------------------------*/

/*-------------------------------------
| wp-config
-------------------------------------*/
<?php
/*-------------------------------------
| Alter Wordpress Domain based on Environment. No need to change the database.
-------------------------------------*/
$server = $_SERVER['SERVER_NAME'];

if ($server == 'localhost')
{
	define('WP_HOME','http://localhost:8000');
	define('WP_SITEURL','http://localhost:8000');
}
else if ($server == 'dev.teamcrossfitacademy.com')
{
	define('WP_HOME','http://dev.teamcrossfitacademy.com');
	define('WP_SITEURL','http://dev.teamcrossfitacademy.com');
}
?>

/*-------------------------------------
| 1) Complete Bare Bones Files
-------------------------------------*/
screenshot.png [visual aid when choosing theme, create png in Photoshop]
style.css [represents the root styles for your theme, don't forget add essential meta data at top of your stylesheet for theme selection]
index.php [represents the blog posts]

/*---------------------------------
| 2) The Infamous Loop
---------------------------------*/
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <div class="post">
	<h1><?php the_title(); ?></h1>
	<div class="content">
	  <?php the_content(); ?>
	</div>
  </div>
<?php endwhile; endif; ?>

/*-------------------------------------
| 3) shared header and footer
-------------------------------------*/
header.php [shared opener]
footer.php [shared closing]

<?php get_header(); ?>   (e.g. header.php)
<?php get_footer(); ?>   (e.g. footer.php)

/*-------------------------------------
| 4) Header and footer Hooks
-------------------------------------*/
These are important for 3rd party plugins, and for Wordpress integration
<?php
  /* Always have wp_head() just before the closing </head>
   * tag of your theme, or you will break many plugins, which
   * generally use this hook to add elements to <head> such
   * as styles, scripts, and meta tags.
   */
  wp_head();
?>

<?php
  /* Always have wp_footer() just before the closing </body>
   * tag of your theme, or you will break many plugins, which
   * generally use this hook to reference JavaScript files.
   */
  wp_footer();
?>

/*-------------------------------------
| 5) Title
-------------------------------------*/
<title>
  <?php
	wp_title( '|', true, 'right' );
	bloginfo('name');
	// Add the blog description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	echo " | $site_description";
  ?>
</title>

/*-------------------------------------
|
| Session 2: Blog to Website Conversion
|
-------------------------------------*/
/*-------------------------------------
| 1) Convert Blog to Website
-------------------------------------*/
1) Add your web pages to the Pages section of Admin.
	Be mindful of the permalinks, update where necessary.
2) Create one page to represent the Blog
3) go to Settings > Reading
	Change the front page to be your home page, and posts page to be whatever page represents the Blog.
4) Update the main menu to reflect the permalinks
	<a href="<?php bloginfo('siteurl'); ?>/perma-link"></a>


/*---------------------------------
| 2) Dynamic WP Menus
---------------------------------*/
1) Add to functions.php
<?php
add_theme_support( 'menus' ); //this enables DB > Appearance > Menus (menus does not show otherwise)
function register_my_menus() {
  register_nav_menus(
	array(
	  'main_menu' => __( 'Primary Menu' ),
	  'sitemap_menu' => __( 'Sitemap Menu' )
	)
  );
}
add_action( 'init', 'register_my_menus' );
?>

2) add to your templates
<nav id="mainmenu">
  <?php
	wp_nav_menu(array(
	  'theme_location' => 'main_menu', // menu slug from step 1
	  'container' => false // 'div' container will not be added
	));
  ?>
</nav>
<nav id="sitemap">
  <?php
	wp_nav_menu(array(
	  'theme_location' => 'sitemap_menu', // menu slug from step 1
	  'container' => false // 'div' container will not be added
	));
  ?>
</nav>

3) DB > Appearance > Menus and manage your menus

/*-------------------------------------
| 3)
-------------------------------------*/


/*-------------------------------------
| 5) Body Class Slug
-------------------------------------*/
<?php
//functions.php
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );
?>

<body <?php body_class(); ?>>


/*-------------------------------------
| Template Parts
-------------------------------------*/
<?php get_header(); ?>   (e.g. header.php)
<?php get_header('cart'); ?>   (e.g. header-cart.php)

<?php get_footer(); ?>   (e.g. footer.php)
<?php get_footer('cart'); ?>   (e.g. footer-cart.php)

<?php get_sidebar(); ?>   (e.g. sidebar.php)
<?php get_sidebar('cart'); ?>  (e.g. sidebar-cart.php)

<?php get_template_part( 'partial', 'slideshow' ); ?> (e.g. partial-slideshow.php)

/*---------------------------------
| bloginfo
---------------------------------*/
<?php bloginfo('url'); ?>
<?php bloginfo('template_url'); ?>


/*---------------------------------
| Enqueue Scripts and Styles
| http://wpcandy.com/teaches/how-to-load-scripts-in-wordpress-themes/#.VMqGSF7F9Go
------------------------------------*/
<?php
// funtions.php

// Register some javascript files, because we love javascript files. Enqueue a couple as well
function my_loading_scripts() {
	// wp_register_script( $handle, $src, $deps, $ver, $in_footer );

	wp_register_script( 'js-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '1.2', true); //load default jquery as dependency
	wp_register_script( 'js-author', get_template_directory_uri() . '/js/scripts.js', array('js-bootstrap'), '1.2', true);

	wp_enqueue_script( 'jquery' ); //built into Wordpress - grabs their latest version of JQuery - not THE latest version.

	/*
		OR we could deregister the default WP jquery as follows

		wp_deregister_script('jquery');
		wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery-1.11.3.min.js', false, '1.11.3', false);  //roll our own

		// pull from google CDN instead
		// wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js", false, '1.11.3', false);

		wp_enqueue_script('jquery');

		// still a good idea to place in the head - in case other plugins try to use it in the head. performance hit

	*/


	wp_enqueue_script( 'js-bootstrap' );
	wp_enqueue_script( 'js-author' );
}

add_action( 'wp_enqueue_scripts', 'my_loading_scripts' );

if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
}



function my_loading_styles() {
	// wp_register_style( $handle, $src, $depsArr, $ver, $media );

	wp_register_style( 'css-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '1.2', 'screen' );
	// wp_register_style( 'css-theme', get_stylesheet_uri(), array('css-bootstrap'), '1.2', 'screen' ); // if we are using the style.css in the theme dir
	wp_register_style( 'css-author', get_template_directory_uri() . '/css/styles.css', array('css-bootstrap'), '1.2', 'screen' );


	wp_enqueue_style( 'css-bootstrap' );
	// wp_enqueue_style( 'css-theme' ); // if we are using the style.css in the theme dir
	wp_enqueue_style( 'css-author' ); //our own custom css file
}

add_action( 'wp_enqueue_scripts', 'my_loading_styles' );

?>

/*---------------------------------
| jQuery No-Conflict
| http://wpcandy.com/teaches/how-to-load-scripts-in-wordpress-themes/#.VMqGSF7F9Go
------------------------------------*/
<script type="text/javascript">
	(function($) {
	    var jQuery = $.noconflict(true);
	    // From there on, window.jQuery and window.$ are undefined.
	    var $ = jQuery;
	    // Your code goes below this line


	})(jQuery);
</script>


/*---------------------------------
| Featured Image/Post Thumbnail
| More details...
| http://codex.wordpress.org/Post_Thumbnails
| http://www.wpbeginner.com/beginners-guide/how-to-add-featured-image-or-post-thumbnails-in-wordpress/
|
| Plugins
| Regenerate Thumbnails :: for after the fact :: http://wordpress.org/extend/plugins/regenerate-thumbnails/
| Simple Image Sizes :: Give author control in editor :: http://wordpress.org/extend/plugins/simple-image-sizes/
---------------------------------*/
<?php
// functions.php
add_theme_support( 'post-thumbnails' ); //enables feature img
set_post_thumbnail_size( 50, 50); //sets default size of uploaded img


// adding more :: http://www.wpbeginner.com/wp-tutorials/how-to-create-additional-image-sizes-in-wordpress/
add_image_size( 'sidebar-thumb', 120, 120, true ); // Hard Crop Mode :: crops to hard dimensions
add_image_size( 'homepage-thumb', 220, 180 ); // Soft Crop Mode :: resamples down to fit within the w/h - no cropping
add_image_size( 'singlepost-thumb', 590, 9999 ); // Unlimited Width or Height Mode

// place in the loop

the_post_thumbnail(); //default featured image

the_post_thumbnail( 'thumbnail' );       // Thumbnail (default 150px x 150px max)
the_post_thumbnail( 'medium' );          // Medium resolution (default 300px x 300px max)
the_post_thumbnail( 'large' );           // Large resolution (default 640px x 640px max)
the_post_thumbnail( 'full' );            // Full resolution (original size uploaded)

the_post_thumbnail( 'your-specified-image-size' ); //additional sizes as specified above
the_post_thumbnail( 'homepage-thumb' ); //for example

?>

/*---------------------------------
| Categories
| http://codex.wordpress.org/Template_Tags/wp_list_categories
---------------------------------*/
<ul>
<?php wp_list_categories('exclude=4,7&title_li='); ?>
</ul>


/*---------------------------------
| Tags
| http://codex.wordpress.org/Function_Reference/get_the_tag_list
---------------------------------*/
<?php
echo get_the_tag_list('<p>Tags: ',', ','</p>');
?>



/*---------------------------------
| Widgets
| http://digwp.com/2010/02/how-to-widgetize-wordpress-theme/
---------------------------------*/
<?php
// functions.php
if (function_exists('register_sidebar')) {

	register_sidebar(array(
		'name' => 'Widgetized Area',
		'id'   => 'widgetized-area',
		'description'   => 'This is a widgetized area.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	));
	register_sidebar(array(
		'name' => 'Sidebar',
		'id'   => 'sidebar',
		'description'   => 'This is the widgetized sidebar.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	));
	register_sidebar(array(
		'name' => 'Footer',
		'id'   => 'footer',
		'description'   => 'This is the widgetized footer.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	));


}
?>

<!-- Place in the part of your theme you wish for this to show up -->
<div id="widgetized-area">

	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('widgetized-area')) : else : ?>

	<div class="pre-widget">
		<p><strong>Widgetized Area</strong></p>
		<p>This panel is active and ready for you to add some widgets via the WP Admin</p>
	</div>

	<?php endif; ?>

</div>
<div id="widgetized-sidebar">

	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('sidebar')) : else : ?>

	<div class="pre-widget">
		<p><strong>Widgetized Sidebar</strong></p>
		<p>This panel is active and ready for you to add some widgets via the WP Admin</p>
	</div>

	<?php endif; ?>

</div>
<div id="widgetized-footer">

	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('footer')) : else : ?>

	<div class="pre-widget">
		<p><strong>Widgetized Footer</strong></p>
		<p>This panel is active and ready for you to add some widgets via the WP Admin</p>
	</div>

	<?php endif; ?>

</div>


/*---------------------------------
| Custom Fields Plugin For Additional Inputs
| https://wordpress.org/plugins/advanced-custom-fields/
---------------------------------*/
<!-- Place in loop -->
<h1><?php the_field('field_name1'); ?></h1>
<h2><?php the_field('field_name2'); ?></h2>
<h2><?php echo get_field('field_name2'); ?></h2>

<?php
/*-------------------------------------
| Get Content With Formatting
-------------------------------------*/
function get_the_content_with_formatting ($more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}
?>


/*---------------------------------
| Sync To Live Host
---------------------------------*/
1) Upload your files
2) Create DB , user with privs on your host
3) Export local DB
4) Import to new DB on Host
5) Update Live Server DB wp_options {change loclahost paths to new paths}
6) Update wp-config on live server to match live DB and user credentials and publosh to host
7) Test site - sub pages may be broken
8) Refresh Permalinks in admin to fix sub page link problems above
9) Find Replace plugin for any images in posts and pages.
	https://wordpress.org/plugins/find-replace/
10) test, test, test.
11) Go to sleep or Jump up and down


/*---------------------------------
| Wordpress Theme Core Files
---------------------------------*/
single.php [represents a single blog post]
page.php [represents the pages]
sidebar.php  [shared sidebar]
functions.php [where we tweak and enhance our theme]

/*---------------------------------
| Theme Template Parts
---------------------------------*/
<?php get_header('cart'); ?>   (e.g. header-cart.php)
<?php get_footer('cart'); ?>   (e.g. footer-cart.php)

<?php get_sidebar(); ?>   (e.g. sidebar.php)
<?php get_sidebar('cart'); ?>  (e.g. sidebar-cart.php)

<?php get_template_part( 'slideshow', 'vertical' ); ?> (e.g. slideshow-vertical.php)