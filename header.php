<!DOCTYPE html>
	
	<?php 
	  	  // Gobal Image Path
	  	  $GLOBALS['imgPath']="/intranet/wp-content/uploads";
	  	  $GLOBALS['miscPath']="/intranet/wp-content";
	  	  $GLOBALS['pagetitle']=the_title('','',0);
//get current page url	  	  
//function current_page_url() {
//	$pageURL = 'http';
//	if( isset($_SERVER["HTTPS"]) ) {
//		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
//	}
//	$pageURL .= "://";
//	if ($_SERVER["SERVER_PORT"] != "80") {
//		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
//	} else {
//		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
//	}
//	return $pageURL;
//}    	  	  
    ?>
<?php //echo current_page_url(); ?>
<?php 
// get page slug		
global $post;
$GLOBALS['slug'] = get_post( $post )->post_name;		

?>
		
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes();?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes();?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes();?>> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" <?php language_attributes();?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes();?>> <!--<![endif]-->
<head>
	<title><?php //if ( is_category() ) {
		//echo __('Category Archive for &quot;', 'theme1843'); single_cat_title(); echo __('&quot; | ', 'theme1843'); bloginfo( 'name' );
	//} elseif ( is_tag() ) {
		//echo __('Tag Archive for &quot;', 'theme1843'); single_tag_title(); echo __('&quot; | ', 'theme1843'); bloginfo( 'name' );
	//} elseif ( is_archive() ) {
		//wp_title(''); echo __(' Archive | ', 'theme1843'); bloginfo( 'name' );
	//} elseif ( is_search() ) {
		//echo __('Search for &quot;', 'theme1843').wp_specialchars($s).__('&quot; | ', 'theme1843'); bloginfo( 'name' );
	//} elseif ( is_home() || is_front_page()) {
		//bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
	//}  elseif ( is_404() ) {
		//echo __('Error 404 Not Found | ', 'theme1843'); bloginfo( 'name' );
	//} elseif ( is_single() ) {
		//wp_title('');
	//} else {
		//echo wp_title( ' | ', false, right ); bloginfo( 'name' );
	//} ?>Cargo Intranet</title>
	<meta name="description" content="<?php wp_title(); echo ' | '; bloginfo( 'description' ); ?>" />
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php if(of_get_option('favicon') != ''){ ?>
	<link rel="icon" href="<?php echo of_get_option('favicon', "" ); ?>" type="image/x-icon" />
	<?php } else { ?>
	<link rel="icon" href="<?php bloginfo( 'template_url' ); ?>/favicon.ico" type="image/x-icon" />
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'atom_url' ); ?>" />
	<?php /* The HTML5 Shim is required for older browsers, mainly older versions IE */ ?>
  <!--[if lt IE 8]>
    <div style=' text-align:center; '>
    	<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" alt="" /></a>
    </div>
  <![endif]-->
  <!-- IE 7 -->
  
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/css/normalize.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/css/prettyPhoto.css" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/css/grid.css" />
  
	<?php
		/* We add some JavaScript to pages with the comment form
		 * to support sites with threaded comments (when in use).
		 */
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	
		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head();
	?>
  <script type="text/javascript">
  	// initialise plugins
		jQuery(function(){
			// main navigation init
			jQuery('ul.sf-menu').superfish({
				delay:       <?php echo of_get_option('sf_delay'); ?>, 		// one second delay on mouseout 
				animation:   {opacity:'<?php echo of_get_option('sf_f_animation'); ?>'<?php if (of_get_option('sf_sl_animation')=='show') { ?>,height:'<?php echo of_get_option('sf_sl_animation'); ?>'<?php } ?>}, // fade-in and slide-down animation
				speed:       '<?php echo of_get_option('sf_speed'); ?>',  // faster animation speed 
				autoArrows:  <?php echo of_get_option('sf_arrows'); ?>,   // generation of arrow mark-up (for submenu) 
				dropShadows: false
			});
			
		});
		
		// Init for audiojs
		audiojs.events.ready(function() {
			var as = audiojs.createAll();
		});
		
		// Init for si.files
		SI.Files.stylizeAll();
  </script>
  
  <script type="text/javascript">
		jQuery(window).load(function() {
			// nivoslider init
			jQuery('#slider').nivoSlider({
				effect: '<?php echo of_get_option('sl_effect'); ?>',
				slices:<?php echo of_get_option('sl_slices'); ?>,
				boxCols:<?php echo of_get_option('sl_box_columns'); ?>,
				boxRows:<?php echo of_get_option('sl_box_rows'); ?>,
				animSpeed:<?php echo of_get_option('sl_animation_speed'); ?>,
				pauseTime:<?php echo of_get_option('sl_pausetime'); ?>,
				directionNav:<?php echo of_get_option('sl_dir_nav'); ?>,
				directionNavHide:<?php echo of_get_option('sl_dir_nav_hide'); ?>,
				controlNav:'.nivo-controlNav',
				controlNavThumbs: true,
				captionOpacity:1
			});
		});
	</script>
  
  
  <style type="text/css">
		
		<?php $background = of_get_option('body_background');
			if ($background != '') {
				if ($background['image'] != '') {
					echo 'body { background-image:url('.$background['image']. '); background-repeat:'.$background['repeat'].'; background-position:'.$background['position'].';  background-attachment:'.$background['attachment'].'; }';
				}
				if($background['color'] != '') {
					echo 'body { background-color:'.$background['color']. '}';
				}
			};
		?>
		
		<?php $header_styling = of_get_option('header_color'); 
			if($header_styling != '') {
				echo '#header {background-color:'.$header_styling.'}';
			}
		?>
		
		<?php $links_styling = of_get_option('links_color'); 
			if($links_styling) {
				echo 'a{color:'.$links_styling.'}';
				echo '.button {background:'.$links_styling.'}';
			}
		?>
  </style>
<script src="<?php echo $GLOBALS['miscPath']?>/magicscroll/magicscroll.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['miscPath']?>/magicscroll/magicscroll.css"/>
<script type="text/javascript">
    MagicScroll.options = {
       'width': 635,
       'height': 75,
       'items': 6,
       'step': 1,
    };
</script>
<script type="text/javascript">
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>

<!--<script type="text/javascript" src="<?php echo $GLOBALS['imgPath'];?>/2013/07/wowslider.js"></script>
	<script type="text/javascript" src="<?php echo $GLOBALS['imgPath'];?>/2013/07/script.js"></script>
	<script type="text/javascript" src="<?php echo $GLOBALS['imgPath'];?>/2013/07/jquery.js"></script>-->
	<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['imgPath'];?>/2013/07/style.css" />
</head>
	  
	  

<body <?php body_class(); ?>>

<div id="main"><!-- this encompasses the entire Web site -->
	<header id="header">
	
	<a href="<?php echo get_permalink(203);?>"><img src="<?php echo $GLOBALS['imgPath'];?>/2013/07/header_earth_w.jpg"></a>
		<div id="bar_lang"><?php echo qtrans_generateLanguageSelectCode('dropdown'); ?></div>
	<?php if ( of_get_option('g_search_box_id') == 'yes') { ?>  
		
          <div id="top-search">
          	  
            <!--<form method="get" action="<?php echo get_option('home'); ?>/">-->
              <input type="submit" value="" id="submit"><input type="text" name="s"  class="input-search"/>
            <!--</form>-->
          </div>  
        <?php } ?>
        <div id="widget-header">
        	<?php if ( ! dynamic_sidebar( 'Header' ) ) : ?><!-- Wigitized Header --><?php endif ?>
        	<!--Dirty Fix -- Hidden Wordpress Menu and Hardcode Menu for Popup New Windows https://trello.com/c/rJPWolCX/19-header-menu -->
<!--        	<div id="dc_jqmegamenu_widget-2" >		<div class="dcjq-mega-menu" id="dc_jqmegamenu_widget-2-item">
        			
		<ul id="menu-intranet-menu" class="menu"><li id="menu-item-833" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-203 current_page_item menu-item-833"><a href="<?php echo site_url();?>/">Home</a></li>
<li id="menu-item-834" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-834 dc-mega-li"><a href="#" class="dc-mega">Department<span class="dc-mega-icon"></span></a>
<div class="sub-container mega" style="left: 0px; top: 40px; z-index: 1000;"><ul class="sub-menu sub" style="display: none;">
	<div class="row" style="width: 846px;"><li id="menu-item-898" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-898 mega-unit mega-hdr" style="height: 168px;"><a href="<?php echo site_url();?>/department/hr-admin/" class="mega-hdr-a" style="height: 16px;">HR &amp; Admin</a>
	<ul class="sub-menu">
		<li id="menu-item-841" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-841"><a title="blank" href="/intranet/wp-content/uploads/2013/07/EMPLOYEE%20GUIDE.pdf" target="_blank">Employee Handbook</a></li>
		<li id="menu-item-843" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-843"><a href="#">eLeave</a></li>
		<li id="menu-item-844" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-844"><a href="#">ISO Information</a></li>
		<li id="menu-item-902" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-902"><a href="#">Jobs Openings</a></li>
	</ul>
</li><li id="menu-item-884" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-884 mega-unit mega-hdr" style="height: 168px;"><a href="<?php echo site_url();?>/department/information-technology/" class="mega-hdr-a" style="height: 16px;">Information Technology</a>
	<ul class="sub-menu">
		<li id="menu-item-846" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-846"><a href="#">IT Projects</a></li>
		<li id="menu-item-848" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-848"><a href="#">System User Guide</a></li>
		<li id="menu-item-847" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-847"><a href="#">Application Main Menu</a></li>
		<li id="menu-item-849" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-849"><a href="#">IT Quarterly Magazine</a></li>
	</ul>
</li><li id="menu-item-850" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-850 mega-unit mega-hdr last" style="height: 168px;"><a href="#" class="mega-hdr-a" style="height: 16px;">GAM</a></li></div>
	
	
	<div class="row" style="width: 846px;"><li id="menu-item-851" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-851 mega-unit mega-hdr" style="height: 58px;"><a href="#" class="mega-hdr-a" style="height: 16px;">Corporate Marketing</a></li><li id="menu-item-852" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-852 mega-unit mega-hdr" style="height: 58px;"><a href="#" class="mega-hdr-a" style="height: 16px;">Finance</a></li><li id="menu-item-853" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-853 mega-unit mega-hdr last" style="height: 58px;"><a href="#" class="mega-hdr-a" style="height: 16px;">Marketing &amp; Sales</a></li></div>
	
	
	<div class="row" style="width: 846px;"><li id="menu-item-854" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-854 mega-unit mega-hdr" style="height: 58px;"><a href="#" class="mega-hdr-a" style="height: 16px;">Seafregiht Operation</a></li><li id="menu-item-855" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-855 mega-unit mega-hdr last" style="height: 58px;"><a href="#" class="mega-hdr-a" style="height: 16px;">Airfreight Operation</a></li></div>
	
</ul></div>
</li>
<li id="menu-item-835" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-835"><a href="/intranet/advance-search/">People</a></li>
<li id="menu-item-836" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-836"><a href="/intranet/wp-content/uploads/2013/07/EMPLOYEE%20GUIDE.pdf" target="_blank">Employee Handbook</a></li>
<li id="menu-item-838" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-838"><a href="/intranet/management-team/">Management Team</a></li>
<li id="menu-item-839" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-839 dc-mega-li"><a href="/intranet/location/" class="dc-mega">Global Office<span class="dc-mega-icon"></span></a>
<div class="sub-container mega" style="right: 0px; top: 40px; z-index: 1000;"><ul class="sub-menu sub" style="display: none;">
	<div class="row" style="width: 564px;"><li id="menu-item-856" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-856 mega-unit mega-hdr" style="height: 102px;"><a href="#" class="mega-hdr-a" style="height: 16px;">China</a>
	<ul class="sub-menu">
		<li id="menu-item-858" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-858"><a href="#">Shenzhen</a></li>
		<li id="menu-item-857" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-857"><a href="#">Foshan</a></li>
	</ul>
</li><li id="menu-item-860" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-860 mega-unit mega-hdr last" style="height: 102px;"><a href="#" class="mega-hdr-a" style="height: 16px;">Australia</a></li></div>
	
</ul></div>
</li>
</ul>		
		</div>
		</div>
        	
    -->    	
        </div><!--#widget-header-->
		<div class="container_12 clearfix">
			<div class="grid_12">
	

      </div>
		</div><!--.container_12-->
	</header>
		  
  <div class="primary_content_wrap">
	<div class="container_12 clearfix">