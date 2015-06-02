<?php
include_once("model/model.php"); 

class zdjecia_Model extends Model 
{
	public function __construct()
	{
		parent::__construct();
				
		switch ($this->getAction()) {
		    default:
		    	$this->show();
		}
	}
	public function show()
	{
		$result = $this->sql_query("SELECT * FROM zdjecia WHERE PRODUKT_ID_produktu = ".$_GET['id']."");
		include "/../view/zdjecia.phtml";
	}
}
?>