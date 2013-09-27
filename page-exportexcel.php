<?php
get_header(); 

/**
 * Template Name: Staff Export
 */

$fileName = "";

$locationId = 1;

$dbh = new PDO('mysql:host=localhost;port=3306;dbname=staff;charset=utf8', 'root', '', array( PDO::ATTR_PERSISTENT => false));

/** Include path **/
require_once '/wp-content/themes/Cargo-Intranet/Classes/PHPExcel.php';

$objPHPExcel = new PHPExcel();

?>
<div class="<?php echo of_get_option('blog_sidebar_pos') ?>">
    <div id="content" class="grid_13 right <?php echo of_get_option('blog_sidebar_pos') ?>">
		<!--#content-->
        <div class="header-title">

<?php
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
		'borders' => array(
			 'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000'),
			 ),
		),
);

$stmtAllLocation = $dbh->prepare("SELECT * FROM location ORDER BY seq ASC");
$stmtAllLocation->execute();
$sheetCount = 0;

while($rsAllLocation = $stmtAllLocation->fetch(PDO::FETCH_OBJ)){

	// Add some data
	//echo date('H:i:s') . " Add some data\n";
	$i = 1;

	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($sheetCount);
	$sheet = $objPHPExcel->getActiveSheet();


	$locationId = $rsAllLocation->location_id;

	$sheet->setTitle($locationId);

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
	$sheet->getColumnDimension('C')->setWidth(15);
	$sheet->getColumnDimension('D')->setWidth(5);
	$sheet->getColumnDimension('E')->setWidth(27);
	$sheet->getColumnDimension('F')->setWidth(10);
	$sheet->getColumnDimension('G')->setWidth(35);

	$i++;

	// print staff
	$lastDepartmentId = -1;
	$lastAddress = "";
	$lastCompany = "";
	$lastTeam = "";
	//ChromePhp::log("select s.*,d.department_id,d.department_name,a.address from staff s inner join department d using(department_id) left join address a using(address_id) where company_id = $companyId order by department_id ASC, staff_id ASC");

	$stmt = $dbh->prepare("select s.*,d.department_id,d.department_name,c.english_name as company_name,a.address from staff s inner join department d using(department_id) inner join company c using(company_id) left join address a using(address_id) where location_id = :location_id AND status = 0 ORDER BY company_name ASC, d.department_id ASC, a.address ASC,  s.staff_id ASC");
	$stmt->bindParam(':location_id',$locationId, PDO::PARAM_STR);
	$stmt->execute();
	while ($rsStaff = $stmt->fetch(PDO::FETCH_OBJ)){
		if($lastCompany != $rsStaff->company_name){
			$sheet->SetCellValue('A'. $i, $rsStaff->company_name);

			$sheet->getStyle('A' . $i)->getFont()->setBold(true)
			                ->setSize(16)
                            ->getColor()->setRGB('336699');
			$i++;
			
			$lastCompany = $rsStaff->company_name;

		}

		if($lastDepartmentId != $rsStaff->department_id){
			$sheet->getStyle('A' . $i)->applyFromArray($blueFillArray);
			$sheet->getStyle('B' . $i)->applyFromArray($blueFillArray);
			$sheet->getStyle('C' . $i)->applyFromArray($blueFillArray);
			$sheet->getStyle('D' . $i)->applyFromArray($blueFillArray);
			$sheet->getStyle('E' . $i)->applyFromArray($blueFillArray);
			$sheet->getStyle('F' . $i)->applyFromArray($blueFillArray);
			$sheet->getStyle('G' . $i)->applyFromArray($blueFillArray);
			$sheet->getRowDimension($i)->setRowHeight(5);

			$sheet->mergeCells("A$i:G$i");
			$i++;
			$sheet->SetCellValue('A'. $i, $rsStaff->department_name);

			$sheet->mergeCells("A$i:B$i");
			$sheet->getStyle('A' . $i)->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFF99')
					)
				)
			);
			$sheet->getStyle('B' . $i)->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFF99')
					)

				)
			);
			$sheet->getStyle('A' . $i)->getFont()->setBold(true);
			$i++;
			
			$lastDepartmentId = $rsStaff->department_id;
		}
		if($rsStaff->team != "" && $lastTeam != $rsStaff->team){
			$sheet->SetCellValue('A'. $i, $rsStaff->team);

			$sheet->getStyle("A$i")->applyFromArray(array(
			  'font' => array(
			    'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
			  )
			));
			$i++;
			
			$lastTeam = $rsStaff->team;
		}
		if($lastAddress != $rsStaff->address){
			$sheet->SetCellValue('A'. $i, $rsStaff->address);

			$i++;
			
			$lastAddress = $rsStaff->address;
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

	$sheetCount++;
}

$objPHPExcel->setActiveSheetIndex(0);

$today = date("YmdHis"); 
$fileName = '/intranet/wp-content/themes/Cargo-Intranet/staff_update/export_excel/'.$today.'_staff_name_book.xls';
// Save Excel 2007 file
echo date('H:i:s') . " Writing Excel<br/>";
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
$objWriter->save($_SERVER['DOCUMENT_ROOT'] . ''.$fileName);

// Echo done
echo date('H:i:s') . " Done writing file.<br/><br/>";


echo "<a href=\"$fileName\" style=\"font-family:Trebuchet MS\">Download File</a></body></html>";

function getNullValue($value){
	if($value == null || $value == "" || $value == " "){
		return "-";
	}else{
		return $value;
	}
}
?>
        
        </div>
	</div>
</div>			
<?php get_footer(); ?>
