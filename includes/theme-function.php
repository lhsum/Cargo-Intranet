<?php

// The excerpt based on words
function my_string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words).'... ';
}


// The excerpt based on character
function my_string_limit_char($excerpt, $substr=0)
{

	$string = strip_tags(str_replace('...', '...', $excerpt));
	if ($substr>0) {
		$string = substr($string, 0, $substr);
	}
	return $string;
		}


add_action( 'after_setup_theme', 'my_setup' );


// Remove invalid tags
function remove_invalid_tags($str, $tags) 
{
    foreach($tags as $tag)
    {
    	$str = preg_replace('#^<\/'.$tag.'>|<'.$tag.'>$#', '', trim($str));
    }

    return $str;
}

// Generates a random string (for embedding flash)
function theme1843_random($length){

	srand((double)microtime()*1000000 );
	
	$random_id = "";
	
	$char_list = "abcdefghijklmnopqrstuvwxyz";
	
	for($i = 0; $i < $length; $i++) {
		$random_id .= substr($char_list,(rand()%(strlen($char_list))), 1);
	}
	
	return $random_id;
}


// Remove Empty Paragraphs
add_filter('the_content', 'shortcode_empty_paragraph_fix');
function shortcode_empty_paragraph_fix($content)
{   
	$array = array (
			'<p>[' => '[', 
			']</p>' => ']', 
			']<br />' => ']'
	);

	$content = strtr($content, $array);

return $content;
}




// For embedding video file
function mytheme_video($file, $image, $width, $height, $color){

	//Template URL
	$template_url = get_template_directory_uri();
	
	//Unique ID
	$id = "video-".theme1843_random(15);
	
	$object_height = $height + 39;

	//JS
	$output  = '<script type="text/javascript">'."\n";
	$output .= 'var flashvars = {};'."\n";
	$output .= 'flashvars.player_width="'.$width.'";'."\n";
	$output .= 'flashvars.player_height="'.$height.'"'."\n";
	$output .= 'flashvars.player_id="'.$id.'";'."\n";
	$output .= 'flashvars.thumb="'.$image.'";'."\n";
	$output .= 'flashvars.colorTheme="'.$color.'";'."\n";
	$output .= 'var params = { "wmode": "transparent" };'."\n";
	$output .= 'params.wmode = "transparent";'."\n";
	$output .= 'params.quality = "high";';
	$output .= 'params.allowFullScreen = "true";'."\n";
	$output .= 'params.allowScriptAccess = "always";'."\n";
	$output .= 'params.quality="high";'."\n";
	$output .= 'var attributes = {};'."\n";
	$output .= 'attributes.id = "'.$id.'";'."\n";
	$output .= 'swfobject.embedSWF("'.$template_url.'/flash/video.swf?fileVideo='.$file.'", "'.$id.'", "'.$width.'", "'.$object_height.'", "9.0.0", false, flashvars, params, attributes);'."\n";
	$output .= '</script>'."\n\n";
	
	$output .= '<div class="video-bg" style="width:'.$width.'px; height:'.$height.'px; background-image:url('.$image.')">'."\n";
	$output .= '</div>'."\n";
	
	//HTML
	$output .= '<div id="'.$id.'">'."\n";
			$output .= '<a href="http://www.adobe.com/go/getflashplayer">'."\n";
					$output .= '<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />'."\n";
			$output .= '</a>'."\n";
	$output .= '</div>';

	return $output;
    
}



// Add Thumb Column
if ( !function_exists('fb_AddThumbColumn') && function_exists('add_theme_support') ) {
// for post and page
add_theme_support('post-thumbnails', array( 'post', 'page' ) );
function fb_AddThumbColumn($cols) {
$cols['thumbnail'] = __('Thumbnail');
return $cols;
}
function fb_AddThumbValue($column_name, $post_id) {
$width = (int) 35;
$height = (int) 35;
if ( 'thumbnail' == $column_name ) {
// thumbnail of WP 2.9
$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
// image from gallery
$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
if ($thumbnail_id)
$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
elseif ($attachments) {
foreach ( $attachments as $attachment_id => $attachment ) {
$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
}
}
if ( isset($thumb) && $thumb ) {
echo $thumb;
} else {
echo __('None');
}
}
}
// for posts
add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );
// for pages
add_filter( 'manage_pages_columns', 'fb_AddThumbColumn' );
add_action( 'manage_pages_custom_column', 'fb_AddThumbValue', 10, 2 );
}



// Show filter by categories for custom posts
function my_restrict_manage_posts() {
	global $typenow;
	$args=array( 'public' => true, '_builtin' => false ); 
	$post_types = get_post_types($args);
	if ( in_array($typenow, $post_types) ) {
	$filters = get_object_taxonomies($typenow);
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			wp_dropdown_categories(array(
				'show_option_all' => __('Show All '.$tax_obj->label ),
				'taxonomy' => $tax_slug,
				'name' => $tax_obj->name,
				'orderby' => 'term_order',
				'selected' => $_GET[$tax_obj->query_var],
				'hierarchical' => $tax_obj->hierarchical,
				'show_count' => false,
				'hide_empty' => true
			));
		}
	}
}
function my_convert_restrict($query) {
	global $pagenow;
	global $typenow;
	if ($pagenow=='edit.php') {
		$filters = get_object_taxonomies($typenow);
		foreach ($filters as $tax_slug) {
			$var = &$query->query_vars[$tax_slug];
			if ( isset($var) ) {
				$term = get_term_by('id',$var,$tax_slug);
				$var = $term->slug;
			}
		}
	}
}
add_action('restrict_manage_posts', 'my_restrict_manage_posts' );
add_filter('parse_query','my_convert_restrict');



// Add to admin_init function
add_action('manage_portfolio_posts_custom_column' , 'custom_portfolio_columns', 10, 2);
add_filter('manage_edit-portfolio_columns', 'my_portfolio_columns');
//Add columns for portfolio posts
function my_portfolio_columns($columns) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Title",
		"portfolio_categories" => "Categories",
		"comments" => "<span><span class=\"vers\"><img src=\"".get_admin_url()."images/comment-grey-bubble.png\" alt=\"Comments\"></span></span>",
		"date" => "Date",
		"thumbnail" => "Thumbnail"
	);
	return $columns;
}
function custom_portfolio_columns( $column, $post_id ) {
	switch ( $column ) {
	case 'portfolio_categories':
		$terms = get_the_term_list( $post_id , 'portfolio_category' , '' , ',' , '' );
		if ( is_string( $terms ) ) {
			echo $terms;
		} else {
			echo 'Uncategorized';
		}
		break;
	}
}


// Add to admin_init function
add_action('manage_reviews_posts_custom_column' , 'custom_reviews_columns', 10, 2);
add_filter('manage_edit-reviews_columns', 'my_reviews_columns');
//Add columns for reviews posts
function my_reviews_columns($columns) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Title",
		"reviews_categories" => "Categories",
		"comments" => "<span><span class=\"vers\"><img src=\"".get_admin_url()."images/comment-grey-bubble.png\" alt=\"Comments\"></span></span>",
		"date" => "Date",
		"thumbnail" => "Thumbnail"
	);
	return $columns;
}
function custom_reviews_columns( $column, $post_id ) {
	switch ( $column ) {
	case 'reviews_categories':
		$terms = get_the_term_list( $post_id , 'reviews_category' , '' , ',' , '' );
		if ( is_string( $terms ) ) {
			echo $terms;
		} else {
			echo 'Uncategorized';
		}
		break;
	}
}


/*-----------------------------------------------------------------------------------*/
/* Output image */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'tz_image' ) ) {
    function tz_image($postid, $imagesize) {
        // get the featured image for the post
        if( has_post_thumbnail($postid) ) {
						$thumb = get_post_thumbnail_id();
						$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
						$image = aq_resize( $img_url, 620, 400, true ); //resize & crop img
						
						
						echo '<img src="'. $image .'" alt="'. get_the_title() .'" />';
						
        }
        
    }
}


/*-----------------------------------------------------------------------------------*/
/* Output gallery */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'tz_grid_gallery' ) ) {
    function tz_grid_gallery($postid, $imagesize) { ?>
				
				<!--BEGIN .slider -->
					<div class="grid_gallery clearfix">
					
						<div class="grid_gallery_inner">
							
						<?php 
								$args = array(
										'orderby'		 => 'menu_order',
										'order' => 'ASC',
										'post_type'      => 'attachment',
										'post_parent'    => get_the_ID(),
										'post_mime_type' => 'image',
										'post_status'    => null,
										'numberposts'    => -1,
								);
								$attachments = get_posts($args);
						?>
								
								<?php if ($attachments) : ?>
								
								<?php foreach ($attachments as $attachment) : ?>
										
										<?php 
										$attachment_url = wp_get_attachment_image_src( $attachment->ID, 'full' );
										$url = $attachment_url['0'];
										$image = aq_resize($url, 180, 120, true);
										?>
										
										<div class="gallery_item">
											<figure class="featured-thumbnail single-gallery-item">
												<a href="<?php echo $attachment_url['0'] ?>" class="image-wrap" rel="prettyPhoto[gallery]">
												<img 
												alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>"
												src="<?php echo $image ?>"
												width="180"
												height="120"
												/>
												<span class="zoom-icon"></span>
												</a>
											</figure>
										</div>
								
								<?php endforeach; ?>
								
								<?php endif; ?>
							
						</div>

					<!--END .slider -->
					</div>
				
				
        
    <?php }
}

/*-----------------------------------------------------------------------------------*/
/* Output gallery slideshow */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'tz_gallery' ) ) {
    function tz_gallery($postid, $imagesize) { ?>
        <?php $random = theme1843_random(10); ?>
				<script type="text/javascript">
					jQuery(function(){
						jQuery('#gallery_post_<?php echo $random; ?>').cycle({
							pause: 1,
							fx: 'fade',
							timeout: 5000,
							pager:   '#g_pagination_<?php echo $random; ?>',
							prev:    '#prev-gal',
							next:    '#next-gal',
							pagerAnchorBuilder: pagerFactory
						});
						
						function pagerFactory(idx, slide) {
							return '<li><a href="#">'+(idx+1)+'</a></li>';
						};
					});
				</script>
				
				
					<!--BEGIN .slider -->
					<div id="gallery_post_<?php echo $random ?>" class="gallery_post">
					
					<?php 
							$args = array(
									'orderby'		 => 'menu_order',
									'order' => 'ASC',
									'post_type'      => 'attachment',
									'post_parent'    => get_the_ID(),
									'post_mime_type' => 'image',
									'post_status'    => null,
									'numberposts'    => -1,
							);
							$attachments = get_posts($args);
					?>
							
							<?php if ($attachments) : ?>
							
							<?php foreach ($attachments as $attachment) : ?>
									
									<?php 
									$attachment_url = wp_get_attachment_image_src( $attachment->ID, 'full' );
									$url = $attachment_url['0'];
									$image = aq_resize($url, 620, 400, true);
									?>
									
									<div class="g_item">
										<img 
										alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>"
										src="<?php echo $image ?>"
										width="620"
										height="400"
										/>
									</div>
							
							<?php endforeach; ?>
							
							<?php endif; ?>

					<!--END .slider -->
					</div>
					
					
					<!--BEGIN pagination -->
					<div class="g_pagination">
						<!--BEGIN Prev&Next -->
						<div class="g_controls">
							<a href="#"><span id="prev-gal"><?php _e('&larr;', 'theme1843'); ?></span></a> 
							<a href="#"><span id="next-gal"><?php _e('&rarr;', 'theme1843'); ?></span></a>
						</div><!--END Prev&Next -->
						<ul id="g_pagination_<?php echo $random ?>"></ul>
					</div><!--END pagination -->
					
					
					<div id="gal-controls">
						
					</div>
			
    <?php }
}

/*-----------------------------------------------------------------------------------*/
/*	Output Audio
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'tz_audio' ) ) {
    function tz_audio($postid) {
	
    	$mp3 = get_post_meta($postid, 'tz_audio_mp3', TRUE);
	
    ?>
		
    	    <div class="single-audio-holder">
						<audio src="<?php echo stripslashes(htmlspecialchars_decode($mp3)); ?>" preload="auto"></audio>
					</div>
    	<?php 
    }
}




/*-----------------------------------------------------------------------------------*/
/*	Pagination
/*-----------------------------------------------------------------------------------*/
function pagination($pages = '', $range = 4)
{ 
     $showitems = ($range * 2)+1; 
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }  
 
     if(1 != $pages)
     {
         echo "<div class=\"pagenavi\">";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."' class='first'>First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."' class='previous'>Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\" class='next'>Next</a>"; 
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."' class='last'>Last</a>";
         echo "</div>\n";
     }
}



// Custom Comments Structure
function mytheme_comment($comment, $args, $depth) {
     $GLOBALS['comment'] = $comment;

?> 
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>" class="comment-body">
      <div class="comment-author vcard">
         <?php echo get_avatar( $comment->comment_author_email, 88 ); ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
      <?php endif; ?>
      
      <div class="comment-meta commentmetadata"><?php printf(__('<span class="date">%1$s</span><span class="author">%2$s</span>' ), get_comment_date('d-m-Y'), get_comment_author_link()) ?></div>
      <?php comment_text() ?>

  <div class="wrapper">
  <div class="reply">
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
   </div>
 </div>


     </div>
<?php } ?>