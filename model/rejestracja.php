<?php
include_once("model/model.php"); 

class rejestracja_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
	
		$this->rejestracja();

		include_once("view/rejestracja.phtml"); 
	}
	
	public function rejestracja() 
	{
		if(isset($_POST['zarejestruj']))
		{
			$result = $this->sql_query("SELECT * FROM `klient` WHERE `login`='".addslashes($_POST['login'])."' LIMIT 1"); 

			if($_POST['login'] == "" || $_POST['haslo'] == "" || $_POST['imie'] == "" || $_POST['nazwisko'] == "" || $_POST['email'] == "" || $_POST['telefon'] == "")
				$this->redirect("index.php?url=rejestracja", "error", "Nie wprowadzono danych."); 
			else if($result) 
				$this->redirect("index.php?url=rejestracja", "error", "Takie konto już istnieje."); 
			else if(strlen($_POST['imie']) > 15 || !preg_match('@^[a-zA-Z]{3,20}$@', $_POST['imie']))
				$this->redirect("index.php?url=rejestracja", "error", "Podane imię jest nieprawidłowe!"); 
			else if(strlen($_POST['nazwisko']) > 30 || !preg_match('@^[a-zA-Z]{2,40}$@', $_POST['nazwisko']))
				$this->redirect("index.php?url=rejestracja", "error", "Podane nazwisko jest nieprawidłowe!");
			else if(strlen($_POST['email']) > 30 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				$this->redirect("index.php?url=rejestracja", "error", "Adres e-mail jest nieprawidłowy!");
			else if(strlen($_POST['nr_telefonu']) > 11 || !preg_match('@^[0-9]{6,20}$@', $_POST['telefon']))
				$this->redirect("index.php?url=rejestracja", "error", "Numer telefonu jest nieprawidłowy!");
			else 
			{			
				mysql_query("INSERT INTO klient VALUES (NULL, '".$_POST['imie']."', '".addslashes($_POST['nazwisko'])."', '".addslashes($_POST['miasto'])."', '".addslashes($_POST['kod_pocztowy'])."', '".addslashes($_POST['ulica'])."', 
					'".addslashes($_POST['nr_domu'])."', '".addslashes($_POST['nr_lokalu'])."', '".addslashes($_POST['email'])."', '".addslashes($_POST['login'])."', '".addslashes($_POST['haslo'])."', 
					'".addslashes($_POST['telefon'])."', '".time()."')")or die(mysql_error()); 
					
				$this->redirect("index.php?url=login", "success", "Konto utowrzone, teraz mozesz sie zalogowac do sklepu."); 
			}
		}
 	}
}
?>