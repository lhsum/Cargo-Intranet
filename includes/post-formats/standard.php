<?php
//get Parnet title & Category
$parent_title = get_the_title($post->post_parent);
$this_page_title=get_the_title();
$category = get_the_category(); 

?>
	
			<?php if(is_singular()) : ?>
			
			<header class="header-title-post">
				
				<?php if(!is_singular()) : ?>
				
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'theme1843');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				
				<?php else :?>
<!-- Location Path  -->
    				<font class="location">	<img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_location_bar_arrow.gif" align="absmiddle">
					<?php if (qtrans_getLanguage() == 'en') {?>
    					<a href="/intranet/">Home</a> >
    				<?php }else{?>
    					<a href="/intranet/?lang=zh">主页</a> >	
    				<?php }?>
    				<?php if ($this_page_title==$parent_title){?>
    					<?php echo '<a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a>'; ?>  > 

    				<?php }else{?>
				 		
				 		<a href="../"><?php echo $parent_title;?></a> > <?php echo $this_page_title; ?>
					<?php }?>
					</font>
<!-- Location Path  -->
				
				<?php endif; ?>

				</header>
		
			<?php endif; ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class('post-holder'); ?>>
				
				<?php get_template_part('includes/post-formats/post-thumb'); ?>
				<?php get_template_part('includes/post-formats/post-meta'); ?>
				<?php if(is_singular()) : ?>
					<font class="post-title"><?php echo $this_page_title;?></font><br>
					<?php// get_template_part('includes/post-formats/post-meta'); ?>
				<?php endif; ?>			
				<?php if(!is_singular()) : ?>
				
					<header class="entry-header">
				
					<?php if(!is_singular()) : ?>
				
						<a href="<?php the_permalink(); ?>" class="ann_title" title="<?php _e('Permalink to:', 'theme1843');?> <?php the_title(); ?>"><?php the_title(); ?></a>
				
					<?php else :?>
				
						<h1 class="entry-title"><?php the_title(); ?></h1>
				
					<?php endif; ?>
				
					
				
					</header>
				
				<?php endif; ?>
				
				<?php if(!is_singular()) : ?>
				
				<div class="post-content">
					<?php $post_excerpt = of_get_option('post_excerpt'); ?>
					<?php if ($post_excerpt=='true' || $post_excerpt=='') { ?>
					
						<div class="excerpt">
						
						
						<?php 
						
						$content = get_the_content();
						$excerpt = get_the_excerpt();

						if (has_excerpt()) {

								the_excerpt();

						} else {
						
								if(!is_search()) {

								echo my_string_limit_words($content,20);
								
								} else {
								
								echo my_string_limit_words($excerpt,30);
								
								}

						}
						
						
						?>
						
						</div>
						
						
					<?php } ?>
					<a href="<?php the_permalink() ?>" class="button"><?php _e('Read more', 'theme1843'); ?></a>
				</div>
				
				<?php else :?>
				
				<div class="content">
				
					<?php the_content(''); ?>
<!--- PREVIOUS: &  Next Post --> 						
>> Post : <?php next_post('%', '', 'yes', 'yes'); ?>	<br />
<< Post : <?php previous_post('%', '', 'yes', 'yes'); ?>

						
<!--- PREVIOUS: &  Next Post -->						
				<!--// .content -->
				</div>
				
				<?php endif; ?>

			 
			</article>