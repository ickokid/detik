<?php
class Authentification
{
	protected $keyAccess = array(
				"superadmin"=>"77217A25432A462D4A614E645267556B58703272357538782F413F4428472B4B",
			 );
	
	public function __construct()
	{
		$this->__validate_auth_basic();
		$this->__validate_header_key();
	}

	public function __validate_header_key() {
		$apikey = "";
		$headers = getallheaders();
		foreach ($headers as $name => $value) {
			if($name == "X-Api-Key"){
				$apikey = $value; 
			}
		}
		
		if( empty($apikey) ) {
			$response['valid'] = 0;
			$response['msg'] = "Tidak dapat mengakses";
			echo json_encode($response);
			die();
		} else {
			if( !in_array( $apikey ,$this->keyAccess ) ){
				$response['valid'] = 0;
				$response['msg'] = "Tidak dapat mengakses";
				echo json_encode($response);
				die();
			}
		}
	}	
	
	public function __validate_auth_basic() {
		$valid_passwords = array (
							"partner" => "7234743777217A25432A462D4A614E645267556B58703273357638782F413F44",
						  );
		$valid_users = array_keys($valid_passwords);
		
		$user = @$_SERVER['PHP_AUTH_USER'];
		$pass = @$_SERVER['PHP_AUTH_PW'];
		
		$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
		
		if (!$validated) {
			header('WWW-Authenticate: Basic realm="TJB"');
			header('HTTP/1.1 401 Unauthorized');
			$response['valid'] = 0;
			$response['msg'] = "Tidak dapat mengakses";
			echo json_encode($response);
			die();
		}
	}
}	