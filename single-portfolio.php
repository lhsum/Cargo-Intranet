<?php get_header(); ?>

<div class="grid_12">

	<!--BEGIN .page-header -->
	<div class="page-header header-title">
			    
		<h1><?php the_title(); ?></h1>
		
	<!--END .page-header -->
	</div>
	
	
	
			
  <!--BEGIN #primary .hfeed-->
	<div id="primary" class="hfeed">
		
		<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				
			<!--BEGIN .hentry -->
			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">				
					
        <div class="two_third">
					
					<?php // get the media elements
            $mediaType = get_post_meta($post->ID, 'tz_portfolio_type', true);
                    	
						switch( $mediaType ) {
							case "Image":
								tz_image($post->ID, 'portfolio-main');
								break;

							case "Slideshow":
								tz_gallery($post->ID, 'portfolio-main');
								break;
								
							case "Grid Gallery":
								tz_grid_gallery($post->ID, 'portfolio-main');
								break;

							case "Video":
								$embed = get_post_meta($post->ID, 'tz_portfolio_embed_code', true);
								echo "<div class='video-holder'>";
								echo stripslashes(htmlspecialchars_decode($embed));
								echo "</div>";
								break;

							case "Audio":
								tz_audio($post->ID);
								break;

							default:
								break;
						}
					?>
					
					
					<!--BEGIN .oldernewer .single-oldernewer -->
					<nav class="oldernewer single-oldernewer">
						
						<?php if( get_previous_post() ) : ?>
						<div class="older"><?php previous_post_link('&larr; %link (previous entry)') ?></div>
						<?php endif; ?>
						
						<?php if( get_next_post() ) : ?>
						<div class="newer"><?php next_post_link('(next entry) %link &rarr;') ?></div>
						<?php endif; ?>

					<!--END .oldernewer .single-oldernewer -->
					</nav>
					
					
				</div>
				
				

				<!-- BEGIN .entry-content -->
				<div class="entry-content one_third last">
								
					<!-- BEGIN .entry-meta -->
					<div class="entry-meta">

						<?php 
							// get the meta information and display if supplied
							$portfolioClient = get_post_meta($post->ID, 'tz_portfolio_client', true);
							$portfolioDate = get_post_meta($post->ID, 'tz_portfolio_date', true); 
							$portfolioInfo = get_post_meta($post->ID, 'tz_portfolio_info', true); 
							$portfolioURL = get_post_meta($post->ID, 'tz_portfolio_url', true);
							
							
							if (!empty($portfolioClient) || !empty($portfolioDate) || !empty($portfolioInfo) || !empty($portfolioURL)){
								echo '<ul class="portfolio-meta-list">';
								}
								
							if( !empty($portfolioClient) ) {
									echo '<li>';
									echo '<strong class="portfolio-meta-key">' . __('Client:', 'framework') . '</strong>';
									echo '<span>' . $portfolioClient . '</span><br />';
									echo '</li>';
								}

							if( !empty($portfolioDate) ) {
									echo '<li>';
									echo '<strong class="portfolio-meta-key">' . __('Date:', 'framework') . '</strong>';
									echo '<span>' . $portfolioDate . '</span><br />';
									echo '</li>';
							}
							
							if( !empty($portfolioInfo) ) {
									echo '<li>';
									echo '<strong class="portfolio-meta-key">' . __('Info:', 'framework') . '</strong>';
									echo '<span>' . $portfolioInfo . '</span><br />';
									echo '</li>';
							}

							if( !empty($portfolioURL) ) {
									echo '<li>';
									echo "<a href='$portfolioURL'>" . __('Launch Project', 'framework') . "</a>";
									echo '</li>';
							}
							
							if (!empty($portfolioClient) || !empty($portfolioDate) || !empty($portfolioInfo) || !empty($portfolioURL)){
								echo '</ul>';
							}
						?>

					<!-- END .entry-meta -->
					</div>
						
					<?php the_content(); ?>

				<!-- END .entry-content -->
				</div>                
				<!--END .hentry-->  
					
					
				</div>

				<?php endwhile; endif; ?>
				

	<!--END #primary .hfeed-->
	</div>
	

</div>

<?php get_footer(); ?>