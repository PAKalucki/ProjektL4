<?php
include_once("model/model.php"); 

class profil_Model extends ModelClass 
{
	public function __construct()  
	{
		parent::__construct();
		
		if($this->isClient())
                {#$result = $this->sql_query("SELECT `imie`, `nazwisko` FROM `klient` WHERE `ID_klienta`='".$this->getLoggedClientId()."' LIMIT 1");
                    $result=klient::profil($this->getLoggedClientId());
                    
                }
                    if($this->isAdmin()) {
			#$result = $this->sql_query("SELECT `imie`, `nazwisko` FROM `pracownik` WHERE `ID_pracownika`='".$this->getLoggedAdminId()."' LIMIT 1");
                       $result = pracownik::profil($this->getLoggedAdminId());
                        
                    }
		
		include_once("view/profil.phtml");
	}
}
?>