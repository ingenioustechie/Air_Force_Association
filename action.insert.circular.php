<?php
// Comment below two lines to hide errors
ini_set("display_errors", "1");
error_reporting(E_ALL);
// ---

$circularNo = $_GET['circularNo'];
$circular_issue_date = $_GET['circular_issue_date'];
$circular_effective_date = $_GET['circular_effective_date'];
$circular_status = $_GET['circular_status']; //may be it will be one

$rank = $_GET['rank'];
$group = $_GET['group'];
$service_period = $_GET['service_period'];
$service_type = $_GET['service_type'];

$amount = $_GET['amount'];

require_once "vars/dbvars.php";



	try 
	{
		$mysqli = new mysqli($host, $username, $password, "afpms");
		
		
		/* check connection */
		if (mysqli_connect_errno()) {
    	throw new Exception(mysqli_connect_error(), 1);
    	}

    	/* turn autocommit on */
		$mysqli->autocommit(FALSE);

		$query_afms_circular_info = "INSERT INTO afpms_circular_info ( `circular_no`, `circular_issue_date`, `circular_effective_date`, `circular_status`) VALUES ($circularNo,$circular_issue_date,$circular_effective_date,$circular_status)";

		$mysqli->query($query_afms_circular_info);
		
		printf ("New Record has id %d.\n", $mysqli->insert_id);
		
		$afpms_circular_info_id = $mysqli->insert_id;

		if($afpms_circular_info_id == 0){
			throw new Exception(mysqli_error($mysqli), 4);
		}	
	
		$query_afpms_circular_categorization_info = "select id, group_id from  afpms_circular_categorization_info where rank = '$rank' and group_id = '$group' and service_period = $service_period and service_type = $service_type";

		//printf($query_afpms_circular_categorization_info);
		if(!$res_afpms_circular_categorization_info_id = $mysqli->query($query_afpms_circular_categorization_info)) {
			throw new Exception(mysqli_error($mysqli), 2);
		}

		while($row = $res_afpms_circular_categorization_info_id->fetch_assoc()) {
			$afpms_circular_categorization_info_id = $row['id'];
		}
		

		if(mysqli_num_rows($res_afpms_circular_categorization_info_id)==0) {
			throw new Exception(0, 3);
		}
		
		
		printf($afpms_circular_categorization_info_id);
		$query_afpms_circular_amount_info = "INSERT INTO afpms_circular_amount_info (`afpms_circular_info_id`, `afpms_circular_categorization_info_id`, `amount`) VALUES ($afpms_circular_info_id,$afpms_circular_categorization_info_id,$amount)";

		$mysqli->query($query_afpms_circular_amount_info);
		printf($query_afpms_circular_amount_info);
		$mysqli->commit();

		
	}
	catch(Exception $error)
	{
		if($error->getCode() == 1) 
		{
			echo "Could not connect to DB :: ".$error->getMessage();
		}
		if($error->getCode() == 2) 
		{
				echo "No Id present ";
		}	
		if($error->getCode() == 3) 
		{
				echo "no result found ";
		}
		if($error->getCode() == 4) 
		{
				echo "Circular Number is already present ";
		}


		$mysqli->close();
	}




exit;