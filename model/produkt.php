<?php
include_once("model/model.php"); 

class produkt_Model extends ModelClass 
{
	public $produkty = false;

	public function __construct()
	{
		parent::__construct();
				
		switch ($this->getAction()) {
			case 'dodaj':
				$this->produkty = $this->dodaj();
			    include_once("view/produkt_dodaj.phtml");
				break;
			case 'pokaz':
				$this->produkty = $this->pokaz();
			    include_once("view/produkt_pokaz.phtml");
				break;
			case 'edytuj':
				$this->produkty = $this->edytuj();
			    include_once("view/produkt_edytuj.phtml");
				break;
			case 'usun':
				$this->produkty = $this->usun();
				include_once("view/produkt_usun.phtml");
				break;
			case 'opinia':
				$this->opinia();
				break;
		    default:
		    	$this->show();
		}
	}
	public function show()
	{
		$query = produkt::allProdukt();
		$check = false;
		if(count($query[0])>0)
		{
			#$sort1 = $this->sql_query("SELECT grupa_produktow FROM produkt GROUP BY grupa_produktow ORDER BY grupa_produktow ASC");	
			#$sort2 = $this->sql_query("SELECT grupa_wiekowa FROM produkt GROUP BY grupa_wiekowa ORDER BY grupa_wiekowa ASC");	
                        $sort1 = produkt::grupaProdukt1();
                        $sort2 = produkt::grupaProdukt2();
			if(isset($_POST['sortuj_k']))
				$sel_k = $_POST['sortuj_k'];
			if(isset($_POST['sortuj_w']))
				$sel_w = $_POST['sortuj_w'];
			
			if((!isset($_POST['sortuj_k']) or $_POST['sortuj_k'] == "none") and (!isset($_POST['sortuj_w']) or $_POST['sortuj_w'] == "none"))
				$result = produkt::allProdukt();
			elseif(isset($_POST['sortuj_k']) and $_POST['sortuj_w'] == "none")
                                $result=produkt::sortProdukt($_POST['sortuj_k']);
				#$result = $this->sql_query("SELECT * FROM produkt WHERE grupa_produktow = '".$_POST['sortuj_k']."' ORDER BY grupa_produktow ASC");
			elseif(isset($_POST['sortuj_w']) and $_POST['sortuj_k'] == "none")
				$result=produkt::sortWiek($_POST['sortuj_w']);
                                #$result = $this->sql_query("SELECT * FROM produkt WHERE grupa_wiekowa = '".$_POST['sortuj_w']."' ORDER BY grupa_wiekowa ASC");
			elseif(isset($_POST['sortuj_w']) and isset($_POST['sortuj_k']))
				$result = produkt::sortWiekProdukt($_POST['sortuj_k'],$_POST['sortuj_w']);
                                #$result = $this->sql_query("SELECT * FROM produkt WHERE grupa_produktow = '".$_POST['sortuj_k']."' and grupa_wiekowa = '".$_POST['sortuj_w']."'  ORDER BY grupa_produktow, grupa_wiekowa ASC");
			$check = true;
		}
		include "/../view/produkt.phtml";
	}
        
	public function dodaj() # warning mozna dodac produkt z brakujacymi danymi i wtedy wszystko sie wali
	{
		if(isset($_POST['dodaj']))
		{
			if($this->isAdmin())
			{	
				$spr = false;
				$id = 0;
				$name="";
				for($i=0; $i<count($_FILES['nazwyPlikow']['name']); $i++)
				{
				
					$error = "";
					$target_dir = "uploads/";
					$target_file[$i] = $target_dir . basename($_FILES["nazwyPlikow"]["name"][$i]);
					$uploadOk = 1;
					$imageFileType = pathinfo($target_file[$i],PATHINFO_EXTENSION);
					// Check if image file is a actual image or fake image
					if(isset($_POST["dodaj"])) {
						$checkk = getimagesize($_FILES["nazwyPlikow"]["tmp_name"][$i]);
						if($checkk !== false) {
							//$error =  "File is an image - " . $check["mime"] . ".";
							$uploadOk = 1;
						} else {
							//$error = "File is not an image.";
							$uploadOk = 0;
						}
					}
					// Check if file already exists
					if (file_exists($target_file[$i])) {
						$error = "Wybrany plik juz istnieje.";
						$uploadOk = 0;
					}
					// Check file size
					if ($_FILES["nazwyPlikow"]["size"][$i] > 500000) {
						$error = "Wybrany plik jest zbyt duzy.";
						$uploadOk = 0;
					}
					// Allow certain file formats + dodano rozszerzenie plikow duzymi literami ~przemek
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif"  && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG"
					&& $imageFileType != "GIF") {
						$error = "Zly format zdjecia, tylko JPG, JPEG, PNG & GIF.";
						$uploadOk = 0;
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						echo "<table align=center><tr><td>".$error."</td></tr></table>";
					// if everything is ok, try to upload file
					} else {
						if (move_uploaded_file($_FILES["nazwyPlikow"]["tmp_name"][$i], $target_file[$i])) {			
							if($name != $_POST['nazwa_produktu'])
							{
                                                                $tab1=array($_POST['nazwa_produktu'], $_POST['rozmiar'], $_POST['opis_produktu'], $_POST['grupa_produktow'], $_POST['grupa_wiekowa'], $_POST['ilosc_produktow']);
                                                                $id_p = produkt::addProdukt($tab1);
								#mysql_query("INSERT INTO produkt VALUES (NULL, '".$_POST['nazwa_produktu']."', '".$_POST['rozmiar']."', '".$_POST['opis_produktu']."', '".$_POST['grupa_produktow']."', '".$_POST['grupa_wiekowa']."', '".$_POST['ilosc_produktow']."')"); 
								#$id_p = mysql_insert_id();
								#mysql_query("INSERT INTO cena VALUES (NULL, '".$_POST['cena_magazynowa']."', '".time()."', '".$_POST['obowiazuje_do']."', '".$this->getLoggedAdminId()."')"); 
								$tab2=array($_POST['cena_magazynowa'], date('Y-m-d'), $_POST['obowiazuje_do'], $this->getLoggedAdminId());
                                                                $id_c=cena::addCena($tab2);
                                                                #$id_c = mysql_insert_id();
								#mysql_query("INSERT INTO produkt_cena VALUES (NULL, '".$_POST['cena_sprzedazy']."', '".$id_p."', '".$id_c."')"); 
								$tab3=array($_POST['cena_sprzedazy'], $id_p, $id_c);
                                                                prod_cena::addProdCena($tab3);
                                                                $id = $id_p;
								$name = $_POST['nazwa_produktu'];
							}
							#mysql_query("INSERT INTO zdjecia VALUES (NULL, '".basename( $_FILES["nazwyPlikow"]["name"][$i])."', '".$id."')");
							$tab4=array(basename( $_FILES["nazwyPlikow"]["name"][$i]), $id);
                                                        zdjecia::addZdjecia($tab4);
                                                        $spr == true;
							if($i == count($_FILES['nazwyPlikow']['name']))
								$this->redirect("index.php?url=produkt", "error", "Produkt zostal dodany poprawnie!");
						} else {
							$this->redirect("index.php?url=produkt", "error", "Wystapil blad podczas dodawania produktu.");
						}
					}
				}
			}
			else
				$this->redirect("index.php?url=produkt", "error", "Nie masz uprawnien do przegladania tej podstrony!");
		}
	}	
	public function pokaz()
	{
		if(isset($_GET['id']))
		{
			if($this->isLogged())
			{
				#$result = $this->sql_query("SELECT * FROM produkt p, produkt_cena pc, cena c, zdjecia z WHERE p.ID_produktu = ".$_GET['id']." AND p.ID_produktu = pc.PRODUKT_ID_produktu AND c.ID_ceny = pc.CENA_ID_ceny AND z.PRODUKT_ID_produktu = p.ID_produktu");
				$result = myqueriesORM::pokazORM($_GET['id']);
                                return $result;
			}
			else
				$this->redirect("index.php?url=produkt", "error", "Zaloguj sie aby zobaczyc szczegoly wybranego produktu.");
		}
	}
	public function usun() 
	{
		if(isset($_GET['id']))
		{
			if($this->isAdmin())
			{
				#$result = $this->sql_query("SELECT * FROM produkt p, produkt_cena pc, cena c WHERE p.ID_produktu = ".$_GET['id']." AND p.ID_produktu = pc.PRODUKT_ID_produktu AND c.ID_ceny = pc.CENA_ID_ceny LIMIT 1");
				$result= myqueriesORM::pokazORM2($_GET['id']);
                            if(!isset($_POST['usun']))
				{
					return $result;
				}
				else if(isset($_POST['usun']))
				{
					#$foto = $this->sql_query("SELECT * FROM zdjecia WHERE PRODUKT_ID_produktu = ".$_GET['id']."");					
					$foto = zdjecia::findZdjeciaWhere($_GET['id']); # ???
                                        zdjecia::deleteZdjeciaWhere($result['ID_produktu']); # wyglada na to ze 1 produkt moze miec tylko 1 zdjecie, jak nie to bum...
					#$opinia = $this->sql_query("SELECT * FROM opinia WHERE PRODUKT_ID_produktu = ".$_GET['id']."");
					$opinia = opinia::findOpiniaWhere($_GET['id']); # ???
                                        #mysql_query("DELETE FROM opinia WHERE PRODUKT_ID_produktu = ".$result['ID_produktu']."");
                                        opinia::deleteAllOpiniaWhere($result['ID_produktu']);    
					#mysql_query("DELETE FROM produkt_cena WHERE CENA_ID_ceny = ".$result['ID_ceny'].""); chyba zle bo jedna cena moze miec wiele produkt_cena ???
                                        prod_cena::deleteProdCenaWhere($result['ID_ceny']);
					#mysql_query("DELETE FROM produkt WHERE ID_produktu = ".$result['ID_produktu']."");
					produkt::deleteProdukt($result['ID_produktu']);
                                        #mysql_query("DELETE FROM cena WHERE ID_ceny = ".$result['ID_ceny']."");
                                        cena::deleteCena($result['ID_ceny']);
					$this->redirect("index.php?url=produkt", "error", "Produkt zostal pomyslnie usuniety.");
				}
			}
			else
				$this->redirect("index.php?url=produkt", "error", "Niestety nie posiadasz uprawniec administratora.");
		}
		
	}	
	public function edytuj()
	{
		if(isset($_GET['id']))
		{
			if($this->isAdmin())
			{
				#$result = $this->sql_query("SELECT * FROM produkt p, produkt_cena pc, cena c WHERE p.ID_produktu = ".$_GET['id']." AND p.ID_produktu = pc.PRODUKT_ID_produktu AND c.ID_ceny = pc.CENA_ID_ceny LIMIT 1");
				$result= myqueriesORM::pokazORM2($_GET['id']);
                            if(!isset($_POST['edytuj']))
				{
					return $result;
				}
				else if(isset($_POST['edytuj']))
				{       
					#mysql_query("UPDATE produkt SET nazwa_produktu = '".$_POST['nazwa_produktu']."', opis_produktu = '".$_POST['opis_produktu']."', grupa_produktow = '".$_POST['grupa_produktow']."', grupa_wiekowa = '".$_POST['grupa_wiekowa']."', ilosc_produktow = '".$_POST['ilosc_produktow']."' WHERE ID_produktu = ".$result[0]['ID_produktu'].""); 
					$tab1=array($_POST['nazwa_produktu'],$_POST['opis_produktu'],$_POST['grupa_produktow'],$_POST['grupa_wiekowa'],$_POST['ilosc_produktow']);
                                        produkt::updateProdukt($result['ID_produktu'],$tab1);
                                        #mysql_query("UPDATE cena SET cena_magazynowa = '".$_POST['cena_magazynowa']."', obowiazuje_do = '".$_POST['obowiazuje_do']."' WHERE ID_ceny = ".$result[0]['ID_ceny']."");
					$tab2=array($_POST['cena_magazynowa'],$_POST['obowiazuje_do'],);
                                        cena::updateCena($result['ID_ceny'],$tab2);
                                        #mysql_query("UPDATE produkt_cena SET cena_sprzedazy = '".$_POST['cena_sprzedazy']."' WHERE CENA_ID_ceny = ".$result[0]['ID_ceny']."");
                                        prod_cena::updateProdCena1($result['ID_ceny'], $_POST['cena_sprzedazy']);# dla CENA_ID_ceny a nie ID_obecnej_ceny, niebezpieczne?
                                        $this->redirect("index.php?url=produkt", "error", "Produkt zostal zedytowany pomyslnie.");
				}
			}
			else
				$this->redirect("index.php?url=produkt", "error", "Niestety nie posiadasz uprawnien administratora.");
		}
	}
	
	public function opinia()
	{
		if(isset($_POST['dodaj_opinie']))
		{
			if(isset($_POST['id_produktu']))
			{
				if($this->isAdmin())
                                {#mysql_query("INSERT INTO opinia VALUES (NULL, '".$this->getLoggedAdminId()."', '".$_POST['skala']."', '".$_POST['opinia']."', '".$_POST['id_produktu']."', ".time().")");
                                $tab1=array($this->getLoggedAdminId(), $_POST['skala'],$_POST['opinia'],$_POST['id_produktu'],date('Y-m-d'));
                                opinia::addOpinia($tab1);    
                                }
                                    else
                                    {#mysql_query("INSERT INTO opinia VALUES (NULL, '".$this->getLoggedClientId()."', '".$_POST['skala']."', '".$_POST['opinia']."', '".$_POST['id_produktu']."', ".time().")");
                                    $tab2=array($this->getLoggedClientId(), $_POST['skala'],$_POST['opinia'],$_POST['id_produktu'],date('Y-m-d'));
                                    opinia::addOpinia($tab2);    
                                    }
				$this->redirect("index.php?url=produkt", "error", "Opinia zostala dodana pomyslnie.");
			}
		}
	}
}
?>