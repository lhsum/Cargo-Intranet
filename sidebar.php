<aside id="sidebar" class="grid_4">
	
	<?php 
//if page slug == Home then else other slug 		
		if ($GLOBALS['slug'] =='home'){?>
<div>&nbsp;</div>
<div><!--<a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image1','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_welcome_on.jpg',1)">--
	--><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_welcome.jpg" width="294" height="54" id="Image1" /><!--</a>--></div>
	

	<div style="max-width:296px; z-index:-1;">
	<div class="easyhtml5video" style="max-width:296px; z-index:auto;">
		<video controls="controls"  poster="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/OrientationHK_2012.jpg" style="width:100%;" title="Orientation Video">
		<source src="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/OrientationHK_2012.m4v" type="video/mp4" />
		<source src="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/OrientationHK_2012.webm" type="video/webm" />
		<source src="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/OrientationHK_2012.ogv" type="video/ogg" />
	
		<object type="application/x-shockwave-flash" data="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/flashfox.swf" width="296" height="224" style="z-index:auto;">
			<param name="movie" value="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/flashfox.swf" />
			<param name="allowFullScreen" value="true" />
	 		<param name="wmode" value="opaque" />
			<param name="flashVars" value="autoplay=false&amp;controls=true&amp;fullScreenEnabled=true&amp;posterOnEnd=true&amp;loop=false&amp;poster=<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/OrientationHK_2012.jpg&amp;src=OrientationHK_2012.m4v" />
 			<embed src="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/flashfox.swf" width="296" height="224" style="z-index:auto;"  flashVars="autoplay=false&amp;controls=true&amp;fullScreenEnabled=true&amp;posterOnEnd=true&amp;loop=false&amp;poster=<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/OrientationHK_2012.jpg&amp;src=OrientationHK_2012.m4v"	allowFullScreen="true" wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer_en" />
			<img alt="OrientationHK 2012" src="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/OrientationHK_2012.jpg" style="left:0; " width="100%" title="Video playback is not supported by your browser" />
		</object>
	
		</video>
<script src="<?php echo $GLOBALS['imgPath'];?>/2013/07/html5video/html5ext.js" type="text/javascript"></script>


	
	<?php //echo do_shortcode('[video file="http://vimeo.com/70991412" width="294" height="230" color="bright_blue"]' ); ?>
		<!--<div class="video-wrap"><iframe src="http://player.vimeo.com/video/70991412?title=0&amp;byline=0&amp;portrait=0" width="294" height="230" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe></div>-->
	</div>	
	</div>
	
<div>
<!---- Search function --------------->
<?php
$dbh = new PDO('mysql:host=localhost;port=3306;dbname=staff;charset=utf8', 'root', '', array( PDO::ATTR_PERSISTENT => false));
?>
	<img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title_search_people.jpg" align="absmiddle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="search_advance" href="<?php echo get_permalink(1024);?>">Advance</a>
	<div>
	<?php if (qtrans_getLanguage() == 'en') {?>
    		<form method="post" action="./staff-search-results">
    	<?php }else{?>
    		<form method="post" action="./staff-search-results/?lang=zh">
    	<?php }?> 
		<div class="styled-select">
			<select name="department" id="department" class="select_text searchTrigger">
				<option value="">-- Department --</option>
					<?php
					// call the stored procedure
					$stmt = $dbh->prepare("SELECT DISTINCT department_id,department_name FROM department ORDER BY department_id ASC");
					
					$stmt->execute();

					while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
						print "<option value=\"" . $rs->department_id ."\"";

						print ">";
						print $rs->department_name;
						print "</option>\n";
					}
					?>
			</select>
		</div>
		<input class="input_search" type="text" name="search_name" placeholder=" -- Name -- "/> 

		<input class="search_submit"type="submit" name="button" id="button" value="GO" />
		
	</form>
</div>	
	
<div><a href="<?php echo get_permalink(930);?>" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image2','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_office_warehouse_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_office_warehouse.jpg" width="294" height="54" id="Image2" /></a></div>
	
<div>
	<table width="294" border="0" cellspacing="0" cellpadding="0" class="table_sidebar">
  <tr>
    <td><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title-staff-recognition.jpg" alt="" width="295" height="44" /></td>
  </tr>
  <tr>
    <td class="bg_slidebarbox">

<?php
//Get Staff Recognition Lastest post
$cat_id = get_cat_ID('Staff Recognition');
//get category ID form category Name

$args = array( 'posts_per_page' => 1,  'category' => $cat_id );

$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post ); 
$content = get_the_content();
$ptitle = get_the_title();
?>

		<font class="staff_month"><?php echo $ptitle; ?></font><br>
		<?php echo my_string_limit_words($content,29);?>

	</td>
  </tr>
  <tr>
    <td><a href="<?php the_permalink(); ?>"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title-more-bottom.jpg" alt="" width="295" height="39" /></a></td>
  </tr>
<?php endforeach; 
wp_reset_postdata();?>
  		
</table>
</div>		
	
<div>
<table width="294" border="0" cellspacing="0" cellpadding="0" class="table_birthday">
  <tr>
    <td><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title-birthday.jpg" alt="" width="294" height="53" /></td>
  </tr>
  <tr>
    <td class="bg_birthday">
    <marquee class="birthday_scrolling" behavior="scroll" direction="up" scrollamount="2">
<?php
//Get Birthday Lastest post  
$cat_id = get_cat_ID('Birthday');
//get category ID form category Name

$args = array( 'posts_per_page' => 1,  'category' => $cat_id );

$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post ); 
$content = get_the_content();
$ptitle = get_the_title();
?>    
<b><?php echo $ptitle; ?></b><br />
<?php echo $content;?>
	
	
</marquee>
    </td>
  </tr>
  <tr>
    <td><a href="<?php the_permalink(); ?>"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title_birthday-bottom.jpg" width="294" height="34" /></a></td>
  </tr>
<?php endforeach; 
wp_reset_postdata();?>    
</table>
</div>	
<div>
	<table width="294" border="0" cellspacing="0" cellpadding="0" class="table_sidebar">
  <tr>
    <td><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title-staff-movement.gif" alt="" width="295" height="44" /></td>
  </tr>
  <tr>
    <td class="bg_slidebarbox">
<?php
//Get Staff Promotion Lastest post
$cat_id = get_cat_ID('Staff Promotion');
//get category ID form category Name

$args = array( 'posts_per_page' => 1,  'category' => $cat_id );

$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post ); 
$content = get_the_content();
$ptitle = get_the_title();
?>    
	<font class="staff_month"><?php echo $ptitle; ?></font><br>
		<?php echo my_string_limit_words($content,29);?>


	</td>
  </tr>
  <tr>
    <td><a href="<?php the_permalink(); ?>"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title-more-bottom.jpg" alt="" width="295" height="39" /></a></td>
  </tr>
<?php endforeach; 
wp_reset_postdata();?>      
</table>
</div>		
<div>
	<table width="294" border="0" cellspacing="0" cellpadding="0" class="table_sidebar">
  <tr>
    <td><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title_whatsnews.jpg" alt="" width="295" height="31" /></td>
  </tr>
  <tr>
    <td class="bg_slidebarbox">
<?php
$cat_ann_id = get_cat_ID('News');
//get category ID form category Name

$args = array( 'posts_per_page' => 4,  'category' => $cat_ann_id );

$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post ); 
$content = get_the_content();
$ptitle = get_the_title();
?>

	<li class="ann_row">
		<?php get_template_part('includes/post-formats/post-meta'); ?>
		<a href="<?php the_permalink(); ?>" class="ann_title"><?php echo my_string_limit_words($ptitle,12); ?></a><br>
		<font class="ann_des"> <?php echo my_string_limit_words($content,12);?></font>
	</li>
  		 <?php endforeach; 
wp_reset_postdata();?>
	</td>		
    </tr>
  <tr>
    <td>
    	<?php if (qtrans_getLanguage() == 'en') {?>
    		<a href="./category/news/">
    	<?php }else{?>
    		<a href="./category/news/?lang=zh">
    	<?php }?> 	  
    <img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/title-more-bottom.jpg" alt="" width="295" height="39" /></a></td>
  </tr>

</table>
</div>

	
	<?php
		//Use other page slug select page
		//get permalink(1010) for each post or page for language select (en/zh)
		 }elseif  ($GLOBALS['slug']=='information-technology'){?>
		<div>&nbsp;</div>
		<div><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image4','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_itproject_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_itproject.jpg" width="294" height="54" id="Image4" /></a></div>	
		<div><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image5','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_applicationmenu_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_applicationmenu.jpg" width="294" height="54" id="Image5" /></a></div>	
		<div><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image6','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_userguide_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_userguide.jpg" width="294" height="54" id="Image6" /></a></div>	
		<div><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image7','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_magazine_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_magazine.jpg" width="294" height="54" id="Image7" /></a></div>	
	<?php }elseif  ($GLOBALS['slug'] =='hr-admin'){?>
		<div>&nbsp;</div>
		<div><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image8','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_leave_application_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_leave_application.jpg" width="294" height="54" id="Image8" /></a></div>	
		<div><a href="<?php echo $GLOBALS['imgPath'];?>/2013/07/EMPLOYEE GUIDE.pdf" target="_blank" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image9','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_empolyee_handbook_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_empolyee_handbook.jpg" width="294" height="54" id="Image9" /></a></div>	
		<!--<div><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image10','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_conferenceroom_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_conferenceroom.jpg" width="294" height="54" id="Image10" /></a></div>	-->
		<div><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image11','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_iso_information_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_iso_information.jpg" width="294" height="54" id="Image11" /></a></div>	
		<div><a href="<?php echo get_permalink(1054);?>" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image12','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_jobs_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_jobs.jpg" width="294" height="54" id="Image12" /></a></div>	
	<?php }elseif  ($GLOBALS['slug'] =='management-team'){?>
		<div>&nbsp;</div>
		<div><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image13','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_message_management_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_message_management.jpg" width="294" height="54" id="Image13" /></a></div>	
		<div><a href="<?php echo get_permalink(1010);?>" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image14','','<?php echo $GLOBALS['imgPath'];?>/2013/07/button_20_precepts_on.jpg',1)"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/button_20_precepts.jpg" width="294" height="54" id="Image14" /></a></div>
		<div>&nbsp;</div>
		<div><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/slogan_1_sds.jpg"/></div>
		<div><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/slogan_1_nsn.jpg"/></div>
	<?php }else{// if any new section side, please add here?>
			
	<?php	}?>
	
</aside><!--sidebar-->