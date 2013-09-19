<?php
include 'ChromePhp.php';

$companyId = $_POST['company_id'];
$password = $_POST['password'];

if($companyId == ""){
	header("Location: ./upload_file.php?message=Please select your station/branch");
	exit;
}

$dbh = new PDO('mysql:host=localhost;port=3306;dbname=staff;charset=utf8', 'root', '', array( PDO::ATTR_PERSISTENT => false));

$stmtCompanyPassword = $dbh->prepare("SELECT password FROM company WHERE company_id = :company_id");
$stmtCompanyPassword->bindParam(":company_id", $companyId);
$stmtCompanyPassword->execute();

while($rsCompanyPassword = $stmtCompanyPassword->fetch(PDO::FETCH_OBJ)){
	$companyPassword = $rsCompanyPassword->password;
	if($companyPassword != $password){
		header("Location: ./upload_file.php?message=Invalid%20Password");
		exit;
	}
}

require_once '../Classes/PHPExcel.php';

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(false);
//$objPHPExcel = $objReader->load("D:/Telephone List Import Format.xlsx");
$objPHPExcel = $objReader->load($_POST['staff_file']);

$objWorksheet = $objPHPExcel->getActiveSheet();

$columnMapper = array();

//get column header
foreach ($objWorksheet->getRowIterator() as $row) {
	
	$rowIndex = $row->getRowIndex();
	
	if($rowIndex == 1){
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,

		foreach ($cellIterator as $cell) {
			
			//ChromePhp::log($cell->getValue() == "CHINESE NAME");
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
			} 
		}
	}else{
		break;
	}
}

$name = "";
$position = "";
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
	
	if($name->getValue() != "NAME" && $name->getValue() != "" && $position->getValue() != ""){
		$departmentId = -1;
		//ChromePhp::log(' department name ' . $department);
		$stmt = $dbh->prepare("SELECT department_id FROM department WHERE department_name = :department");
		$stmt->bindParam(':department', $department, PDO::PARAM_STR, 40);
		$stmt->execute();
			
		while ($rs = $stmt->fetch(PDO::FETCH_OBJ)){
			$departmentId = $rs->department_id;
		}
		
		//ChromePhp::log(' department ' . $departmentId);
		
		//create department if not exist
		if($departmentId <= 0){
			$stmt = $dbh->prepare("INSERT INTO department (department_name) VALUES (:department_name)");
			$stmt->bindParam(':department_name',$department);
			$stmt->execute();

			$departmentId = $dbh->lastInsertId();
			
		}
		//get staff
		$staffId = "";
		
		$stmtStaff = $dbh->prepare("SELECT * FROM staff WHERE status = 0 AND english_name = :english_name AND department_id = :department_id AND company_id = :company_id AND email = :email ");
		$stmtStaff->bindParam(':english_name',$name, PDO::PARAM_STR);
		$stmtStaff->bindParam(':department_id',$departmentId);
		$stmtStaff->bindParam(':company_id',$companyId);
		$stmtStaff->bindParam(':email',$email);
		$stmtStaff->execute();
		//ChromePhp::log("SELECT * FROM staff WHERE english_name = '".$name."' AND department_id = ".$departmentId." AND company_id = ".$companyId." AND email = '".$email."';");
		
		if ($rs = $stmtStaff->fetch(PDO::FETCH_OBJ)){
			$staffId = $rs->staff_id;
			
			$status = 0;
			//update staff if staff exist
		
			if($email != $rs->email || $chineseName != $rs->chinese_name || $position != $rs->position || $status != $rs->status){
				$stmt = $dbh->prepare("UPDATE STAFF SET last_batch_id = :last_batch_id, chinese_name = :chinese_name,position = :position,status = :status,modifydate = now() WHERE status = 0 AND english_name = :english_name AND department_id = :department_id AND company_id = :company_id AND email = :email");
				$stmt->bindParam(':last_batch_id',$lastBatchId);
				$stmt->bindParam(':email',$email, PDO::PARAM_STR);
				$stmt->bindParam(':chinese_name',$chineseName, PDO::PARAM_STR);
				$stmt->bindParam(':position',$position, PDO::PARAM_STR);
				$stmt->bindParam(':status',$status);
				
				$stmt->bindParam(':english_name',$name, PDO::PARAM_STR);
				$stmt->bindParam(':department_id',$departmentId);
				$stmt->bindParam(':company_id',$companyId);

				$stmt->execute();
			}else{
				$stmt = $dbh->prepare("UPDATE STAFF SET last_batch_id = :last_batch_id WHERE staff_id = :staff_id");
				$stmt->bindParam(':last_batch_id',$lastBatchId);
				$stmt->bindParam(':staff_id',$staffId);
				$stmt->execute();
			}

		}else{
			//create staff
			$status = 0;
			$stmt = $dbh->prepare("INSERT INTO staff (email,english_name,chinese_name,position,department_id,company_id,status,createdate,modifydate,last_batch_id) VALUES (:email,:english_name,:chinese_name,:position,:department_id,:company_id,:status,now(),now(),:last_batch_id)");
			$stmt->bindParam(':email',$email);
			$stmt->bindParam(':english_name',$name);
			$stmt->bindParam(':chinese_name',$chineseName);
			$stmt->bindParam(':position',$position);
			$stmt->bindParam(':department_id',$departmentId);
			$stmt->bindParam(':company_id',$companyId);
			$stmt->bindParam(':status',$status);
			$stmt->bindParam(':last_batch_id',$lastBatchId);
			
			$stmt->execute();
			
			$staffId = $dbh->lastInsertId();
			
		}
		
		//check difference , then update and create
		// create phone
		if ($directline != "-" && $directline != '"' && $directline != "''") {
			if($ext == "-" || $ext == "\""){
				$ext = null;
			}
			createPhone($dbh,$staffId, "D", $directline, $ext, $lastBatchId);
		}
		if ($mobile != "" && $mobile != "-" && $mobile != "\"" && $mobile != "''") {
			createPhone($dbh,$staffId, "M", $mobile, null , $lastBatchId);
		}
		if ($fax != "" && $fax != "-" && $fax != "\"" && $fax != "''" && $fax != "'") {
			createPhone($dbh,$staffId, "F", $fax, null , $lastBatchId);
		}
	}
}

//update staff removed from list
//ChromePhp::log(" last batch id : " . $lastBatchId);

$stmt = $dbh->prepare("UPDATE staff SET status = 1 WHERE last_batch_id < :last_batch_id and company_id = :company_id");
$stmt->bindParam(':last_batch_id',$lastBatchId);
$stmt->bindParam(':company_id',$companyId);
$stmt->execute();

$stmt = $dbh->prepare("UPDATE staff_phone sp INNER JOIN staff s USING(staff_id) SET sp.status = 1 WHERE sp.last_batch_id < :last_batch_id and s.company_id = :company_id");
$stmt->bindParam(':last_batch_id',$lastBatchId);
$stmt->bindParam(':company_id',$companyId);
$stmt->execute();

echo "DONE";

function createPhone($dbh,$staffId,$phone_type,$phone_number,$ext,$lastBatchId){
	//check duplicate
	$stmtStaff = $dbh->prepare("SELECT sp.staff_phone_id FROM phone p INNER JOIN staff_phone sp ON (p.phone_id = sp.phone_id) WHERE p.phone_number = :phone_number AND p.phone_type = :phone_type AND sp.staff_id = :staffId AND p.ext <=> :ext");
	$stmtStaff->bindParam(':phone_type',$phone_type);
	$stmtStaff->bindParam(':phone_number',$phone_number);
	$stmtStaff->bindParam(':staffId',$staffId);
	$stmtStaff->bindParam(':ext',$ext);
	$stmtStaff->execute();
	$status = 0;
	
	if ($rsPhone = $stmtStaff->fetch(PDO::FETCH_OBJ)){
	
		$stmt = $dbh->prepare("UPDATE staff_phone SET status = :status, last_batch_id = :last_batch_id, modifydate = now() WHERE staff_phone_id = :staff_phone_id");
		$stmt->bindParam(':status',$status);
		$stmt->bindParam(':last_batch_id',$lastBatchId);
		$stmt->bindParam(':staff_phone_id',$rsPhone->staff_phone_id);
		$stmt->execute();
		
		return $rsPhone->staff_phone_id;
		
	}else{
	
		$stmt = $dbh->prepare("INSERT INTO phone (phone_type,phone_number,ext) VALUES (:phone_type,:phone_number,:ext)");
		$stmt->bindParam(':phone_type',$phone_type);
		$stmt->bindParam(':phone_number',$phone_number);
		$stmt->bindParam(':ext',$ext);
		$stmt->execute();

		$phoneId = $dbh->lastInsertId();

		$stmt = $dbh->prepare("INSERT INTO staff_phone (phone_id,staff_id,status,last_batch_id,modifydate) VALUES (:phoneId,:staffId,0,:last_batch_id,now())");
		$stmt->bindParam(':phoneId',$phoneId);
		$stmt->bindParam(':staffId',$staffId);
		$stmt->bindParam(':last_batch_id',$lastBatchId);
		$stmt->execute();

		$staffPhoneId = $dbh->lastInsertId();

		return $staffPhoneId;
		
	}
	

}
?>