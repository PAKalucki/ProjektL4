<?php
include_once("model/model.php"); 

class koszyk_Model extends Model 
{
	public $koszyk = false;

	public function __construct()
	{
		parent::__construct();
				
		switch ($this->getAction()) {
			case 'dodaj':
				$this->dodaj();
				break;
			case 'usun':
				$this->usun();
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
		if($this->isLogged())
		{
			if(count($_SESSION['koszyk']) > 0)
			{
				for($i=0; $i < count($_SESSION['koszyk']); $i++)
				{
					$result[$i] = mysql_query("SELECT * FROM produkt p, produkt_cena pc WHERE p.ID_produktu = ".$_SESSION['koszyk'][$i]." AND pc.PRODUKT_ID_produktu = p.ID_produktu");	
				}
			}
		}
		else
			$this->redirect("index.php?url=produkt", "error", "Zaloguj sie aby zobaczyc swoj koszyk.");

		
		include "view/koszyk.phtml";
	}
	
	public function dodaj()
	{
		if($this->isLogged())
		{		
			array_push($_SESSION['koszyk'], $_GET['id']);
			$this->redirect("index.php?url=produkt&page=pokaz&id=".$_GET['id']."", "error", "Produkt zostal dodany do koszyka.");
		}
	}
	
	public function usun()
	{
		if($this->isLogged())
		{
			for($i=0; $i<count($_SESSION['koszyk']); $i++)
			{
				if($_SESSION['koszyk'][$i] == $_GET['id'])
					unset($_SESSION['koszyk'][$i]);
			}
			$_SESSION['koszyk'] = array_merge($_SESSION['koszyk']);
			$this->redirect("index.php?url=koszyk", "error", "Produkt zostal usuniety z koszyka.");
		}
	}
	
	public function zamow()
	{
		if($this->isLogged())
		{
			if(!isset($_POST['zamow_potwierdz']))
			{
				if(count($_SESSION['koszyk']) > 0)
				{
					for($i=0; $i < count($_SESSION['koszyk']); $i++)
					{
						switch($_POST['platnosc'])
						{
							case "p":
								$pl = "Paragon";
							break;
							case "f":
								$pl = "Faktura";
							break;
						}
						switch($_POST['dostawa'])
						{
							case "l":
								$do = "List (zwykla paczka)";
							break;
							case "e":
								$do = "List ekonomiczny";
							break;
							case "p":
								$do = "Przesylka polecona";
							break;
							case "k":
								$do = "Przesylka kurierem";
							break;
						}
						
						$y="ilosc";
						
						if($this->isAdmin())
							$result[$i] = mysql_query("SELECT * FROM pracownik p, produkt pr, produkt_cena pc WHERE p.ID_pracownika = ".$this->getLoggedAdminId()." AND pr.ID_produktu = ".$_SESSION['koszyk'][$i]." AND pc.PRODUKT_ID_produktu = pr.ID_produktu");
						else
							$result[$i] = mysql_query("SELECT * FROM klient k, produkt pr, produkt_cena pc WHERE k.ID_klienta = ".$this->getLoggedClientId()." AND pr.ID_produktu = ".$_SESSION['koszyk'][$i]." AND pc.PRODUKT_ID_produktu = pr.ID_produktu");	
						
					}
				}
				include "view/koszyk_zamow.phtml";
			}
			else if(isset($_POST['zamow_potwierdz']))
			{
				if($this->isLogged())
				{
					for($i=0; $i < count($_SESSION['koszyk']); $i++)
					{
						$y="ilosc";
						$z=$y.$i;
						//$query = $this->sql_query("SELECT * FROM produkt WHERE ID_produktu = ".$_SESSION['koszyk'][$i]."");
						//if($query[0]['ilosc_produktow]')
						if($this->isAdmin())
							mysql_query("INSERT INTO zamowienie VALUES (NULL, '".$this->getLoggedAdminId()."', 'p', '".time()."', NULL, NULL, NULL, NULL, NULL, '".$_POST['dostawa']."', '".$_POST['platnosc']."', NULL)");
						else
							mysql_query("INSERT INTO zamowienie VALUES (NULL, '".$this->getLoggedClientId()."', 'p', '".time()."', NULL, NULL, NULL, NULL, NULL, '".$_POST['dostawa']."', '".$_POST['platnosc']."', NULL)");
						$id_z = mysql_insert_id();
						mysql_query("INSERT INTO pozycja_zamowienia VALUES (NULL, '".$id_z."', '".$_SESSION['koszyk'][$i]."', '".$_POST[$z]."')");
						mysql_query("UPDATE produkt SET ilosc_produktow = (ilosc_produktow-'".$_POST[$z]."') WHERE ID_produktu = '".$_SESSION['koszyk'][$i]."'");
					}
					$_SESSION['koszyk'] = array();
					$this->redirect("index.php?url=koszyk", "error", "Zamowienie zostalo przyjete do realizacji!");
				}
				else
					$this->redirect("index.php?url=koszyk", "error", "Musisz byc zalogowany aby dokonac zamowienia.");
			}
		}
		else
			$this->redirect("index.php?url=produkt", "error", "Zaloguj sie aby zlozyc zamowienie.");
	}

}
?>