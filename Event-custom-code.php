<?php
// event custome post type 


function create_event_post_type() {
    $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'menu_name'          => 'Events',
        'add_new'            => 'Add New Event',
        'add_new_item'       => 'Add New Event',
        'edit_item'          => 'Edit Event',
        'new_item'           => 'New Event',
        'view_item'          => 'View Event',
        'search_items'       => 'Search Events',
        'not_found'          => 'No events found',
        'not_found_in_trash' => 'No events found in Trash',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-calendar',
        'rewrite'             => array( 'slug' => 'events' ),
        'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
    );

    register_post_type( 'event', $args );
}
add_action( 'init', 'create_event_post_type' );

function create_event_taxonomies() {
    // Categories
    $labels = array(
        'name'              => 'Event Categories',
        'singular_name'     => 'Event Category',
        'search_items'      => 'Search Event Categories',
        'all_items'         => 'All Event Categories',
        'parent_item'       => 'Parent Event Category',
        'parent_item_colon' => 'Parent Event Category:',
        'edit_item'         => 'Edit Event Category',
        'update_item'       => 'Update Event Category',
        'add_new_item'      => 'Add New Event Category',
        'new_item_name'     => 'New Event Category Name',
    );
    register_taxonomy( 'event_category', 'event', array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'event-category' ),
    ));

    // Locations
    $labels = array(
        'name'              => 'Event Locations',
        'singular_name'     => 'Event Location',
        'search_items'      => 'Search Event Locations',
        'all_items'         => 'All Event Locations',
        'edit_item'         => 'Edit Event Location',
        'update_item'       => 'Update Event Location',
        'add_new_item'      => 'Add New Event Location',
        'new_item_name'     => 'New Event Location Name',
    );
    register_taxonomy( 'event_location', 'event', array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'event-location' ),
    ));
}

add_action( 'init', 'create_event_taxonomies' );

function add_event_date_time_field() {
    add_meta_box(	
        'event_date_time',
        'Event Date and Time',
        'display_event_date_time_field',
        'event',
        'normal',
        'default'
    );
}

function display_event_date_time_field($post) {
    $event_date = get_post_meta($post->ID, '_event_date', true);
    $start_time = get_post_meta($post->ID, '_start_time', true);
    $end_time = get_post_meta($post->ID, '_end_time', true);
    $start_am_pm = get_post_meta($post->ID, '_start_am_pm', true);
    $end_am_pm = get_post_meta($post->ID, '_end_am_pm', true);
    ?>
    <label for="event_date">Event Date:</label>
    <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>" />
    <br/>
<label for="start_time">Start Time:</label>
    <input type="text" name="start_time" id="start_time" value="<?php echo esc_attr($start_time); ?>" />
    <select name="start_am_pm" id="start_am_pm">
		<option value=""></option>
        <option value="AM" <?php selected($start_am_pm, 'AM'); ?>>AM</option>
        <option value="PM" <?php selected($start_am_pm, 'PM'); ?>>PM</option>
    </select>

    <label for="end_time">End Time:</label>
    <input type="text" name="end_time" id="end_time" value="<?php echo esc_attr($end_time); ?>" />
    <select name="end_am_pm" id="end_am_pm">
		<option value=""></option>
        <option value="AM" <?php selected($end_am_pm, 'AM'); ?>>AM</option>
        <option value="PM" <?php selected($end_am_pm, 'PM'); ?>>PM</option>
    </select>
    <?php
}

function save_event_date_time($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['event_date'])) {
        update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
    }
    
   if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['start_time'])) {
        update_post_meta($post_id, '_start_time', sanitize_text_field($_POST['start_time']));
    }

    if (isset($_POST['start_am_pm'])) {
        update_post_meta($post_id, '_start_am_pm', sanitize_text_field($_POST['start_am_pm']));
    }

    if (isset($_POST['end_time'])) {
        update_post_meta($post_id, '_end_time', sanitize_text_field($_POST['end_time']));
    }

    if (isset($_POST['end_am_pm'])) {
        update_post_meta($post_id, '_end_am_pm', sanitize_text_field($_POST['end_am_pm']));
    }
}
add_action('add_meta_boxes', 'add_event_date_time_field');
add_action('save_post', 'save_event_date_time');
?>

<?php
// Template Name: Events_Template
get_header();
?>

<section class="frist-banner-sec">
<div class="banner-sec-row">
	<div class="baner-sec-hed">
<h1>EVENTS</h1>		
</div>
	</div>
</section>
<div class="hpro_container">

	<div class="hpro_row">
		<div class="hpro_column_one">
<?php 
	  //
	  if(isset($_GET['id_get'])){
 // $query_slug = implode (',',$_GET['id_get']);
  // $query_slug = IN($query_slug_1);
 // $query_slug = implode(' , ', $_GET['id_get']);  
 $query_slug = $_GET['id_get'];
// echo  "$query_slug";

$hotelspost = array( 'post_type' => 'event', 
                    'tax_query' =>array(
                        array(
                        'taxonomy' => 'event_category',
                        'field'  => 'slug',
                        'terms' => $query_slug,
                        )
                    ),
);

$loop = new WP_Query( $hotelspost );
?>
  
<?php 

while ( $loop->have_posts() ) : $loop->the_post();?>
			<div class="date_des">
		<?php
		 $post_id = get_the_ID();
		   $hello = get_post_meta($post_id, '_event_date');
		 $echo = $hello[0]; 
		
		  $formatted_date = date_i18n('j M Y', strtotime($echo));
		  echo "$formatted_date" ;
		?>
	</div>
 <div class="hpro_collect_post"> 
	 <div class="hpro_disply_flex_one">
		 <h3 class="ent-tite"><span class="text-des">Event Title : </span><?php the_title( ); ?></h3> 
	 </div>
		<?php
		  $post_id = get_the_ID();
		   $hello = get_post_meta($post_id, '_event_date');
		   $hello_2 = get_post_meta($post_id, '_start_time');
		  $hello_3 = get_post_meta($post_id, '_end_time');
		   $hello_4 = get_post_meta($post_id, '_start_am_pm');
		   $hello_5 = get_post_meta($post_id, '_end_am_pm');
		   $echo = $hello[0];
		   $echo_2 = $hello_2[0];
		   $echo_3 = $hello_3[0];
		   $echo_4 = $hello_4[0];
		   $echo_5 = $hello_5[0];
		  ?>
	 <div class="hpro_flex">	
		<p>
			<?php echo "$echo";  ?>	</p> 
		
	<p><span><?php echo "$echo_2"; echo "$echo_4";?></span> -
		<span><?php
		    echo "$echo_3"; echo "$echo_5";
		?></span></p>
	 </div>
	
	
	
	     
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content"><?php the_content(); ?></div>   
</article>
 </div>
<?php endwhile;
		  ?>
	   
	   <?php
}elseif($_GET['id_get_2']){ ?>

<!-- second start -->
<?php
// print_r($_GET['id_get']);
//$query_slug =  (',',$_GET['id_get']);
 // $query_slug = implode (',',$_GET['id_get']);
  // $query_slug = IN($query_slug_1);
 // $query_slug = implode(' , ', $_GET['id_get']);  
 $query_slug = $_GET['id_get_2'];
//  echo  "$query_slug";

$hotelspost = array( 'post_type' => 'event', 
                    'tax_query' =>array(
                        array(
                        'taxonomy' => 'event_location',
                        'field'  => 'slug',
                        'terms' => $query_slug,
                        )
                    ),
);

$loop = new WP_Query( $hotelspost );
?>
    
<?php 
while ( $loop->have_posts() ) : $loop->the_post();?>
			<div class="date_des">
		<?php
		 $post_id = get_the_ID();
		   $hello = get_post_meta($post_id, '_event_date');
		 $echo = $hello[0]; 
		
		  $formatted_date = date_i18n('j M Y', strtotime($echo));
		  echo "$formatted_date" ;
		?>
	</div>
<div class="hpro_collect_post">
	 <div class="hpro_disply_flex_one">
			 <h3 class="ent-tite"><span class="text-des">Event Title : </span><?php the_title( ); ?></h3> 
	</div>
		<?php
		  $post_id = get_the_ID();
		   $hello = get_post_meta($post_id, '_event_date');
		   $hello_2 = get_post_meta($post_id, '_start_time');
		  $hello_3 = get_post_meta($post_id, '_end_time');
		   $hello_4 = get_post_meta($post_id, '_start_am_pm');
		   $hello_5 = get_post_meta($post_id, '_end_am_pm');
		 $echo = $hello[0];
		   $echo_2 = $hello_2[0];
		   $echo_3 = $hello_3[0];
		   $echo_4 = $hello_4[0];
		   $echo_5 = $hello_5[0];
		  ?>
	<div class="hpro_flex">	
		<p>
				<?php echo "$echo";  ?>	</p> 

		<p><span><?php echo "$echo_2"; echo "$echo_4";?></span> -
			<span><?php
				echo "$echo_3"; echo "$echo_5";
			?></span></p>
	</div>
<?php echo esc_attr(get_post_meta($loop->ID, 'event_date', true));?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content"><?php the_content(); ?></div>
</article>
</div>
<?php endwhile;?>
	
<?php
}elseif($_GET['all_events']){ ?>

<!-- second start -->
<?php

 $query_slug = $_GET['all_events'];

$hotelspost = array( 'post_type' => 'event');

$loop = new WP_Query( $hotelspost );
?>
    
<?php 
while ( $loop->have_posts() ) : $loop->the_post();?>
			<div class="date_des">
		<?php
		 $post_id = get_the_ID();
		   $hello = get_post_meta($post_id, '_event_date');
		 $echo = $hello[0]; 
		
		  $formatted_date = date_i18n('j M Y', strtotime($echo));
		  echo "$formatted_date" ;
		?>
	</div>
<div class="hpro_collect_post">
	 <div class="hpro_disply_flex_one">
	 	 <h3 class="ent-tite"><span class="text-des">Event Title :</span><?php the_title( ); ?></h3> 
	</div>
		<?php
		  $post_id = get_the_ID();
		   $hello = get_post_meta($post_id, '_event_date');
		   $hello_2 = get_post_meta($post_id, '_start_time');
		  $hello_3 = get_post_meta($post_id, '_end_time');
		   $hello_4 = get_post_meta($post_id, '_start_am_pm');
		   $hello_5 = get_post_meta($post_id, '_end_am_pm');
		 $echo = $hello[0];
		   $echo_2 = $hello_2[0];
		   $echo_3 = $hello_3[0];
		   $echo_4 = $hello_4[0];
		   $echo_5 = $hello_5[0];
		  ?>
	<div class="hpro_flex">
		<p>
			<?php echo "$echo";  ?>	</p> 
		
	<p><span><?php echo "$echo_2"; echo "$echo_4";?></span> -
		<span><?php
		    echo "$echo_3"; echo "$echo_5";
		?></span></p>
	</div>
<?php echo esc_attr(get_post_meta($loop->ID, 'event_date', true));?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content"><?php the_content(); ?></div>
</article>
	</div>
<?php endwhile;?>

<?php
}else{
		  ?>

	<?php
 $hotelspost = array( 'post_type' => 'event',
    'limit' => '12',
  'tax_query' => array(
        array(
            'taxonomy' => 'event_category', 
            'field' => 'slug',     
            'terms' => 'upcoming-skills-building',
            'operator' => 'NOT IN',  
        ),
					),
					 );
    $loop = new WP_Query( $hotelspost );

while ( $loop->have_posts() ) : $loop->the_post();?>
			<div class="date_des">
		<?php
		 $post_id = get_the_ID();
		   $hello = get_post_meta($post_id, '_event_date');
		 $echo = $hello[0]; 
		
		  $formatted_date = date_i18n('j M Y', strtotime($echo));
		  echo "$formatted_date" ;
		?>
	</div>
<div class="hpro_collect_post">
	 <div class="hpro_disply_flex_one">
			 <h3 class="ent-tite"><span class="text-des">Event Title : </span><?php the_title( ); ?></h3> 
	</div>
		<?php
		  $post_id = get_the_ID();
		   $hello = get_post_meta($post_id, '_event_date');
		   $hello_2 = get_post_meta($post_id, '_start_time');
		  $hello_3 = get_post_meta($post_id, '_end_time');
		   $hello_4 = get_post_meta($post_id, '_start_am_pm');
		   $hello_5 = get_post_meta($post_id, '_end_am_pm');
		 $echo = $hello[0];
		   $echo_2 = $hello_2[0];
		   $echo_3 = $hello_3[0];
		   $echo_4 = $hello_4[0];
		   $echo_5 = $hello_5[0];
		  ?>
	<div class="hpro_flex">
		<p>
			<?php echo "$echo";  ?>	</p> 
		
	<p><span><?php echo "$echo_2"; echo "$echo_4";?></span>
		<span><?php
		    echo "$echo_3"; echo "$echo_5";
		?></span></p>
	</div>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-content"><?php the_content(); ?></div>
</article>
</div>
<?php endwhile;?>
	
	<?php
	  }
?>
		</div>
	<div class='hpro_column_two'>
	  <div class="hpro_collect_category_first">
		    <h2>
			 EVENTS 
		  </h2>
		 <p>
		   <a href="?all_events='all_events'">All Events</a>	 
		  </p>
		  <?php
               
               $categories =  get_terms(['taxonomy'=> 'event_category']);
               foreach($categories as $category) {
                   ?>
          <?php echo '<p> <a href="?id_get=' .  $category->slug . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> </p> ';?>  <?php  } ?>
			</div>
				  <div class="hpro_collect_category_second">
		  <h2>
			 EVENTS BY LOCATION
		  </h2>
		   <p>Argentina </p> 
					   <p>Bangladesh </p>
					   <p>Benin </p>
					   <p> Burkina Faso </p>
					   <p>Ghana </p>
		 </div> 
	 </div>
	</div>
	</div>
<?php 
get_footer();
?>

slick- slider



// Function to enqueue custom scripts
function my_scripts_method() {
	 wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '//custom.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'slick-min-js', get_stylesheet_directory_uri() . '/slick.min.js', array( 'jquery' ), '1.0', true );
	}
LINK

<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
 <link rel="stylesheet"  type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<script type="text/javascript" async src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


jQuery(document).ready(function(){
		 jQuery(".blg-fit-post article").addClass("testiii"); 
jQuery('.resource-slider .elementor-column-gap-default').slick({ 
   infinite: true, // This sets our slider to slide infinitely in a loop
    slidesToShow: 2,
    slidesToScroll: 1,
    autoplay: true,
  autoplaySpeed: 2000,
    dots: false,
    arrows: true,
    responsive: [
      { 
        breakpoint: 980,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

SCROLL CLASS ADD

jQuery(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    if (scroll >= 650) {
        jQuery(".hpro_column_two").addClass("darkHeader");
    }else {
           jQuery(".hpro_column_two").removeClass("darkHeader");
        }
});

});




/********************* Classic Editor Code Start  ********************/ 

add_filter('use_block_editor_for_post', '__return_false', 10);
