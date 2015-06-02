<?php
include_once("model/model.php");

class main_Model extends Model
{

	public function __construct()  
	{
		parent::__construct();
		
		$check = false;
		$result2 = $this->sql_query("SELECT COUNT(pz.PRODUKT_ID_produktu) as top, p.nazwa_produktu, p.ID_produktu FROM pozycja_zamowienia pz, produkt p WHERE p.ID_produktu = pz.PRODUKT_ID_produktu GROUP BY pz.PRODUKT_ID_produktu ORDER BY top DESC LIMIT 3");
		if(count($result2[0]) > 0)
		{
			$result = $this->sql_query("SELECT COUNT(pz.PRODUKT_ID_produktu) as top, p.nazwa_produktu, p.ID_produktu FROM pozycja_zamowienia pz, produkt p WHERE p.ID_produktu = pz.PRODUKT_ID_produktu GROUP BY pz.PRODUKT_ID_produktu ORDER BY top DESC LIMIT 3");
			$check = true;
		}
		include_once("view/main.phtml");
	}

}
?>