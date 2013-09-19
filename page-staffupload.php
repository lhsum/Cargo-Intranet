<?php
get_header(); 

/**
 * Template Name: Staff Upload
 */
error_reporting (E_ALL ^ E_NOTICE);

$searchName = $_POST["search_name"];
$department = $_POST["department"];

$dbh = new PDO('mysql:host=localhost;port=3306;dbname=staff;charset=utf8', 'root', '', array( PDO::ATTR_PERSISTENT => false));

		
  ?>


  
<div class="<?php echo of_get_option('blog_sidebar_pos') ?>">
    <div id="content" class="grid_13 right <?php echo of_get_option('blog_sidebar_pos') ?>">

        <div class="header-title">
    		<font class="location">	<img src="<?php echo $imgPath;?>/2013/07/icon_location_bar_arrow.gif" align="absmiddle">
    		<?php if (qtrans_getLanguage() == 'en') {?>
    		<a href="/intranet/">Home</a> >
    		<?php }else{?>
    		<a href="/intranet/?lang=zh">主页</a> >	
    		<?php }?> <?php echo get_the_title(); ?>
    		</font>
			<br>
			<!---- Search-staff--->
			<!--<script src="/intranet/wp-content/themes/Cargo-Intranet/js/jquery-1.7.2.min.js"></script>-->
			<script src="/intranet/wp-content/themes/Cargo-Intranet/js/jquery.form.js"></script> 
			<script src="/intranet/wp-content/themes/Cargo-Intranet/js/waypoints.min.js"></script>
			<script> 
			// wait for the DOM to be loaded 
			$(document).ready(function() { 
				var searchFlag = false;
				$('#simpleSearchForm').ajaxForm(function(data) {
					
					$('#searchResult').waypoint('destroy');
					
					var dataObject = jQuery.parseJSON(data);
					
					$('#searchInformation').find('#lblSearchKeyword').text(dataObject.searchKeywords);
					$('#searchInformation').find('#lblRecordCount').text(dataObject.recordCount);
					
					if(parseInt(dataObject.recordCount) <= 0){
						$('#searchInformation').hide();
						$('#searchNoResult').show();
						$('#searchResult').empty();
					}else if(dataObject.haveMoreRecords){
						if(parseInt(dataObject.from) == 0){
							$('#searchResult').empty();
						}
						$('#searchInformation').show();
						$('#searchNoResult').hide();
						$('#searchResult').show();
						$('#searchResult').append(dataObject.htmlCode);
						$('#searchResult').waypoint(function() {
							searchFlag = true;
						}, { offset: 'bottom-in-view' });
						
						$('#group_' + dataObject.from).find('.lblGroup').html(dataObject.recordGroup);
						
					}
				}); 
				$('.searchTrigger').change(function(){
					$('#simpleSearchForm').find('input[name="from"]').val(0);
				});
				setInterval(function(){
					if(searchFlag){
						$('#simpleSearchForm').find('input[name="from"]').val(parseInt($('#simpleSearchForm').find('input[name="from"]').val()) + 20);
						$('#simpleSearchForm').submit();
						searchFlag = false;
					}
				},250);
				
				$('#simpleSearchForm').submit();
			}); 
			</script> 
			<style type="text/css">
			*{
				font-family:Verdana, Geneva, sans-serif;
				font-size: 12px;
			}
			td{
				vertical-align:top;
			}
			.staff_search_result{
				padding: 10px;
				border: 2px;
				border-color: #00b5c7;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
				border-radius: 5px;
				border-style: solid;
				margin-bottom:4px;
				margin-right: 14px;
			}

			</style>
	<form method="post" action="/intranet/wp-content/themes/Cargo-Intranet/staff_search_ajax.php" id="simpleSearchForm">
		<select name="department" style="margin:0px;padding:1px;" class="searchTrigger">
		<option value="">-- Department --</option>
			<?php
			// call the stored procedure
			$stmt = $dbh->prepare("SELECT DISTINCT department_id,department_name FROM department ORDER BY department_id ASC");
			
			$stmt->execute();

			while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
				print "<option value=\"" . $rs->department_id ."\"";
				if($rs->department_id == $department){
					print 'SELECTED="SELECTED"';
				}
				print ">";
				print $rs->department_name;
				print "</option>\n";
			}
			?>
		</select>
		<br/>
		<input type="text" name="search_name" placeholder=" -- Name -- " style="width:200px" class="searchTrigger" value="<?=$searchName?>"/> 

		<input type="submit" value="Search" />
		<input type="hidden" name="from" value="0"/>
	</form>
	<br/>
	<div id="searchInformation" style="display:none">
		Search Keyword(s) : <span id="lblSearchKeyword"></span><br />

		Total: <span id="lblRecordCount"></span> record(s) found.

		</div>
		<div id="searchNoResult" style="display:none">
		<br/>
		No record found. Please search again or try <a href="/intranet/advance-search/">Advance Search. </a>
	</div>
	
	<div id="searchResult">
	
	</div>
	<br/>
		</div>	
			                  <!-- //.post-content -->
	</div><!--#content-->
			
<?php get_sidebar(); ?>
</div>			
<?php get_footer(); ?>