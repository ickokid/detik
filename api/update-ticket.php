<?php
$time_start = time();

include "../config/config.php";
include "../libraries/Authentification.php";

$authentification = new Authentification();

$response = array();
$valid = 0;
$msg = "";
$updated_at = 0;

$event_id = isset($_POST["event_id"])?intval($_POST["event_id"]):0;
$ticket_code = isset($_POST["ticket_code"])?$_POST["ticket_code"]:"";
$status = isset($_POST["status"])?$_POST["status"]:"";

if (!empty($event_id) && !empty($ticket_code) && !empty($status)) {
	$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($conn === false){
		$response['valid'] = $valid;
		$response['msg'] = "Tidak berhasil terhubung ke database";
		echo json_encode($response);
		die();
	}
	
	if(!preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $ticket_code))
	{
		$response['valid'] = $valid;
		$response['msg'] = "Format kode tiket tidak sesuai (harus alphanumeric)";
		echo json_encode($response);
		die();
	} else {
		if($status == 1 || $status == 2){
			$ticket_code = strtoupper($ticket_code);
			
			$sql = "SELECT ticket_id FROM ticket WHERE event_id = ".$event_id." AND ticket_code = '".$ticket_code."'";
			if($result = mysqli_query($conn, $sql)){
				if(mysqli_num_rows($result) > 0){
					$row = mysqli_fetch_array($result);
					$ticket_id = isset($row['ticket_id'])?intval($row['ticket_id']):0;
					$updated_at = time();
					
					$sql = "UPDATE ticket SET ticket_status = ".$status.", ticket_update_date = '".date("Y-m-d H:i:s", $updated_at)."' WHERE ticket_id = ".$ticket_id;
					if ($result = mysqli_query($conn, $sql)) {
						$ticket_status_desc = "available";
						if($status == 2){
							$ticket_status_desc = "claimed";
						}	

						$valid = 1;
						$status = $ticket_status_desc;
					} 
				} else {
					$response['valid'] = $valid;
					$response['msg'] = "Kode tiket tidak terdaftar";
					echo json_encode($response);
					die();
				}	
			}
		} else {
			$response['valid'] = $valid;
			$response['msg'] = "Parameter tidak lengkap";
			echo json_encode($response);
			die();
		}	
	}
	
	mysqli_close($conn);
} else {
	$response['valid'] = $valid;
	$response['msg'] = "Parameter tidak lengkap";
	echo json_encode($response);
	die();
}

$response['valid'] = $valid;
$response['status'] = $status;
$response['updated_at'] = $updated_at;
if(!empty($msg)) $response['msg'] = $msg;
$response['execution_time'] = (microtime(true) - $time_start);

echo json_encode($response);
?>