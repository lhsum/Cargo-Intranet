<?php
/**
 * Template Name: Home Page
 */

get_header(); 
  ?>


  
<div class="<?php echo of_get_option('blog_sidebar_pos') ?>">
    <div id="content" class="grid_8 <?php echo of_get_option('blog_sidebar_pos') ?>">
	<div class="weather">
	<?php //echo do_shortcode('[awesome-weather location="Hong Kong, HK" units="C" override_title="Hong Kong" forecast_days=0]'); ?>
	<?php echo do_shortcode('[awesome-weather location="Hong Kong, HK" units="C" size="wide" override_title="Hong Kong" forecast_days=5 hide_stats=1 show_link=0]'); ?>
	</div>			
                    <div class="header-title">
<font class="location">
<img src="<?php echo $imgPath;?>/2013/07/icon_location_bar_arrow.gif" align="absmiddle"><?php the_title(); ?></font>
<br>
<font class="date_clock">
	<img src="<?php echo $imgPath;?>/2013/07/icon_date.jpg" align="absmiddle">
	<?php
    	$date = new DateTime();
		date_timezone_set($date, timezone_open('Asia/Hong_Kong'));
    	echo date_format($date, 'l\, j F Y') . "\n<br>";
	?>				
	</font>		
	<font class="title-welcome">
	<?php
	date_default_timezone_set('Asia/Hong_Kong');
	$current_time = date("G");
	if (qtrans_getLanguage() == 'en') {
		if ($current_time < "12") {echo "<font color='#ff9600'>Good Morning</font>";}
		else if ($current_time < "17") {echo "<font color='#00afce'>Good Afternoon</font>";}
		else {echo "<font color='#ff0000'>Good Evening</font>";}
		?>
		, welcome to Cargo Intranet!   
	<?php }else if(qtrans_getLanguage() == 'zh') {
		if ($current_time < "12") {echo "<font color='#ff9600'>早安</font>";}
		else if ($current_time < "17") {echo "<font color='#00afce'>午安</font>";}
		else {echo "<font color='#ff0000'>晚安</font>";}
		?>
		, 欢迎来到Cargo 内部网络!   
	<?php }?>
	
	</font>

	
		    </div>
	<?php if( is_front_page() ) { ?>
		<div class="MagicScroll">
			<?php if (qtrans_getLanguage() == 'en') {?>
				<a href="#"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_News.gif"/>
				News</a>
				<a href="<?php echo $GLOBALS['imgPath'];?>/2013/07/EMPLOYEE GUIDE.pdf" target="_blank"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_Handbook.gif"/>
				Empolyee Guide</a>
				<a href="<?php echo get_permalink(1024);?>"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_people.gif"/>
				People				</a>
				<a href="http://sharepoint" target="_blank"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_public_folder.gif"/>
				Public</a>
				<a href="<?php echo get_permalink(920);?>"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_Album.gif"/>
				Album</a>
				<a href="#"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_calendar.gif"/>
				Calendar</a>
			<?php }elseif (qtrans_getLanguage() == 'zh'){?>
				<a href="#"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_News.gif"/>
				新闻</a>
				<a href="<?php echo $GLOBALS['imgPath'];?>/2013/07/EMPLOYEE GUIDE.pdf" target="_blank"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_Handbook.gif"/>
				员工指引</a>
				<a href="<?php echo get_permalink(1024);?>"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_people.gif"/>
				人员</a>
				<a href="https://sharepoint.cargofe.com" target="_blank"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_public_folder.gif"/>
				共享</a>
				<a href="<?php echo get_permalink(920);?>"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_Album.gif"/>
				相簿</a>
				<a href="#"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/Icon_calendar.gif"/>
				日历</a>
			<?php }?>
							
		</div>
			
	
<section id="slider-wrapper">
	
			
<!--			<div id="wowslider-container1">
	<div class="ws_images"><ul>
<li><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/slide1.jpg" alt="slide1" title="slide1" id="wows1_0"/></li>
<li><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/slide2.jpg" alt="slide2" title="slide2" id="wows1_1"/></li>
<li><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/slide3.jpg" alt="slide3" title="slide3" id="wows1_2"/></li>
</ul></div>
		</div>-->

	
      		<?php include_once(TEMPLATEPATH . '/slider.php'); ?>
  	
  </section>
  				<!--#slider-->
	<?php } ?>                  
	  
	<div>  
	<table width="635" border="0" cellspacing="0" cellpadding="0">
  	<tr >
 	   <td class="bg_announcement" width="295">
		
 	   <img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title_announcement<?php if (qtrans_getLanguage() != 'en') { echo "_zh";}?>.jpg" width="185" height="32" align="absmiddle" />
		<?php if (qtrans_getLanguage() == 'en') {?>
    		<a href="./category/announcement/" class="title_more">More >>
    	<?php }else{?>
    		<a href="./category/announcement/?lang=zh" class="title_more">More >>
    	<?php }?> 	    
 	    </a>
    </td>
  </tr>
  <tr>
    <td class="bg_announcementbox">
<?php
$category_ids = get_all_category_ids();
foreach($category_ids as $cat_id) {
  $cat_name = get_cat_name($cat_id);
  //echo $cat_id . ': ' . $cat_name;
  //Test Category ID
}

?>

<?php
$cat_ann_id = get_cat_ID('Announcement');
//get category ID form category Name

$args = array( 'posts_per_page' => 4,  'category' => $cat_ann_id );

$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post ); 
$content = get_the_content();
$ptitle = get_the_title();
?>

	<li class="ann_row">
		<?php if(has_post_thumbnail()) { ?>
			<?php
			$thumb = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
			$image = aq_resize( $img_url, 620, 340, true ); //resize & crop img
			
		?>
			<figure class="ann_img">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" /></a>
			</figure>	
		<?php }else{?>
		<!--	<figure class="ann_img">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="./wp-content/uploads/2013/07/update_news1.gif" alt="<?php the_title(); ?>" /></a>
			</figure>	-->
		<?php }?>
		<?php get_template_part('includes/post-formats/post-meta'); ?>
		<a href="<?php the_permalink(); ?>" class="ann_title"><?php echo my_string_limit_words($ptitle,9); ?></a><br>
		<font class="ann_des"> <?php echo my_string_limit_words($content,20);?></font>
	</li>
<?php endforeach; 
wp_reset_postdata();?>

    </td>
  </tr>
  <tr>
    <td><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title_announcement_bottom.jpg" width="646" height="14" /></td>
  </tr>
</table>
<table width="100%" style="margin-bottom:3em;"><tr><td valign="top">
<table width="321" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="321"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title_company_org<?php if (qtrans_getLanguage() != 'en') { echo "_zh";}?>.jpg" alt="" width="321" height="43" /></td>
    <td></td>
  </tr>
  <tr>
    <td class="bg_conentbox" align="center"><a href="#"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/org_chart.jpg" alt=""  width="250"/></a></td>
  </tr>
  <tr>
    <td><a href="#"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/content_box_bottom.jpg" alt="" width="321" height="36" /></a></td>
  </tr>
</table>
</td><td>
<table width="321" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="321"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title_orientation<?php if (qtrans_getLanguage() != 'en') { echo "_zh";}?>.jpg" alt="" width="321" height="43" /></td>
    <td></td>
  </tr>
  <tr>
    <td class="bg_conentbox"><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Orientation_May 2013</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> 20 Precepts</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Message From CEO</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Management Team</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Management Team</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Management Team</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Management Team</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Management Team</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Management Team</a><br>
    	<a href="#" class="materials_title"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/icon_ppt.jpg" alt="" align="absmiddle"> Orientation_May 2013</a><br>
    </td>
  </tr>
  <tr>
    <td><a href="#"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/content_box_bottom.jpg" alt="" width="321" height="36" /></a></td>
  </tr>
</table>
</td></tr></table>				
		</div>                    <!-- //.post-content -->
	</div><!--#content-->
			
<?php get_sidebar(); ?>
</div>			
<?php get_footer(); ?>