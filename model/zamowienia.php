<?php
include_once("model/model.php"); 

class zamowienia_Model extends Model 
{
	public $zamowienie = false;

	public function __construct()
	{
		parent::__construct();
		
		if(!$this->isLogged())
			$this->redirect("index.php?url=produkt", "error", "Najpierw zaloguj sie na swoje konto.");
		
		switch ($this->getAction()) {
			case 'pokaz':
				$this->zamowienie = $this->pokaz();
			    include_once("view/zamowienia_pokaz.phtml");
				break;
			case 'platnosc':
				$this->zamowienie = $this->platnosc();
			    include_once("view/zamowienia_platnosc.phtml");
				break;
			case 'zwrot':
				$this->zamowienie = $this->zwrot();
			    include_once("view/zamowienia_zwrot.phtml");
				break;
			case 'zarzadzaj':
				$this->zarzadzaj();
				break;
			case 'zamow':
				$this->zamow();
				break;
		    default:
		    	$this->show();
		}
	}
	public function show()
	{
		$check = $this->sql_query("SELECT * FROM zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc, klient k WHERE k.ID_klienta = z.KLIENT_ID_klienta AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND pz.PRODUKT_ID_produktu = p.ID_produktu AND p.ID_produktu = pc.PRODUKT_ID_produktu");
			
		$ch = false;
		for($k=0; $k < count($check); $k++) {
			if($check[$k]['KLIENT_ID_klienta'] == $this->getLoggedClientId()) {
				$ch=true;
			}
		} 
		if($ch == true)
			$result = $this->sql_query("SELECT * FROM zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc WHERE z.KLIENT_ID_klienta = ".$this->getLoggedClientId()." AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND pz.PRODUKT_ID_produktu = p.ID_produktu AND p.ID_produktu = pc.PRODUKT_ID_produktu");	
		
		include "/../view/zamowienia.phtml";
	}
	
	public function pokaz()
	{
		if(isset($_GET['id']))
		{
			if($this->isLogged())
			{
				$result = $this->sql_query("SELECT * FROM klient k, zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc WHERE k.ID_klienta = ".$this->getLoggedClientId()." AND k.ID_klienta = z.KLIENT_ID_klienta AND z.ID_zamowienia = ".$_GET['id']." AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND pz.PRODUKT_ID_produktu = p.ID_produktu AND p.ID_produktu = pc.PRODUKT_ID_produktu");
				return $result[0];
			}
			else
				$this->redirect("index.php?url=zamowienia", "error", "Zaloguj sie aby zobaczyc szczegoly swojego zamowienia.");
		}
	}
	
	public function platnosc()
	{
		if(isset($_GET['id']))
		{
			if($this->isLogged())
			{
				$result = $this->sql_query("SELECT * FROM klient k, zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc WHERE k.ID_klienta = ".$this->getLoggedClientId()." AND k.ID_klienta = z.KLIENT_ID_klienta AND z.ID_zamowienia = ".$_GET['id']." AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND pz.PRODUKT_ID_produktu = p.ID_produktu AND p.ID_produktu = pc.PRODUKT_ID_produktu");
				if(!isset($_POST['zaplac']))
					return $result[0];
				else
				{
					mysql_query("UPDATE zamowienie SET data_oplacenia = '".time()."', status = 'o' WHERE ID_zamowienia = ".$_POST['id']."");
					$this->redirect("index.php?url=zamowienia", "error", "Dziekujemy za zaplacenie za zamawiane przedmioty.");
				}
			}
			else
				$this->redirect("index.php?url=zamowienia", "error", "Zaloguj sie aby dokonac platnosci.");
		}
	}
	
	public function zarzadzaj()
	{
		if($this->isAdmin())
		{
			$check = false;
			$result = $this->sql_query("SELECT * FROM zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc, klient k WHERE k.ID_klienta = z.KLIENT_ID_klienta AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND pz.PRODUKT_ID_produktu = p.ID_produktu AND p.ID_produktu = pc.PRODUKT_ID_produktu");
			if(count($result) > 0)
			{
				$wy = false;
				for($k=0; $k < count($result); $k++) {
					if($result[$k]['status'] == 'o') {
						$wy=true;
					}
				}
				if($wy == true)
					$result2 = $this->sql_query("SELECT * FROM klient k, zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc WHERE z.KLIENT_ID_klienta = k.ID_klienta AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND pz.PRODUKT_ID_produktu = p.ID_produktu AND p.ID_produktu = pc.PRODUKT_ID_produktu AND z.status = 'o'");
				
				$zw = false;
				for($k=0; $k < count($result); $k++) {
					if($result[$k]['status'] == 'z') {
						$zw=true;
					}
				}
				if($zw == true)
					$result3 = $this->sql_query("SELECT * FROM klient k, zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc WHERE z.KLIENT_ID_klienta = k.ID_klienta AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND pz.PRODUKT_ID_produktu = p.ID_produktu AND p.ID_produktu = pc.PRODUKT_ID_produktu AND z.status = 'z'");
				

				if(isset($_POST['zwrot']))
				{
					mysql_query("UPDATE zamowienie SET data_otrzymania_zwrotu = '".time()."', status = 'k' WHERE ID_zamowienia = ".$_POST['id']."");
					$this->redirect("index.php?url=zamowienia&page=zarzadzaj", "error", "Prosba o zwrot zostala przyjeta do realizacji.");
				}
				
				if(isset($_POST['wyslij']))
				{
					mysql_query("UPDATE zamowienie SET data_wyslania = '".time()."', status = 's' WHERE ID_zamowienia = ".$_POST['id']."");
					$this->redirect("index.php?url=zamowienia&page=zarzadzaj", "error", "Przedmiot zostal wyslany do klienta.");
				}
				$check=true;
			}
		
			include "/../view/zamowienia_zarzadzaj.phtml";
		}
	}
	
	public function zwrot()
	{
		if(isset($_GET['id']))
		{
			if($this->isLogged())
			{
				$result = $this->sql_query("SELECT * FROM klient k, zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc WHERE k.ID_klienta = ".$this->getLoggedClientId()." AND k.ID_klienta = z.KLIENT_ID_klienta AND z.ID_zamowienia = ".$_GET['id']." AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND pz.PRODUKT_ID_produktu = p.ID_produktu AND p.ID_produktu = pc.PRODUKT_ID_produktu");
				if(!isset($_POST['zwroc']))
					return $result[0];
				else if(isset($_POST['zwroc']))
				{
					mysql_query("UPDATE zamowienie SET data_zwrotu = '".time()."', status = 'z' WHERE ID_zamowienia = ".$_POST['id']."");
					$this->redirect("index.php?url=zamowienia", "error", "Prośba o zwrot zostala wysłana do pracowników sklepu.");
				}
			}
			else
				$this->redirect("index.php?url=zamowienia", "error", "Zaloguj sie aby dokonac platnosci.");
		}
	}

}
?>