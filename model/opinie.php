<?php
include_once("model/model.php"); 

class opinie_Model extends Model 
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
		$check = false;
		$result2 = $this->sql_query("SELECT * FROM opinia o, klient k, produkt p WHERE o.PRODUKT_ID_produktu = ".$_GET['id']." AND o.nick = k.ID_klienta AND p.ID_produktu = o.PRODUKT_ID_produktu");
		if(count($result2[0]) > 0)
		{		
			$result = $this->sql_query("SELECT * FROM opinia o, klient k, produkt p WHERE o.PRODUKT_ID_produktu = ".$_GET['id']." AND o.nick = k.ID_klienta AND p.ID_produktu = o.PRODUKT_ID_produktu");
			$check = true;
		}
		include "/../view/opinie.phtml";
	}
}
?>