<?php
include_once("model/model.php"); 

class login_Model extends ModelClass 
{
	public function __construct()  
	{
		parent::__construct(); 
		
		switch($this->getAction()) 
		{
			case 'logout': 
				$this->logout();
				break;
			default: 
				$this->login(); 
				break;
		}
		
		include_once("view/login.phtml"); 
	}
	
	public function login() 
	{
		if(isset($_POST['login'])) 
		{
			#$client = $this->sql_query("SELECT * FROM `klient` WHERE `login`='".addslashes($_POST['username'])."' LIMIT 1"); 
			#$employee = $this->sql_query("SELECT * FROM `pracownik` WHERE `login`='".addslashes($_POST['username'])."' LIMIT 1"); 
			$client = klient::findKlientWhereLogin($_POST['username']); #w tym nie bardzo sie da zrobic addslashes() ale moze nie wybuchnie...
                        $employee = pracownik::findPracownikWhereLogin($_POST['username']);
			if($_POST['username'] == "" || $_POST['password'] == "") 
				$this->redirect("index.php?url=login", "error", "Nie wprowadzono danych."); 
			else if(!$client && !$employee) 
				$this->redirect("index.php?url=login", "error", "Niepoprawna nazwa użytkownika."); 
			else if($_POST['password'] != $client['haslo'] && $_POST['password'] != $employee['haslo']) 
				$this->redirect("index.php?url=login", "error", "Niepoprawne hasło."); 
			else if($client)
			{
				$_SESSION['logged'] = true; 
				$_SESSION['id_klienta'] = $client['ID_klienta']; 
				$_SESSION['koszyk'] = array();
					$this->redirect("index.php?url=profil", "success", "Zostałeś zalogowany pomyślnie jako klient!");	
			}
			else if($employee)
			{
				$_SESSION['logged'] = true; 
				$_SESSION['id_pracownika'] = $employee['ID_pracownika']; 
				$_SESSION['koszyk'] = array();
					$this->redirect("index.php?url=profil", "success", "Zostałeś zalogowany pomyślnie jako administrator!");	
			}
		}
	}
	
	public function logout() 
	{
		unset($_SESSION['koszyk']);
		session_unset(); 
		$this->redirect("index.php?url=login", "info", "Zostałeś wylogowany z serwisu!");
	}
}
?>