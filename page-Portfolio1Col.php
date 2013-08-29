<?php
/**
 * Template Name: Portfolio 1 column
 */

get_header(); ?>

<div id="content" class="grid_12">
	<?php include_once (TEMPLATEPATH . '/title.php');?>   
  <?php global $more;	$more = 0;?>
  <?php $values = get_post_custom_values("category-include"); $cat=$values[0];  ?>
  <?php $catinclude = 'portfolio_category='. $cat ;?>
  
  <?php $temp = $wp_query;
	$wp_query= null;
	$wp_query = new WP_Query(); ?>
  <?php $wp_query->query("post_type=portfolio&". $catinclude ."&paged=".$paged.'&showposts=5'); ?>
  <?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'theme1843' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'theme1843' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>


<!-- BEGIN Gallery -->
<div id="gallery" class="one_column">
  <ul class="portfolio">
    <?php 
      $i=1;
      if ( have_posts() ) while ( have_posts() ) : the_post(); 
    ?>
    
      <li class="<?php echo $addclass; ?>">
				<?php
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
				$image = aq_resize( $img_url, 480, 360, true ); //resize & crop img
				
				//mediaType init
				$mediaType = get_post_meta($post->ID, 'tz_portfolio_type', true);
			 
			 
			 //check thumb and media type
				if(has_post_thumbnail($post->ID) && $mediaType != 'Video' && $mediaType != 'Audio'){ 
				
					
					//Disable overlay_gallery if we have Image post
					$prettyType = 0;
					
					if($mediaType != 'Image') { 
						
						$prettyType = "prettyPhoto[gallery".$i."]";
						
					} else { 
						
						$prettyType = 'prettyPhoto';
					
					} ?>
				
				
        <span class="image-border"><a class="image-wrap" href="<?php echo $img_url;?>" rel="<?php echo $prettyType; ?>" title="<?php the_title();?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" /><span class="zoom-icon"></span></a></span>
				
				
				<?php
					$thumbid = 0;
					$thumbid = get_post_thumbnail_id($post->ID);
				
					$images = get_children( array(
						'orderby' => 'menu_order',
						'order' => 'ASC',
            'post_type' => 'attachment',
            'post_parent' => $post->ID,
            'post_mime_type' => 'image',
            'post_status' => null,
            'numberposts' => -1
					) ); 
						/* $images is now a object that contains all images (related to post id 1) and their information ordered like the gallery interface. */
						if ( $images ) { 

							//looping through the images
							foreach ( $images as $attachment_id => $attachment ) {
							
							 if( $attachment->ID == $thumbid ) continue;
							?>
								<?php 
								$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); // returns an array
								$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
								$image_title = $attachment->post_title;
								?>
									
								<a href="<?php echo $image_attributes[0]; ?>" title="<?php the_title(); ?>" rel="<?php echo $prettyType; ?>" alt="<?php echo $alt; ?>" style="display:none;"><img src="<?php echo $image_attributes[0]; ?>" alt="<?php echo $alt; ?>"/></a>

							<?php
							}
					}
		
				?>
				
				<?php }else { ?>
				
        <span class="image-border"><a class="image-wrap" href="<?php the_permalink() ?>" title="<?php _e('Permanent Link to', 'theme1843');?> <?php the_title_attribute(); ?>" ><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" /></a></span>
				
        <?php } ?>
				
				
        <div class="folio-desc">
					<h3><a href="<?php the_permalink(); ?>"><?php $title = the_title('','',FALSE); echo substr($title, 0, 40); ?></a></h3>
					<p class="excerpt"><?php $content = get_the_content(); echo my_string_limit_words($content,120);?></p>
					<a href="<?php the_permalink() ?>" class="button"><?php _e('Read more', 'theme1843'); ?></a>
				</div>
				
				
      </li>
    
  
    <?php $i++; $addclass = ""; endwhile; ?>
  </ul>
  <div class="clear"></div>
	
</div><!-- END Gallery -->



<?php get_template_part('includes/post-formats/post-nav'); ?>
<!-- Posts Navigation -->


<?php $wp_query = null; $wp_query = $temp;?>
</div><!-- #content -->
<!-- end #main -->
<?php get_footer(); ?>