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
		.section *{
			border:1px solid #CCCCCC;
			padding:10px;
			font-family:Trebuchet MS;
		}
		.section{
			padding:5px;
		}
		h2{
			margin:5px;
			font-size:16px;
			letter-spacing:0.07em;
		}
		.message{
			color:red;
			font-weight:bold;
		}
		.option{
			margin-left:10px;
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
			<div class="section">
				<h2>1) Select station/branch to update</h2>
				<select name="branch" class="option">
					<option value="">-- select station/branch --</option>
					<?php
						$stmt = $dbh->prepare("SELECT distinct * FROM location");
						$stmt->execute();
						while ($rs = $stmt->fetch(PDO::FETCH_OBJ)){
							echo "<option value=\"$rs->location_id\">$rs->location_name</option>";
						}
					?>
				</select> <br/>
				
			</div>
			<div class="section">
				<h2>2) Select the excel File (*.xlsx)</h2>
				<input type="file" name="staff_file" class="option" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"><br/> 
			</div>
			<div class="section">
				<h2>3) Password : </h2><input type="password" name="password" class="option" style="width:150px"/>	
			</div>
			<div class="section">
				<input type="submit" value="Upload >" class="button">
			</div>
		</form> 
		<br /><a href="../staff-export/">Export Excel</a>
		
        </div>
	</div>
</div>			
<?php get_footer(); ?>
