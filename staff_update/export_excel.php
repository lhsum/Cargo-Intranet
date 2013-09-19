<?php

include 'ChromePhp.php';

$fileName = "";

$companyId = 1;

$dbh = new PDO('mysql:host=localhost;port=3306;dbname=staff;charset=utf8', 'root', '', array( PDO::ATTR_PERSISTENT => false));

/** Include path **/
ini_set('include_path', ini_get('include_path').';../Classes/');

/** PHPExcel */
include 'PHPExcel.php';
echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Internal Staff</title>
</head>
<body>
<pre style="font-family:Trebuchet MS;font-size:16px;letter-spacing:0.05em;">
EOD;

// Create new PHPExcel object
echo date('H:i:s') . " Create new Excel\n";
$objPHPExcel = new PHPExcel();

// Set properties
//echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Cargo FarEast");
$objPHPExcel->getProperties()->setLastModifiedBy("Cargo FarEast");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

$styleArray = array(
       'borders' => array(
             'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000'),
             ),
       ),
);

$blueFillArray = array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'CCFFFF')
		),
);

$stmtAllCompany = $dbh->prepare("SELECT * FROM company ORDER BY company_id ASC");
$stmtAllCompany->execute();
$sheetCount = 0;

while($rsAllCompany = $stmtAllCompany->fetch(PDO::FETCH_OBJ)){

	// Add some data
	//echo date('H:i:s') . " Add some data\n";
	$i = 1;

	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($sheetCount);
	$sheet = $objPHPExcel->getActiveSheet();


	$companyId = $rsAllCompany->company_id;
	$companyName = $rsAllCompany->english_name;

	$sheet->SetCellValue('A'.$i , $companyName);

	$stmtCompanyPhone = $dbh->prepare("SELECT * FROM phone p INNER JOIN company_phone cp USING (company_id) WHERE p.company_id = :company_id");
	$stmtCompanyPhone->bindParam(":company_id",$companyId);
	$stmtCompanyPhone->execute();

	$companyPhone = "";

	while($rsCompanyPhone = $stmtCompanyPhone->fetch(PDO::FETCH_OBJ)){
		if($rsCompanyPhone->phone_type == "D"){
			$companyPhone = $rsCompanyPhone->phone_number;
		}
	}

	$sheet->SetCellValue('C'.$i , $companyPhone);
	$sheet->getStyle('A' . $i)->getFont()->setBold(true)
				                ->setSize(16)
                                ->getColor()->setRGB('336699');


	$i+=2;


	$sheet->SetCellValue('A'. $i, 'NAME');
	$sheet->SetCellValue('B'. $i, 'POSITION');
	$sheet->SetCellValue('C'. $i, 'DIR. LINE NO.');
	$sheet->SetCellValue('D'. $i, 'EXT.');
	$sheet->SetCellValue('E'. $i, 'MOBILE PHONE');
	$sheet->SetCellValue('F'. $i, 'FAX LINE');
	$sheet->SetCellValue('G'. $i, 'EMAIL');

	$sheet->getStyle('A' . $i)->getFont()->setBold(true);
	$sheet->getStyle('B' . $i)->getFont()->setBold(true);
	$sheet->getStyle('C' . $i)->getFont()->setBold(true);
	$sheet->getStyle('D' . $i)->getFont()->setBold(true);
	$sheet->getStyle('E' . $i)->getFont()->setBold(true);
	$sheet->getStyle('F' . $i)->getFont()->setBold(true);
	$sheet->getStyle('G' . $i)->getFont()->setBold(true);
	
	$sheet->getStyle('A' . $i)->applyFromArray($styleArray);
	$sheet->getStyle('B' . $i)->applyFromArray($styleArray);
	$sheet->getStyle('C' . $i)->applyFromArray($styleArray);
	$sheet->getStyle('D' . $i)->applyFromArray($styleArray);
	$sheet->getStyle('E' . $i)->applyFromArray($styleArray);
	$sheet->getStyle('F' . $i)->applyFromArray($styleArray);
	$sheet->getStyle('G' . $i)->applyFromArray($styleArray);

	$sheet->getColumnDimension('A')->setWidth(40);
	$sheet->getColumnDimension('B')->setWidth(40);
	$sheet->getColumnDimension('C')->setWidth(13);
	$sheet->getColumnDimension('D')->setWidth(5);
	$sheet->getColumnDimension('E')->setWidth(27);
	$sheet->getColumnDimension('F')->setWidth(10);
	$sheet->getColumnDimension('G')->setWidth(35);

	$i++;

	// print staff
	$lastDepartmentId = -1;

	//ChromePhp::log("select s.*,d.department_id,d.department_name from staff s inner join department d using(department_id) where company_id = $companyId order by department_id ASC, staff_id ASC");

	$stmt = $dbh->prepare("select s.*,d.department_id,d.department_name from staff s inner join department d using(department_id) where company_id = :company_id AND status = 0 order by d.department_id ASC, s.staff_id ASC");
	$stmt->bindParam(':company_id',$companyId);
	$stmt->execute();
	while ($rsStaff = $stmt->fetch(PDO::FETCH_OBJ)){
		//ChromePhp::log($rsStaff->department_id);
		if($lastDepartmentId != $rsStaff->department_id){
			if($i > 4){
				$sheet->getStyle('A' . $i)->applyFromArray($blueFillArray);
				$sheet->getStyle('B' . $i)->applyFromArray($blueFillArray);
				$sheet->getRowDimension($i)->setRowHeight(5);
				$i++;
			}
			$sheet->SetCellValue('A'. $i, $rsStaff->department_name);
		
			$sheet->getStyle('A' . $i)->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFF99')
					),
					'borders' => array(
						 'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '000000'),
						 ),
					),
				)
			);
			$sheet->getStyle('A' . $i)->getFont()->setBold(true);
			$i++;
			
			$lastDepartmentId = $rsStaff->department_id;
		}

		
		$sheet->SetCellValue('A'. $i, $rsStaff->english_name);
		$sheet->SetCellValue('B'. $i, $rsStaff->position);
		
		$stmtPhone = $dbh->prepare("SELECT p.phone_type,p.phone_number,p.ext FROM staff_phone sp INNER JOIN phone p ON (sp.phone_id = p.phone_id) WHERE sp.staff_id = :staffId AND sp.status = 0");
		$stmtPhone->bindParam(':staffId',$rsStaff->staff_id);
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

		$sheet->SetCellValue('C'. $i, getNullValue($dbDirectline));
		$sheet->SetCellValue('D'. $i, getNullValue($dbExt));
		$sheet->SetCellValue('E'. $i, getNullValue($dbMobile));
		$sheet->SetCellValue('F'. $i, getNullValue($dbFax));
		$sheet->SetCellValue('G'. $i, $rsStaff->email);

		$sheet->getStyle('A' . $i)->applyFromArray($styleArray);
		$sheet->getStyle('B' . $i)->applyFromArray($styleArray);
		$sheet->getStyle('C' . $i)->applyFromArray($styleArray)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
		$sheet->getStyle('D' . $i)->applyFromArray($styleArray)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('E' . $i)->applyFromArray($styleArray)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('F' . $i)->applyFromArray($styleArray)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('G' . $i)->applyFromArray($styleArray);
		$i++;


	}
	/*
	for ($col = 'A'; $col !== 'J'; $i++) {
		$sheet->getColumnDimension($col)->setAutoSize(true);
	}
	*/
	// Rename sheet
	echo date('H:i:s') . " Writing Sheet " . substr($companyName,0,31) ."\n";
	$sheet->setTitle(substr($companyName,0,31));

	$sheetCount++;
}


$objPHPExcel->setActiveSheetIndex(0);

$today = date("YmdHis"); 
$fileName = './export_excel/'.$today.'_staff_name_book.xls';
// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
$objWriter->save($fileName);

// Echo done
echo date('H:i:s') . " Done writing file.\r\n";

echo "</pre>";

echo "<a href=\"$fileName\" style=\"font-family:Trebuchet MS\">Download File</a></body></html>";

function getNullValue($value){
	ChromePhp::log("===[" . $value . "]===");
	if($value == null || $value == "" || $value == " "){
		return "-";
	}else{
		return $value;
	}
}