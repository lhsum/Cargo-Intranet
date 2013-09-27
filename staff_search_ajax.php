<?php
error_reporting (E_ALL ^ E_NOTICE);

$searchName = $_POST["search_name"];
$department = $_POST["department"];
$position = trim($_POST["position"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$from = $_POST["from"];

$dbh = new PDO("mysql:host=localhost;port=3306;dbname=staff;charset=utf8", 'root', '', array( PDO::ATTR_PERSISTENT => false));

#mysqli_report(MYSQLI_REPORT_ALL);

$searchSQL = "
  FROM staff s
       INNER JOIN department d USING (department_id)
       INNER JOIN company c USING (company_id)
       INNER JOIN address a USING (address_id)
        WHERE 1=1 ";

$searchKeywords = "";	   
	
if($department != ""){
	$searchSQL .= " AND department_id = " . $department;
	$stmtDepartment = $dbh->prepare("SELECT department_name FROM department WHERE department_id = " . $department);
	$stmtDepartment->execute();
	$searchKeywords .= " department is " . $stmtDepartment->fetchColumn(0).",";
} 
   
if($searchName != ""){
	$searchNameArray = explode(" ", $searchName);
	foreach($searchNameArray as $searchNamePart){
		$searchSQL .= " AND s.english_name LIKE '%" . $searchNamePart ."%'" ;
	}
	$searchKeywords .= " staff name contains '" . $searchName ."',";
}


if($position != ""){
	$searchSQL .= " AND position LIKE '%" . $position . "%'";
	$searchKeywords .= " position name contains '" . $position."',";
} 

if($email != ""){
	$searchSQL .= " AND email LIKE '%" . $email . "%'";
	$searchKeywords .= " email contains '" . $email."',";
} 

if($phone != ""){
	$searchSQL .= " AND s.staff_id IN (SELECT sp.staff_id FROM staff_phone sp INNER JOIN phone p USING(phone_id) WHERE p.phone_number LIKE '%" . $phone . "%' OR p.ext LIKE '%" . $phone . "%')";
	$searchKeywords .= " phone contains '" . $phone."',";
} 

$searchRecordSQL = "SELECT s.staff_id,
	   s.email,
       s.english_name,
       s.chinese_name,
       s.position,
	   s.email,
	   s.team,
       d.department_name,
       c.english_name AS company_name,
       a.address,
	   c.entity_name " . $searchSQL;
	   

if($from != ""){
	$searchRecordSQL .= " LIMIT " . $from .", 20";
}else{
	$searchRecordSQL .= " LIMIT 0, 20";
}

//print $searchRecordSQL;

$searchCountSQL = "SELECT COUNT(*)" . $searchSQL;

$totalRecordCount = 0;
$recordGroups = array();

$stmtTotalCount = $dbh->prepare($searchCountSQL);
$stmtTotalCount->execute();

$totalRecordCount = $stmtTotalCount->fetchColumn(0);


$htmlCode = "";
$haveMoreRecords = false;

//$htmlCode .= $searchRecordSQL;
$stmt = $dbh->prepare($searchRecordSQL);
$stmt->execute();
$i = 0;
while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {

	if($i == 0){
		$haveMoreRecords = true;
		$to = $from + 20;
		$htmlCode .= "<div id=\"group_$from\"><br/>Displaying record(s) ". ($from + 1) ." - " .(($to > $totalRecordCount)?$totalRecordCount:$to) ." <span class=\"lblGroup\"></span> <br/><br/>";
	}
	$i++;
	
	array_push($recordGroups,$rs->department_name);
	$htmlCode .= "<a name=\"" . $rs->department_name . "\"/>";
	$htmlCode .= '<div class="staff_search_result" >';
	$htmlCode .= "<table>\n";
	$htmlCode .= "<tr><td><b>Name</b></td><td>" . $rs->english_name . "&nbsp;&nbsp;" . $rs->chinese_name ."</td></tr>\n";
	$htmlCode .= "<tr><td><b>Department</b></td><td>" . $rs->department_name . "</td></tr>\n";
	if($rs->team != ""){
		$htmlCode .= "<tr><td><b>Team</b></td><td>" . $rs->team . "</td></tr>\n";
	}
	$htmlCode ;
	$htmlCode .= "<tr><td><b>Position</b></td><td>" . $rs->position . "</td></tr>\n";
	
	if($rs->email != ""){
		$htmlCode .= "<tr><td><b>Email</b></td><td><a href=\"mailto:" . $rs->email ."\">".$rs->email."</a></td></tr>\n";
	}

	$stmtPhone = $dbh->prepare('SELECT p.phone_number, p.phone_type, p.ext
	  FROM phone p INNER JOIN staff_phone sp USING (phone_id)
	 WHERE sp.staff_id = :staff_id');
	$stmtPhone->bindParam(':staff_id', $rs->staff_id, PDO::PARAM_STR, 12);
	$stmtPhone->execute();
	if($stmtPhone->rowCount() > 0){
		$htmlCode .= "<tr><td><b>Phone</b></td><td>";
		while ($rsPhone = $stmtPhone->fetch(PDO::FETCH_OBJ)) {
			$htmlCode .= "" . $rsPhone->phone_number;
			if($rsPhone->ext != ""){
				$htmlCode .= "&nbsp;ext:" . $rsPhone->ext;
			}
			$htmlCode .= "&nbsp; (" . $rsPhone->phone_type .") <br/>";
		}
		$htmlCode .= "</td></tr>";
	}

	$htmlCode .= "<tr><td><b>Company</b></td><td>" . $rs->company_name . "&nbsp;</td></tr>\n";
	$htmlCode .= "<tr><td><b>Address</b></td><td>" . $rs->address . "</td></tr>";
	
	$htmlCode .= "</td></tr></table>\n\n";
	$htmlCode .= '</div>';
}

$recordGroups = array_unique($recordGroups);

$recordGroupString = "";
foreach($recordGroups as $recordGroupItem){
	$recordGroupString  .= "<a href=\"#" . $recordGroupItem . "\">".$recordGroupItem."</a>&nbsp;&nbsp;";
}

$htmlCode .= "
</div>
";
$data["from"]=$from;
$data["recordCount"]=$totalRecordCount;
$data["searchKeywords"]=rtrim($searchKeywords,',');
$data["haveMoreRecords"]=$haveMoreRecords;
$data["recordGroup"]=$recordGroupString;
$data["htmlCode"]=$htmlCode;
echo(json_encode($data));
?>