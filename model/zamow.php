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
				$this->koszyk = $this->zamow();
			    include_once("view/koszyk_zamow.phtml");
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
			$this->redirect("index.php?url=produkt", "error", "Zaloguj sie aby zobaczyc szczegoly wybranego produktu.");

		
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
			$this->redirect("index.php?url=koszyk", "error", "Produkt zostal usuniety pomyslnie z koszyka.");
		}
	}
	


}
?>