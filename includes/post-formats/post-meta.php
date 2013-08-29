<?php $category = get_the_category(); ?>


    <?php $post_meta = of_get_option('post_meta'); ?>
		<?php if ($post_meta=='true' || $post_meta=='') { ?>
			<div class="post-meta">
				<time datetime="<?php the_time('Y-m-d\TH:i'); ?>"><?php the_time('d-m-Y'); ?></time> | 
				<?php if($category[1]){
					echo $category[1]->name;
				}?>
					<?php //the_author(); ?><?php//comments_popup_link('No comments', '1 comment', '% comments', 'comments-link', 'Comments are closed'); ?>
				
			</div><!--.post-meta-->
		<?php } ?>		
