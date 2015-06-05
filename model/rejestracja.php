<?php
include_once("model/model.php"); 

class rejestracja_Model extends ModelClass 
{
	public function __construct()  
	{
		parent::__construct(); 
		switch ($this->getAction()) {
			case 'pracownik':
				$this->zarzadzaj = $this->pracownik();
			    include_once("view/rejestracja_pracownika.phtml");
				break;
		    default:
		    	$this->rejestracja();
		}

	}
	
	public function rejestracja() 
	{
		if(isset($_POST['zarejestruj']))
		{
			#$result = $this->sql_query("SELECT * FROM `klient` WHERE `login`='".addslashes($_POST['login'])."' LIMIT 1"); 
                        $result = klient::findKlientWhereLogin($_POST['login']);
                        $result2 = pracownik::findPracownikWhereLogin($_POST['login']);#nalezy tez sprawdzac czy nie istnieje takie konto pracownika bo bum   
			if($_POST['login'] == "" || $_POST['haslo'] == "" || $_POST['imie'] == "" || $_POST['nazwisko'] == "" || $_POST['email'] == "" || $_POST['telefon'] == "")
				$this->redirect("index.php?url=rejestracja", "error", "Nie wprowadzono danych."); 
			else if($result || $result2) 
				$this->redirect("index.php?url=rejestracja", "error", "Takie konto już istnieje."); 
			else if(strlen($_POST['imie']) > 15 || !preg_match('@^[a-zA-Z]{3,20}$@', $_POST['imie']))
				$this->redirect("index.php?url=rejestracja", "error", "Podane imię jest nieprawidłowe!"); 
			else if(strlen($_POST['nazwisko']) > 30 || !preg_match('@^[a-zA-Z]{2,40}$@', $_POST['nazwisko']))
				$this->redirect("index.php?url=rejestracja", "error", "Podane nazwisko jest nieprawidłowe!");
			else if(strlen($_POST['email']) > 30 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				$this->redirect("index.php?url=rejestracja", "error", "Adres e-mail jest nieprawidłowy!");
			else if(strlen($_POST['telefon']) > 20 || !preg_match('@^[0-9]{6,20}$@', $_POST['telefon']))
				$this->redirect("index.php?url=rejestracja", "error", "Numer telefonu jest nieprawidłowy!");
			else 
			{			
				#mysql_query("INSERT INTO klient VALUES (NULL, '".$_POST['imie']."', '".addslashes($_POST['nazwisko'])."', '".addslashes($_POST['miasto'])."', '".addslashes($_POST['kod_pocztowy'])."', '".addslashes($_POST['ulica'])."', 
					#'".addslashes($_POST['nr_domu'])."', '".addslashes($_POST['nr_lokalu'])."', '".addslashes($_POST['email'])."', '".addslashes($_POST['login'])."', '".addslashes($_POST['haslo'])."', 
					#'".addslashes($_POST['telefon'])."', '".time()."')")or die(mysql_error()); 
				$tab1=array($_POST['imie'],$_POST['nazwisko'],$_POST['miasto'],$_POST['kod_pocztowy'],$_POST['ulica'],$_POST['nr_domu'],$_POST['nr_lokalu'],$_POST['email'],$_POST['login'],$_POST['haslo'],$_POST['telefon'],date('Y-m-d'));
                                klient::addKlient($tab1); #w razie sukcesu zwraca id dodanego klienta ale jak zasygnalizowac blad?
				$to      = $_POST['email'];
				$subject = "Witamy w Naszym sklepie!";
				$message = "Witamy w sklepie Sklep Internetowy. Zyczymy udanych zakupow w Naszym sklepie! Sklep-intern";
				$headers = 'From: sklep-intern@wp.pl' . "\r\n" .
					'Reply-To: sklep-intern@wp.pl' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

				mail($to, $subject, $message, $headers); 
                $this->redirect("index.php", "success", "Konto zostalo zalozone pomyslnie.");
			}
		}
		include_once("view/rejestracja.phtml"); 
 	}
	public function pracownik() 
	{
		if(isset($_POST['zarejestruj']))
		{
			$result = pracownik::findPracownikWhereLogin($_POST['login']); 
                        $result2 = klient::findKlientWhereLogin($_POST['login']);#jak wyzej
			if($_POST['login'] == "" || $_POST['haslo'] == "" || $_POST['imie'] == "" || $_POST['nazwisko'] == "" || $_POST['email'] == "" || $_POST['telefon'] == "")
				$this->redirect("index.php?url=rejestracja&page=pracownik", "error", "Nie wprowadzono danych."); 
			else if($result || $result2) 
				$this->redirect("index.php?url=rejestracja&page=pracownik", "error", "Takie konto już istnieje."); 
			else if(strlen($_POST['imie']) > 15 || !preg_match('@^[a-zA-Z]{3,20}$@', $_POST['imie']))
				$this->redirect("index.php?url=rejestracja&page=pracownik", "error", "Podane imię jest nieprawidłowe!"); 
			else if(strlen($_POST['nazwisko']) > 30 || !preg_match('@^[a-zA-Z]{2,40}$@', $_POST['nazwisko']))
				$this->redirect("index.php?url=rejestracja&page=pracownik", "error", "Podane nazwisko jest nieprawidłowe!");
			else if(strlen($_POST['email']) > 30 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				$this->redirect("index.php?url=rejestracja&page=pracownik", "error", "Adres e-mail jest nieprawidłowy!");
			else if(strlen($_POST['telefon']) > 20 || !preg_match('@^[0-9]{6,20}$@', $_POST['telefon']))
				$this->redirect("index.php?url=rejestracja&page=pracownik", "error", "Numer telefonu jest nieprawidłowy!");
			else 
			{			
				#mysql_query("INSERT INTO pracownik VALUES (NULL, '".$_POST['imie']."', '".addslashes($_POST['nazwisko'])."', '".addslashes($_POST['telefon'])."', '".addslashes($_POST['email'])."', '".addslashes($_POST['login'])."', '".addslashes($_POST['haslo'])."')")or die(mysql_error()); 
                                $tab2=array($_POST['imie'], $_POST['nazwisko'], $_POST['telefon'], $_POST['email'], $_POST['login'], $_POST['haslo']);
                                pracownik::addPracownik($tab2);
                                $this->redirect("index.php", "success", "Pracownik zostal dodany i posiada uprawnienia administratora.");
			}
		}
 	}
}
?>	