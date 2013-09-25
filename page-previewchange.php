<?php
get_header(); 

/**
 * Template Name: Staff Preview Change
 */

$fileName = "";

$locationId = $_POST['branch'];
$password = $_POST['password'];

if(isset($_FILES['staff_file'])){
	$today = date("YmdHis"); 
	$fileName =  $_SERVER['DOCUMENT_ROOT'] . '/intranet/wp-content/themes/Cargo-Intranet/staff_update/uploaded_excel/'.$today.'_'.$_FILES['staff_file']['name'];
	if(!move_uploaded_file($_FILES['staff_file']['tmp_name'], $fileName)){
		header("Location: ../staff-upload/?message=Invalid File");
	}
}

if($locationId == ""){
	header("Location: ../staff-upload/?message=Please select your station/branch");
	exit;
}

$hadError = false;

$dbh = new PDO('mysql:host=localhost;port=3306;dbname=staff;charset=utf8', 'root', '', array( PDO::ATTR_PERSISTENT => false));


$stmtLocationPassword = $dbh->prepare("SELECT password FROM location WHERE location_id = :location_id");
$stmtLocationPassword->bindParam(":location_id", $locationId);
$stmtLocationPassword->execute();

while($rsLocationPassword = $stmtLocationPassword->fetch(PDO::FETCH_OBJ)){
	$locationPassword = $rsLocationPassword->password;
	if($locationPassword != $password){
		header("Location: ../staff-upload/?message=Invalid%20Password");
		exit;
	}
}

require_once '/wp-content/themes/Cargo-Intranet/Classes/PHPExcel.php';

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);
$objPHPExcel = $objReader->load($fileName);
//$objPHPExcel = $objReader->load("D:/Telephone List Import Format.xlsx");

$objWorksheet = $objPHPExcel->getActiveSheet();

$columnMapper = array();
$valueMapper = array();

$processedRecords = array();

?>

<div class="<?php echo of_get_option('blog_sidebar_pos') ?>">
    <div id="content" class="grid_13 right <?php echo of_get_option('blog_sidebar_pos') ?>">
		<!--#content-->
        <div class="header-title">
		<script src="../js/jquery-1.10.1.min.js"></script>
		<style type="text/css">
		.crossout{
			text-decoration: line-through;
		}
		td.newItem{
			font-weight:bold;
			background-color:#E0FFD1;
		}
		span.newItem{
			font-weight:bold;
			padding:1px;
			background-color:#E0FFD1 !important;
		}
		.diff{
			background-Color:#FFFF80;
		}

		td,th{
			font-family:Trebuchet MS;
			text-align:left;
			vertical-align:top;
			border-bottom:1px solid #CCCCCC;
			font-size:12px;
		}
		.error_numeric{
			background-Color:#FF6666 !important;
		}
		.error_email{
			background-Color:#FF6666 !important;
		}
		.error_message{
			font-size:13px;
			font-weight:bold;
			color:white;
		}
		.button {
			width:200px;
			height:40px;
			font-size:18px;
			font-weight:bold;
			font-family:Verdana;
		}
		.remove{
			background-Color:#CCCCCC;
			color:#666666;
		}
		</style>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.error_numeric').append("<br/><span class=\"error_message\">* 數字<br/>(0-9 - \") ONLY</span>");
			$('.error_email').append("<br/><span class=\"error_message\">* EMAIL 不正確</span>");
			
			$('.error_numeric').closest('tr').find('td').css(
				{ 
					'border-top'  : '5px solid #FF6666', 
				  	'background-Color' : '#FFCCCC'
				}
			);

			$('.error_email').closest('tr').find('td').css(
				{ 
					'border-top'  : '5px solid #FF6666', 
				  	'background-Color' : '#FFCCCC'
				}
			);

			$('#backButton').click(function(){
				window.location.href="../staff-upload/";
			});
		});
		</script>


		<h1> Preview Change </h1>

		<?php
		//get column header
		foreach ($objWorksheet->getRowIterator() as $row) {
			
			$rowIndex = $row->getRowIndex();
			
			if($rowIndex == 1){
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,

				foreach ($cellIterator as $cell) {
					
					if($cell->getValue() == "CHINESE NAME"){
						$columnMapper["CHI"] = $cell->getColumn();
					}else if ($cell->getValue() == "NAME"){
						$columnMapper["NAME"] = $cell->getColumn();
					}else if($cell->getValue() == "POSITION"){
						$columnMapper["POSITION"] = $cell->getColumn();
					}else if($cell->getValue() == "DEPARTMENT"){
						$columnMapper["DEPARTMENT"] = $cell->getColumn();
					}else if($cell->getValue() == "DIRECT LINE"){
						$columnMapper["DIRECT"] = $cell->getColumn();
					}else if($cell->getValue() == "EXT"){
						$columnMapper["EXT"] = $cell->getColumn();
					}else if($cell->getValue() == "MOBILE"){
						$columnMapper["MOBILE"] = $cell->getColumn();
					}else if($cell->getValue() == "FAX"){
						$columnMapper["FAX"] = $cell->getColumn();
					}else if($cell->getValue() == "EMAIL"){
						$columnMapper["EMAIL"] = $cell->getColumn();
					}else if($cell->getValue() == "TEAM"){
						$columnMapper["TEAM"] = $cell->getColumn();
					}else if($cell->getValue() == "ADDRESS"){
						$columnMapper["ADDRESS"] = $cell->getColumn();
					}else if($cell->getValue() == "COMPANY"){
						$columnMapper["COMPANY"] = $cell->getColumn();
					} 
				}
			}else{
				
				if($objWorksheet->getCell('A' . $rowIndex) != "" && 
					$objWorksheet->getCell('B' . $rowIndex) != "" &&
					$objWorksheet->getCell('C' . $rowIndex) == "" &&
					$objWorksheet->getCell('D' . $rowIndex) == ""){
					$key = $objWorksheet->getCell('A' . $rowIndex)->getValue();
					$value = $objWorksheet->getCell('B' . $rowIndex)->getValue();

					$valueMapper[$key] = $value;
				}
			}
		}

		$name = "";
		$position = "";
		$team = "";
		$address = "";
		$directline = "";
		$ext = "";
		$email = "";
		$mobile = "";
		$fax = "";
		$department = "";
		$chineseName = null;

		//get last batch id
		$stmt = $dbh->prepare("SELECT MAX(last_batch_id) FROM staff");
		$stmt->execute();

		$lastBatchId = $stmt->fetchColumn(0);
		$lastBatchId++;

		$i = 1;

		echo '<table cellpadding="5" cellspacing="0">';
		echo '<tr>
		<th>1</th>
		<th style="width:200px">NAME</th>
		<th style="width:150px">COMPANY</th>
		<th style="width:300px">POSITION</th>
		<th style="width:150px">DEPARTMENT</th>
		<th>EMAIL</th>
		<th>TEAM</th>
		<th>ADDRESS</th>
		<th>DIRECT</th>
		<th>EXT</th>
		<th>MOBILE</th>
		<th>FAX</th>
		<th><nobr>中文名</nobr></th>

		</tr>';

		//process each row in excel
		foreach ($objWorksheet->getRowIterator() as $row) {
			
			$rowIndex = $row->getRowIndex();

			if(!empty($columnMapper["NAME"]) && $columnMapper["NAME"] != ""){
				$name = $objWorksheet->getCell($columnMapper["NAME"] . $rowIndex);
			}
			if(!empty($columnMapper["POSITION"]) && $columnMapper["POSITION"] != ""){
				$position = $objWorksheet->getCell($columnMapper["POSITION"] . $rowIndex);
			}
			if(!empty($columnMapper["DIRECT"]) && $columnMapper["DIRECT"] != ""){
				$directline = $objWorksheet->getCell($columnMapper["DIRECT"] . $rowIndex);
			}
			if(!empty($columnMapper["EXT"]) && $columnMapper["EXT"] != ""){
				$ext = $objWorksheet->getCell($columnMapper["EXT"] . $rowIndex);
			}
			if(!empty($columnMapper["EMAIL"]) && $columnMapper["EMAIL"] != ""){
				$email = $objWorksheet->getCell($columnMapper["EMAIL"] . $rowIndex);
			}
			if(!empty($columnMapper["MOBILE"]) && $columnMapper["MOBILE"] != ""){
				$mobile = $objWorksheet->getCell($columnMapper["MOBILE"] . $rowIndex);
			}
			if(!empty($columnMapper["FAX"]) && $columnMapper["FAX"] != ""){
				$fax = $objWorksheet->getCell($columnMapper["FAX"] . $rowIndex);
			}
			if(!empty($columnMapper["DEPARTMENT"]) && $columnMapper["DEPARTMENT"] != ""){
				$department = $objWorksheet->getCell($columnMapper["DEPARTMENT"] . $rowIndex);
			}
			if(!empty($columnMapper["CHI"]) && $columnMapper["CHI"] != ""){
				$chineseName = $objWorksheet->getCell($columnMapper["CHI"] . $rowIndex);
			}
			if(!empty($columnMapper["TEAM"]) && $columnMapper["TEAM"] != ""){
				$team = $objWorksheet->getCell($columnMapper["TEAM"] . $rowIndex);
			}
			if(!empty($columnMapper["ADDRESS"]) && $columnMapper["ADDRESS"] != ""){
				$addressKey = $objWorksheet->getCell($columnMapper["ADDRESS"] . $rowIndex)->getValue();
				if($addressKey != null && !empty($valueMapper[$addressKey])){
					$address = $valueMapper[$addressKey];
				}
			}
			if(!empty($columnMapper["COMPANY"]) && $columnMapper["COMPANY"] != ""){
				$company = $objWorksheet->getCell($columnMapper["COMPANY"] . $rowIndex);
			}

			//check each row in the excel
			if($name->getValue() != "NAME" && $name->getValue() != "" && $position->getValue() != ""){
				$departmentId = -1;
				$stmt = $dbh->prepare("SELECT department_id FROM department WHERE department_name = :department");
				$stmt->bindParam(':department', $department, PDO::PARAM_STR, 40);
				$stmt->execute();
					
				while ($rs = $stmt->fetch(PDO::FETCH_OBJ)){
					$departmentId = $rs->department_id;
				}
				
				//get Company
				$companyId = -1;
				$stmt = $dbh->prepare("SELECT company_id FROM company WHERE english_name = :english_name");
				$stmt->bindParam(':english_name', $company, PDO::PARAM_STR, 40);
				$stmt->execute();
					
				while ($rs = $stmt->fetch(PDO::FETCH_OBJ)){
					$companyId = $rs->company_id;
				}

				//get staff
				$staffId = "";
				
				$stmtStaffCount = $dbh->prepare("SELECT COUNT(*) FROM staff WHERE status = 0 AND department_id = :department_id AND company_id = :company_id AND english_name = :english_name");
				$stmtStaffCount->bindParam(':department_id',$departmentId);
				$stmtStaffCount->bindParam(':company_id',$companyId);
				$stmtStaffCount->bindParam(':english_name',$name, PDO::PARAM_STR);	
				$stmtStaffCount->execute();
				$staffNameCount = $stmtStaffCount->fetchColumn();

				//solve duplicated staff name problem
				$stmtStaff = null;
				if($staffNameCount <= 1){
					$stmtStaff = $dbh->prepare("SELECT staff.*,address.address FROM staff LEFT JOIN address USING (address_id) WHERE status = 0 AND department_id = :department_id AND company_id = :company_id AND english_name = :english_name");
					$stmtStaff->bindParam(':department_id',$departmentId);
					$stmtStaff->bindParam(':company_id',$companyId);
					$stmtStaff->bindParam(':english_name',$name, PDO::PARAM_STR);
					//ChromePhp::log("SELECT staff.*,address.address FROM staff LEFT JOIN address USING (address_id) WHERE status = 0 AND department_id = $departmentId AND company_id = $companyId AND english_name = $name");
				}else{
					$stmtStaff = $dbh->prepare("SELECT staff.*,address.address FROM staff LEFT JOIN address USING (address_id) WHERE status = 0 AND department_id = :department_id AND company_id = :company_id AND english_name = :english_name AND email = :email");
					$stmtStaff->bindParam(':department_id',$departmentId);
					$stmtStaff->bindParam(':company_id',$companyId);
					$stmtStaff->bindParam(':english_name',$name, PDO::PARAM_STR);
					$stmtStaff->bindParam(':email',$email, PDO::PARAM_STR);
				}
				$stmtStaff->execute();

				echo "<tr>";
				$i++;
				echo "<td>$i</td>";
				if ($rs = $stmtStaff->fetch(PDO::FETCH_OBJ)){
					//show minor change records (position, phone change);
					$staffRecord = array();
					$staffRecord['english_name'] = $rs->english_name;
					$staffRecord['department_id'] = $rs->department_id;
					$staffRecord['company_id'] = $rs->company_id;
					$staffRecord['email'] = $rs->email;
					$staffRecord['team'] = $rs->team;
					
					array_push($processedRecords,$staffRecord);
					
					$staffId = $rs->staff_id;
				
					
					echo showDiff($rs->english_name,$name,'string');
					echo showDiff($company,$company,'string');
					echo showDiff($rs->position,$position,'string');
					echo showDiff($department,$department,'string');
					echo showDiff($rs->email,$email,'email');
					echo showDiff($rs->team,$team,'string');
					echo showDiff($rs->address,$address,'string');

					$stmt = $dbh->prepare("SELECT p.phone_type,p.phone_number,p.ext FROM staff_phone sp INNER JOIN phone p ON (sp.phone_id = p.phone_id) WHERE sp.staff_id = :staffId AND sp.status = 0");
					$stmt->bindParam(':staffId',$staffId);
					$stmt->execute();
					
					$dbDirectline = "";
					$dbExt = "";
					$dbMobile = "";
					$dbFax = "";
					
					while ($rsPhone = $stmt->fetch(PDO::FETCH_OBJ)){
						if($rsPhone->phone_type == 'D'){
							$dbDirectline = $rsPhone->phone_number;
							$dbExt = $rsPhone->ext;
						}else if($rsPhone->phone_type == 'M'){
							$dbMobile = $rsPhone->phone_number;
						}else if($rsPhone->phone_type == 'F'){
							$dbFax = $rsPhone->phone_number;
						}
					}
					
					echo showDiff($dbDirectline,$directline,'numeric');
					echo showDiff($dbExt,$ext,'numeric');
					echo showDiff($dbMobile,$mobile,'string');
					echo showDiff($dbFax,$fax,'numeric');
					echo showDiff($rs->chinese_name,$chineseName,'string');
				}else{
					//show new record(change name , department and email will treat that record as new record)
					echo "<td class=\"diff newItem\">$name</td>";
					echo "<td class=\"diff newItem\">$company</td>";
					echo "<td class=\"diff newItem\">$position</td>";
					echo "<td class=\"diff newItem\">$department</td>";
					echo "<td class=\"diff newItem ".validateEmail($email)."\">$email</td>";
					echo "<td class=\"diff newItem\">$team</td>";
					echo "<td class=\"diff newItem\">$address</td>";

					echo "<td class=\"diff newItem ".validateNumeric($directline)."\">$directline</td>";
					echo "<td class=\"diff newItem ".validateNumeric($ext)."\">$ext</td>";
					echo "<td class=\"diff newItem\">$mobile</td>";
					echo "<td class=\"diff newItem ".validateNumeric($fax)."\">$fax</td>";
					echo "<td class=\"diff newItem\">$chineseName</td>";
				}
				echo "</tr>";
			}
			
		}

		$stmt = $dbh->prepare("SELECT s.*,d.department_name,c.english_name as company_name,a.address FROM staff s INNER JOIN department d USING (department_id) INNER JOIN company c USING (company_id) LEFT JOIN address a USING (address_id) WHERE s.status = 0 AND s.location_id = :location_id");
		$stmt->bindParam(':location_id',$locationId);
		$stmt->execute();

		while($rs = $stmt->fetch(PDO::FETCH_OBJ)){
			$found = false;
			foreach($processedRecords as $row){
				if($row['english_name'] == $rs->english_name && $row['department_id'] == $rs->department_id && $row['company_id'] == $rs->company_id && $row['email'] == $rs->email){
					$found = true;
				}
			}
			if(!$found){
				//show removed records
				echo "<tr>";
				$i++;
				echo "<td>$i</td>";
				echo "<td class=\"crossout remove\">$rs->english_name</td>";
				echo "<td class=\"crossout remove\">$rs->company_name</td>";
				echo "<td class=\"crossout remove\">$rs->position</td>";
				echo "<td class=\"crossout remove\">$rs->department_name</td>";
				echo "<td class=\"crossout remove\">$rs->email</td>";
				echo "<td class=\"crossout remove\">$rs->team</td>";
				echo "<td class=\"crossout remove\">$rs->address</td>";
				
				$stmtPhone = $dbh->prepare("SELECT p.phone_type,p.phone_number,p.ext FROM staff_phone sp INNER JOIN phone p ON (sp.phone_id = p.phone_id) WHERE sp.staff_id = :staffId AND sp.status = 0");
				$stmtPhone->bindParam(':staffId',$rs->staff_id);
				$stmtPhone->execute();
				
				$dbDirectline = "";
				$dbExt = "";
				$dbMobile = "";
				$dbFax = "";
				
				while ($rsPhone = $stmtPhone->fetch(PDO::FETCH_OBJ)){
					if($rsPhone->phone_type == 'D'){
						$dbDirectline = $rsPhone->phone_number;
						$dbExt = $rsPhone->ext;
					}else if($rsPhone->phone_type == 'M'){
						$dbMobile = $rsPhone->phone_number;
					}else if($rsPhone->phone_type == 'F'){
						$dbFax = $rsPhone->phone_number;
					}
				}
				
				echo "<td class=\"crossout remove\">$dbDirectline</td>";
				echo "<td class=\"crossout remove\">$dbExt</td>";
				echo "<td class=\"crossout remove\">$dbMobile</td>";
				echo "<td class=\"crossout remove\">$dbFax</td>";
				echo "<td class=\"crossout remove\">$rs->chinese_name</td>";
				echo "</tr>";
				
			}
			$rs->english_name;
			$rs->department_id;
			$rs->company_id;
			$rs->email;
		}

		echo "</table>";

		function showDiff($cellFrom,$cellTo,$cellType){
			if ($cellTo == "-" || $cellTo == '"' || $cellTo == "''" || $cellTo == "'") {
				$cellTo = "";
			}
			
			$returnString = "";
			
			if($cellFrom != $cellTo){
				$returnString .= "<td class=\"diff ";
				if($cellType == 'numeric'){
					$returnString .= validateNumeric($cellTo);
				}else if($cellType == 'email'){
					$returnString .= validateEmail($cellTo);
				}
				$returnString .= "\"><span class=\"crossout\">$cellFrom</span>&nbsp;<span class=\"newItem\">$cellTo</span></td>";
			}else{
				$returnString .= "<td class=\"nodiff\">$cellTo</td>";
			}
			
			return $returnString;
		}
		function validateNumeric($field){
			global $hadError;
			
			//ChromePhp::log('field : ' . $field);
			if ($field == "-" || $field == '"' || $field == "''" || $field == "'" || $field == "") {
				return "";
			}else{
				$pattern = "/^[0-9+\- \/]+$/i";
				
				if(preg_match($pattern, $field)){
					return "";
				}else{
					$hadError = true;
					return "error_numeric";
				}
			}
		}
		function validateEmail($field){
			global $hadError;

			if($field == "" || $field == "-"){
				return "";
			}else{
				$pattern = "/.+\@.+\..+/i";
				if(preg_match($pattern, $field)){
					return "email";
				}else{
					$hadError = true;
					return "error_email";
				}
			}
		}

		?>
		<br/>
		<form method="post" action="../staff-update/">
		<center><input type="button" value="< Back" class="button" id="backButton"/><input type="submit" value="Next >" class="button" <?=($hadError?'DISABLED="DISABLED"':'')?>/></center>
		<input type="hidden" name="staff_file" value="<?=$fileName?>"/>
		<input type="hidden" name="branch" value="<?=$locationId?>"/>
		<input type="hidden" name="password" value="<?=$password?>"/>
		</form>

       	</div>
	</div>
</div>			
<?php get_footer(); ?>
