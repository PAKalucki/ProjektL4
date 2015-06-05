<?php
include_once("model/model.php"); 

class uzytkownik_Model extends ModelClass 
{
	public $zarzadzaj = false;

	public function __construct()
	{
		parent::__construct();
		
		if (!$this->isLogged())
		    $this->redirect($this->generateUrl(), "error", "Zaloguj sie aby moc korzystac z tej funkcji sklepu.");
		
		switch ($this->getAction()) {
			case 'edytuj':
				$this->zarzadzaj = $this->edytuj();
			    include_once("view/uzytkownik_edytuj.phtml");
				break;
			case 'usun':
				$this->zarzadzaj = $this->usun();
				include_once("view/uzytkownik_usun.phtml");
				break;
		    default:
		    	$this->pokaz();
		}
	}
	public function pokaz()
	{
		if($this->isAdmin())
		{
			$check = false;
		
			if($result=klient::allKlient())
			{
				
				$check = true;
			}
			include "/../view/uzytkownik.phtml";
		}
	}
	public function usun()
	{
		if($this->isAdmin())
		{
			if(isset($_GET['id']))
			{
				if(isset($_POST['usun']))
				{
					#$query1 = mysql_query("SELECT * FROM `zamowienie` WHERE `KLIENT_ID_klienta`='".addslashes($_GET['id'])."'"); 
					$query1 = zamowienie::findZamowienieIDKlienta($_GET['id']);
                                    for($i=0;$i<count($query1);$i++)
					{       
                                                $x = $query1[$i];
						#mysql_query("DELETE FROM `pozycja_zamowienia` WHERE `ZAMOWIENIE_ID_zamowienia` = '".$x['ID_zamowienia']."'");
						poz_zamowienia::deletePozycja($x['ID_zamowienia']);
                                                #mysql_query("DELETE FROM `zamowienie` WHERE `KLIENT_ID_klienta` = ".$_GET['id'].""); czemu to tez jest w petli?
                                                
                                                
                                        }
                                        zamownie::deleteZamownieKlienta($_GET['id']);
					#mysql_query("DELETE FROM `klient` WHERE `ID_klienta` = ".$_GET['id']."");
                                        klient::deleteKlient($_GET['id']);
					$this->redirect("index.php?url=uzytkownik", "error", "Klient zostal poprawnie usuniety z bazy danych.");
				}	
			}
		}
		else
		{
			if(isset($_POST['usun']))
			{
				#$query1 = mysql_query("SELECT * FROM `zamowienie` WHERE `KLIENT_ID_klienta`='".$this->getLoggedClientId()."'"); 
				$query1 = zamowienie::findZamowienieIDKlienta($this->getLoggedClientId());
                            for($i=0;$i<count($query1);$i++)
				{       $x=$query1[0];
					#mysql_query("DELETE FROM `pozycja_zamowienia` WHERE `ZAMOWIENIE_ID_zamowienia` = '".$x['ID_zamowienia']."'");
					poz_zamowienia::deletePozycja($x['ID_zamowienia']);
                                #mysql_query("DELETE FROM `zamowienie` WHERE `KLIENT_ID_klienta` = ".$this->getLoggedClientId()."");
				}
                                zamowienie::deleteZamownieKlienta($this->getLoggedClientId());
				#mysql_query("DELETE FROM `klient` WHERE `ID_klienta` = ".$this->getLoggedClientId()."");
                                klient::deleteKlient($this->getLoggedClientId());
				unset($_SESSION['koszyk']);
				session_unset(); 
				$this->redirect("index.php", "error", "Twoje konto zostalo usuniete z bazy danych.");
			}
		}	
	}	
	public function edytuj()
	{
		if($this->isAdmin())
		{
			if(isset($_GET['id']))
			{
				if(!isset($_POST['edytuj']))
				{
					#$result = $this->sql_query("SELECT * FROM `klient` WHERE `ID_klienta` = '".addslashes($_GET['id'])."' LIMIT 1");
					$result=klient::findKlient($_GET['id'])->as_array();
                                    if($result)
					{
						return $result;
					}
				}
				else if(isset($_POST['edytuj']))
				{
					if($_POST['login'] == '' || $_POST['haslo'] == '' || $_POST['imie'] == '' || $_POST['nazwisko'] == '' || $_POST['miasto'] == '' || $_POST['kod_pocztowy'] == '' || $_POST['ulica'] == '' || $_POST['nr_domu'] == '' || $_POST['email'] == '')
						$this->redirect("index.php?url=uzytkownik", "error", "Wprowadz wszystkie dane poprawnie.");
					else if($_POST['login'] == '' || $_POST['haslo'] == '')
						$this->redirect("index.php?url=uzytkownik", "error", "Wprowadz poprawnie login i haslo.");
					else
					{
						#mysql_query("UPDATE klient SET imie = '".$_POST['imie']."', nazwisko = '".$_POST['nazwisko']."', miasto = '".$_POST['miasto']."', 
                                                #kod_pocztowy = '".$_POST['kod_pocztowy']."', ulica = '".$_POST['ulica']."', 
                                                #nr_domu = '".$_POST['nr_domu']."', nr_lokalu = '".$_POST['nr_lokalu']."', telefon = '".$_POST['telefon']."', 
                                                #email = '".$_POST['email']."', login = '".$_POST['login']."', haslo = '".$_POST['haslo']."', data_modyfikacji = '".time()."' WHERE ID_klienta = '".addslashes($_GET['id'])."'"); 
						$tab=array($_POST['imie'],$_POST['nazwisko'],$_POST['miasto'],$_POST['kod_pocztowy'],$_POST['ulica'],$_POST['nr_domu'],$_POST['nr_lokalu'],$_POST['email'],$_POST['login'],$_POST['haslo'],$_POST['telefon'],date('Y-m-d'),);
                                                klient::updateKlient($_GET['id'],$tab);
                                            $this->redirect("index.php?url=uzytkownik", "error", "Konto poprawnie zaktualizowane.");
					}
				}
			}
		}
		else
		{
			if(!isset($_POST['edytuj']))
			{
				#$result = $this->sql_query("SELECT * FROM `klient` WHERE `ID_klienta` = '".$this->getLoggedClientId()."' LIMIT 1");
				$result = klient::findKlient($this->getLoggedClientId())->as_array();
                            if($result)
				{
					return $result;
				}
			}
			else if(isset($_POST['edytuj']))
			{
				if($_POST['login'] == '' || $_POST['haslo'] == '' || $_POST['imie'] == '' || $_POST['nazwisko'] == '' || $_POST['miasto'] == '' || $_POST['kod_pocztowy'] == '' || $_POST['ulica'] == '' || $_POST['nr_domu'] == '' || $_POST['email'] == '')
					$this->redirect("index.php?url=profil", "error", "Wprowadz wszystkie dane poprawnie.");
				else if($_POST['login'] == '' || $_POST['haslo'] == '')
					$this->redirect("index.php?url=profil", "error", "Wprowadz poprawnie login i haslo.");
				else
				{
					#mysql_query("UPDATE klient SET imie = '".$_POST['imie']."', nazwisko = '".$_POST['nazwisko']."', miasto = '".$_POST['miasto']."', kod_pocztowy = '".$_POST['kod_pocztowy']."', ulica = '".$_POST['ulica']."', nr_domu = '".$_POST['nr_domu']."', nr_lokalu = '".$_POST['nr_lokalu']."', telefon = '".$_POST['telefon']."', email = '".$_POST['email']."', login = '".$_POST['login']."', haslo = '".$_POST['haslo']."', data_modyfikacji = '".time()."' WHERE ID_klienta = '".$this->getLoggedClientId()."'"); 
					$tab=array($_POST['imie'],$_POST['nazwisko'],$_POST['miasto'],$_POST['kod_pocztowy'],$_POST['ulica'],$_POST['nr_domu'],$_POST['nr_lokalu'],$_POST['email'],$_POST['login'],$_POST['haslo'],$_POST['telefon'],date('Y-m-d'),);
                                                klient::updateKlient($this->getLoggedClientId(),$tab);
                                    $this->redirect("index.php?url=profil", "error", "Konto poprawnie zaktualizowane.");
				}
			}
		}	
	}
}
?>