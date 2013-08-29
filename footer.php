  </div><!--.container-->
</div>  
	<footer id="footer">
  	<div id="back-top-wrapper">
    	<p id="back-top">
        <a href="#top"><span></span></a>
      </p>
    </div>
	<?php if (is_front_page()) : ?>
	<div id="widget-footer">&nbsp;</div>
	<?php endif; ?>
		<div class="container_12 clearfix">
			<div id="copyright" class="clearfix">
				<div class="grid_13">
					<?php if ( of_get_option('footer_menu') == 'true') { ?>  
						<nav class="footer">
							<?php wp_nav_menu( array(
								'container'       => 'ul', 
								'menu_class'      => 'footer-nav', 
								'depth'           => 0,
								'theme_location' => 'footer_menu' 
								)); 
							?>
						</nav>
					<?php } ?>
					<div id="footer-text">
						<?php $myfooter_text = of_get_option('footer_text'); ?>
						
						<?php if($myfooter_text){?>
							<?php echo of_get_option('footer_text'); ?>
						<?php } else { ?>
								<!--<a href="#">CONTACT US</a><span class="divider">&nbsp;&nbsp;|&nbsp;&nbsp;</span> <a href="#">SITEMAP</a><span class="divider">&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="#">TERMS OF USE</a>
									<span class="divider">&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="#">DISCARMER</a><span class="divider">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
								<a href="#" title="Privacy Policy"><?php _e('Privacy Policy', 'theme1843'); ?></a><br>-->
							<a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('description'); ?>" class="site-name"><?php bloginfo('name'); ?></a> &copy; <?php echo date('Y'); ?> <?php _e('All Rights Reserved', 'theme1843'); ?>
						<?php } ?>
						<br />
					</div>
					
				</div>
			</div>
		</div><!--.container-->
	</footer>
</div><!--#main-->
<?php wp_footer(); ?> <!-- this is used by many Wordpress features and for plugins to work properly -->
<?php if(of_get_option('ga_code')) { ?>
	<script type="text/javascript">
		<?php echo stripslashes(of_get_option('ga_code')); ?>
	</script>
  <!-- Show Google Analytics -->	
<?php } ?>
</body>
</html>