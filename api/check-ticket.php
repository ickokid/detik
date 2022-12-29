<?php
$time_start = time();

include "../config/config.php";
include "../libraries/Authentification.php";

$authentification = new Authentification();

$response = array();
$valid = 0;
$status = "";
$msg = "";

$event_id = isset($_POST["event_id"])?intval($_POST["event_id"]):0;
$ticket_code = isset($_POST["ticket_code"])?$_POST["ticket_code"]:"";

if (!empty($event_id) && !empty($ticket_code)) {
	$ticket_code = strtoupper($ticket_code);
	
	if(!preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $ticket_code))
	{
		$response['valid'] = $valid;
		$response['msg'] = "Format kode tiket tidak sesuai (harus alphanumeric)";
		echo json_encode($response);
		die();
	} else {
		$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		if($conn === false){
			$response['valid'] = $valid;
			$response['msg'] = "Tidak berhasil terhubung ke database";
			echo json_encode($response);
			die();
		}
		
		$sql = "SELECT ticket_status FROM ticket WHERE event_id = ".$event_id." AND ticket_code = '".$ticket_code."'";
		if($result = mysqli_query($conn, $sql)){
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_array($result);
				$ticket_status = isset($row['ticket_status'])?intval($row['ticket_status']):0;
				
				$ticket_status_desc = "available";
				if($ticket_status == 2){
					$ticket_status_desc = "claimed";
				}	

				$valid = 1;
				$status = $ticket_status_desc;
			} else {
				$response['valid'] = $valid;
				$response['msg'] = "Kode tiket tidak terdaftar";
				echo json_encode($response);
				die();
			}	
		}
		
		mysqli_close($conn);
	}	
} else {
	$response['valid'] = $valid;
	$response['msg'] = "Parameter tidak lengkap";
	echo json_encode($response);
	die();
}

$response['valid'] = $valid;
$response['status'] = $status;
$response['ticket_code'] = $ticket_code;
if(!empty($msg)) $response['msg'] = $msg;
$response['execution_time'] = (microtime(true) - $time_start);

echo json_encode($response);
?>