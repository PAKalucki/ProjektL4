<?php
include_once("model/model.php"); 

class opinie_Model extends ModelClass 
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
	{# W BAZIE NIE MA POLACZENIA MIEDZY OPINIA.NICK A KLIENT.ID_KLIENTA tworzenie polaczen bazujacych na szczesciu nie jest dobrym pomyslem, tak samo jak robienie joina pomiedzy różnymi typami danych bo id-number, opinia-varchar, proponuje przerobic baze...
         # findOpiniaKlient(); zwraca numeryczna tablice asocjacyjnych tablic odpowiadajacym rekorda tabeli lub FALSE jesli nie znaleziono   
		$check = false;
		#$result2 = $this->sql_query("SELECT * FROM opinia o, klient k, produkt p WHERE o.PRODUKT_ID_produktu = ".$_GET['id']." AND o.nick = k.ID_klienta AND p.ID_produktu = o.PRODUKT_ID_produktu");
		
                if($result = opinia::findOpiniaKlient($_GET['id']))
		{		
			#$result = $this->sql_query("SELECT * FROM opinia o, klient k, produkt p WHERE o.PRODUKT_ID_produktu = ".$_GET['id']." AND o.nick = k.ID_klienta AND p.ID_produktu = o.PRODUKT_ID_produktu");
			
                        $check = true;
		}
		include "/../view/opinie.phtml";
	}
}
?>