<?php
class Ticket
{
	protected $prefix = "DTK";
	protected $length = 10;
	
	public function __construct($prefix, $length)
	{
		$this->prefix = $prefix;
		$this->length = $length;
	}

	public function generateCode()
	{
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';

		for ($i = 0; $i < $this->length; $i++){
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}	
		
		return $this->prefix.$randomString;
	}	
}	