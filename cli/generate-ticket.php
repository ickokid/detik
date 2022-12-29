<?php
$time_start = time();
ini_set('display_errors',1);
error_reporting(E_ALL);

/* 
Running in cmd / command
- C:\wamp64\bin\php\php7.2.33\php.exe -f "C:\wamp64\www\detik\cli\generate-ticket.php" 2 500
- php C:\\wamp64\\www\\detik\\cli\\generate-ticket.php 2 500 
*/

define("DOC_ROOT","C:\wamp64\www\detik\\");

include DOC_ROOT."config/config.php";
include DOC_ROOT."libraries/Ticket.php";

$event_id = isset($_SERVER["argv"][1])?intval($_SERVER["argv"][1]):0;
$total_tiket = isset($_SERVER["argv"][2])?intval($_SERVER["argv"][2]):0;

if (!empty($event_id) && !empty($total_tiket)) {
	$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($conn === false){
		die("ERROR: Tidak berhasil terhubung ke database. " . mysqli_connect_error());
	}
	
	$sql = "SELECT event_id FROM event WHERE event_id = ".$event_id;
	if($result = mysqli_query($conn, $sql)){
		if(mysqli_num_rows($result) < 1){
			$sql = "INSERT INTO event VALUES (".$event_id.", ".$total_tiket.", '".date("Y-m-d H:i:s")."')";
			if ($result = mysqli_query($conn, $sql)) {
				//Generate Ticket
				$ticket = new Ticket("DTK",7);
				
				$total_ticket_created = 0;
				$total_ticket_failed = 0;
				for ($i = 0; $i < $total_tiket; $i++){
					$ticket_code = $ticket->generateCode();

					$sql = "SELECT ticket_id FROM ticket WHERE ticket_code = '".$ticket_code."'";
					if($result = mysqli_query($conn, $sql)){
						if(mysqli_num_rows($result) < 1){
							$sql = "INSERT INTO ticket (ticket_code, event_id, ticket_status, ticket_create_date) VALUES ('".$ticket_code."', ".$event_id.", 1, '".date("Y-m-d H:i:s")."')";
							if ($result = mysqli_query($conn, $sql)) {
								$total_ticket_created++;
							} else {
								$total_ticket_failed++;
							}
						}
					}
				}
				
				unset($ticket);
				//End Generate Ticket
				
				echo "Event ID : ".$event_id."\n";
				echo "Total Tiket : ".$total_tiket."\n";
				echo "Berhasil Terdaftar\n";
				echo "Total Sukses Tiket Generate : ".$total_ticket_created."\n";
				echo "Total Gagal Tiket Generate : ".$total_ticket_failed."\n";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		} else{
			echo "Event ID = ".$event_id." telah Terdaftar!!!\n";
		}
	} else{
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	} 
	
	mysqli_close($conn);
}
else {
	echo "Silakan masukan Event ID & Total tiket\n";
}

echo "\nWaktu eksekusi dalam satuan detik: " . (microtime(true) - $time_start) . "\n";
?>