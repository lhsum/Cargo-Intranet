<?php
get_header(); 

/**
 * Template Name: Staff Upload
 */

$dbh = new PDO('mysql:host=localhost;port=3306;dbname=staff;charset=utf8', 'root', '', array( PDO::ATTR_PERSISTENT => false));
$message = isset($_GET["message"])?$_GET["message"]:"";
?>

<div class="<?php echo of_get_option('blog_sidebar_pos') ?>">
    <div id="content" class="grid_13 right <?php echo of_get_option('blog_sidebar_pos') ?>">
		<!--#content-->
        <div class="header-title">
        <script src="../js/jquery-1.10.1.min.js"></script>
		<style type="text/css">
		.button {
			width:400px;
			height:40px;
			font-size:18px;
			font-weight:bold;
			font-family:Verdana;
		}

		.section{
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
		.message{
			color:red;
			font-weight:bold;
			font-family:Trebuchet MS;
		}
		.option{
			padding:2px;
			height:25px;
		}

		</style>

		<?php
			if($message != null && $message != ""){
				?>
					<h3 class="message"><?=$message?></h3>
				<?php
			}
		?>

		<h1>Staff update</h1>
		<form name="UploadPage" method="post" enctype="multipart/form-data" action="../staff-preview-change/"> 
			<div class="section" style="position:relative;left:-5px">
				<table cellpadding="7" cellspacing="10">

					<tr><td><b>Select branch to update</b></td>
						<td><select name="branch" class="option" style="width:256px;height:30px;">
								<option value="">-- select station/branch --</option>
								<?php
									$stmt = $dbh->prepare("SELECT distinct * FROM location order by seq ASC");
									$stmt->execute();
									while ($rs = $stmt->fetch(PDO::FETCH_OBJ)){
										echo "<option value=\"$rs->location_id\">$rs->location_name</option>";
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Select the excel file (*.xlsx)</b></td>
						<td><input type="file" name="staff_file" class="option" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="width:250px"></td>
					</tr>
					<tr>
						<td><b>Password</b></td>
						<td><input type="password" name="password" class="option" style="width:250px"/></td>
					</tr>
				</table>
				<br/>
				<input type="submit" value="Upload >" class="button">
			</div>

		</form> 
		<br /><a href="../staff-export/">Export Excel</a>
		
        </div>
	</div>
</div>			
<?php get_footer(); ?>
