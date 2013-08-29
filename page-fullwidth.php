<?php
/**
 * Template Name: Fullwidth Page
 */

get_header(); ?>
<?php
//get Parnet title
$parent_title = get_the_title($post->post_parent);
$this_page_title=get_the_title();
?>	

<div id="content" class="grid_13 right">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
        <?php if(has_post_thumbnail()) {
          echo '<a href="'; the_permalink(); echo '">';
          echo '<figure class="featured-thumbnail"><span class="img-wrap">'; the_post_thumbnail(); echo '</span></figure>';
          echo '</a>';
          }
        ?>
        <div class="header-title">
    		<font class="location">	<img src="<?php echo $imgPath;?>/2013/07/icon_location_bar_arrow.gif" align="absmiddle">
    		<?php if (qtrans_getLanguage() == 'en') {?>
    		<a href="/intranet/">Home</a> >
    		<?php }else{?>
    		<a href="/intranet/?lang=zh">主页</a> >	
    		<?php }?>
    		
    		<?php if ($this_page_title==$parent_title){?>
    			 <?php echo $this_page_title;?>
    		<?php }else{?>
				 <a href="../"><?php echo $parent_title;?></a> > <?php echo $this_page_title; ?>
			<?php }?>
			</font>
			<?php if ($parent_title=='Department'){?><div class="locationbar_icon"><a href="#"><img src="<?php echo $imgPath;?>/2013/07/icon_phonebook.jpg" align="absmiddle"></a> <a href="#"><img src="<?php echo $imgPath;?>/2013/07/icon_email.jpg" align="absmiddle"></a></div><?php };?>
			<br>
		</div>
	
  
				<?php the_content(); ?>
        <div class="pagination">
          <?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
        </div><!--.pagination-->
    </div><!--#post-# .post-->

  <?php endwhile; ?>
</div><!--#content-->
<?php get_footer(); ?>
