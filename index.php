<?php get_header(); ?>

<div class="vr <?php echo of_get_option('blog_sidebar_pos') ?>">
	<div id="content" class="grid_9 <?php echo of_get_option('blog_sidebar_pos') ?>">
	
  	<div class="inner">
		<?php include_once (TEMPLATEPATH . '/title.php');?> 
		
		
		<?php 
                
			if (have_posts()) : while (have_posts()) : the_post(); 
			
					// The following determines what the post format is and shows the correct file accordingly
					$format = get_post_format();
					get_template_part( 'includes/post-formats/'.$format );
					
					if($format == '')
					get_template_part( 'includes/post-formats/standard' );
					
			 endwhile; else:
			 
			 ?>
			 
			 <div class="no-results">
				<?php echo '<p><strong>' . __('There has been an error.', 'theme1843') . '</strong></p>'; ?>
        <p><?php _e('We apologize for any inconvenience, please', 'theme1843'); ?> <a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('description'); ?>"><?php _e('return to the home page', 'theme1843'); ?></a> <?php _e('or use the search form below.', 'theme1843'); ?></p>
        <?php get_search_form(); /* outputs the default Wordpress search form */ ?>
      </div><!--no-results-->
			
    <?php endif; ?>
      
    
    <?php get_template_part('includes/post-formats/post-nav'); ?>
	</div>

	</div><!--#content-->
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>