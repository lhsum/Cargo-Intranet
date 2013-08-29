<?php get_header(); ?>
<div id="content">
	<div id="error404" class="clearfix">
  	<div class="error404-num">404</div>
    <div class="">
    	<hgroup>
        <?php echo '<h1>' . __('Sorry!', 'theme1843') . '</h1>'; ?>
        <?php echo '<h2>' . __('Page Not Found', 'theme1843') . '</h2>'; ?>
      </hgroup>
      <?php echo '<h4>' . __('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'theme1843') . '</h4>'; ?>
      <?php //echo '<p>' . __('Please try using our search box below to look for information on the internet.', 'theme1843') . '</p>'; ?>
      <?php //get_search_form(); /* hide search outputs the default Wordpress search form */ ?>
    </div>
	</div><!--#error404 .post-->
</div><!--#content-->
<?php get_footer(); ?>