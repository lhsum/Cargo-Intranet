<?php get_header(); ?>
<div class=" <?php echo of_get_option('blog_sidebar_pos') ?>">
	<div id="content" class="grid_13 <?php echo of_get_option('blog_sidebar_pos') ?>">
		
		
		<div class="inner">
			<?php 
                
			if (have_posts()) : while (have_posts()) : the_post(); 
			
					// The following determines what the post format is and shows the correct file accordingly
					$format = get_post_format();
					get_template_part( 'includes/post-formats/'.$format );
					
					if($format == '')
					get_template_part( 'includes/post-formats/standard' ); ?>
					
					
		<?php get_template_part( 'includes/post-formats/related-posts' ); ?>

					
    
		<?php// comments_template('', false); ?>
		
		
		<?php endwhile; endif; ?>
		</div>
    

	</div><!--#content-->
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>