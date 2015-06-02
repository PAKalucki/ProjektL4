<?php
include_once("model/model.php"); 

class login_Model extends Model 
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
			$client = $this->sql_query("SELECT * FROM `klient` WHERE `login`='".addslashes($_POST['username'])."' LIMIT 1"); 
			$employee = $this->sql_query("SELECT * FROM `pracownik` WHERE `login`='".addslashes($_POST['username'])."' LIMIT 1"); 
			
			if($_POST['username'] == "" || $_POST['password'] == "") 
				$this->redirect("index.php?url=login", "error", "Nie wprowadzono danych."); 
			else if(!$client && !$employee) 
				$this->redirect("index.php?url=login", "error", "Niepoprawna nazwa użytkownika."); 
			else if($_POST['password'] != $client[0][10] && $_POST['password'] != $employee[0][6]) 
				$this->redirect("index.php?url=login", "error", "Niepoprawne hasło."); 
			else if($client)
			{
				$_SESSION['logged'] = true; 
				$_SESSION['id_klienta'] = $client[0][0]; 
				$_SESSION['koszyk'] = array();
					$this->redirect("index.php?url=profil", "success", "Zostałeś zalogowany pomyślnie jako klient!");	
			}
			else if($employee)
			{
				$_SESSION['logged'] = true; 
				$_SESSION['id_pracownika'] = $employee[0][0]; 
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