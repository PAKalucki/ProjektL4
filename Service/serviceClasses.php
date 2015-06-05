<?php
require_once 'idiorm/idiorm.php';
require_once 'paris/paris.php';

    ORM::configure('/schema.sql');
    ORM::configure('mysql:host=localhost; dbname=schema');
    ORM::configure('username', 'root');
    ORM::configure('password', '');
    #ORM::configure('logging', true);

        function array_default_key($array) { #zamiana asocjacyjnej tablicy na numeryczna
            $i = 0;
            foreach ($array as $key => $val) {
                $array[$i] = $val;
                $i++;
            }
            return $array;
            }    
   
class klient extends Model{
   public static $_table = 'klient';
   public static $_id_column = 'ID_klienta';
   
   
   public function zamowienie()
        {
            return $this->has_many('zamowienie', 'KLIENT_ID_klienta', 'ID_klienta');   
        }
    
    public function addKlient($tab){
        $user = Model::factory('klient')->create();
        $user->ID_klienta = NULL;
        $user->imie = $tab[0];
        $user->nazwisko= $tab[1];
        $user->miasto= $tab[2];
        $user->kod_pocztowy= $tab[3];
        $user->ulica= $tab[4];
        $user->nr_domu= $tab[5];
        $user->nr_lokalu= $tab[6];
        $user->email= $tab[7];
        $user->login= $tab[8];
        $user->haslo= $tab[9];
        $user->telefon= $tab[10];
        $user->save();
        return $user->id();
    }
    
    public function findKlient($id) #zwraca obiekt lub false
    {
        $q = Model::factory('klient')->find_one($id);
        return $q;
    }
    
    public function profil($id)
    {#SELECT `imie`, `nazwisko` FROM `klient` WHERE `ID_klienta`='".$this->getLoggedClientId()."' LIMIT 1"
        #$q = ORM::for_table('klient')->select('imie')->select('nazwisko')->find_one($id);
        $q = Model::factory('klient')->select('imie')->select('nazwisko')->find_one($id);
        return $q;  
    }
    
    public function findKlientWhereLogin($id) #zwraca obiekt lub false
    {
        $q = Model::factory('klient')->where('login',$id)->find_one();
        if(!$q)
            return false;
        $data = $q->as_array();
        return $data;
    }
    
    public function klientKoszyk($id2)
    {
    #SELECT * FROM klient k, produkt pr, produkt_cena pc 
    #WHERE k.ID_klienta = ".$this->getLoggedClientId()." AND pr.ID_produktu = ".$_SESSION['koszyk'][$i]." 
    #AND pc.PRODUKT_ID_produktu = pr.ID_produktu
        $q = ORM::for_table('produkt')
                ->inner_join('produkt_cena',array('produkt_cena.PRODUKT_ID_produktu','=','produkt.ID_produktu'))
                ->where('produkt.ID_produktu',$id2)
                ->find_one();
        if(!$q)
            return false;
        return $q;
        
    }
    
    public function allKlient() #zwraca numeryczna tablice asocjacyjnych tablic odpowiadajacych rekorda z tabeli
    {
        $q = Model::factory('klient')->find_many();
        if(!$q)
            return false;
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        if(!$tempArray)
            return false;
        return $tempArray;
    }
    
    public function deleteKlient($id){
        $user = Model::factory('klient')->find_one($id);
        $user->delete();  
    }
    
    /*`ID_klienta` int(5) NOT NULL AUTO_INCREMENT,
  `imie` varchar(30) NOT NULL,
  `nazwisko` varchar(30) NOT NULL,
  `miasto` varchar(30) NOT NULL,
  `kod_pocztowy` varchar(10) NOT NULL,
  `ulica` varchar(30) NOT NULL,
  `nr_domu` int(4) NOT NULL,
  `nr_lokalu` int(4) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `login` varchar(30) NOT NULL,
  `haslo` varchar(30) NOT NULL,
  `telefon` int(15) DEFAULT NULL,
  `data_modyfikacji` int(30) DEFAULT NULL,*/
    
    public function updateKlient($id,$var){
        $user=Model::factory('klient')->find_one($id); 
        $user->imie= $var[0];
        $user->nazwisko= $var[1];
        $user->miasto= $var[2];
        $user->kod_pocztowy=$var[3];
        $user->ulica=$var[4];
        $user->nr_domu=$var[5];
        $user->nr_lokalu= $var[6];
        $user->email= $var[7];
        $user->login=$var[8];
        $user->haslo =$var[9];
        $user->telefon=$var[10];
        $user->data_modyfikacji= $var[11];
        $user->save();
    }    
        
}



class produkt extends Model{
   public static $_table = 'produkt';
   public static $_id_column = 'ID_produktu';
   
   public function opinia(){
       
       return $this->has_many('opinia','PRODUKT_ID_produktu','ID_produktu');
   }

   public function prod_cena(){
       
       return $this->has_many('prod_cena','PRODUKT_ID_produktu','ID_produktu');
   }
   
   public function zdjecia(){
       
       return $this->has_many('zdjecia','id','ID_produktu');
   }  

   public function poz_zamowienia(){
       
       return $this->has_many('poz_zamowienia','PRODUKT_ID_produktu','ID_produktu');
   }
   
   public function addProdukt($tab){
        $user = Model::factory('produkt')->create();
        
        $user->ID_produktu = NULL;
        $user->nazwa_produktu = $tab[0];
        $user->rozmiar= $tab[1];
        $user->opis_produktu= $tab[2];
        $user->grupa_produktow= $tab[3];
        $user->grupa_wiekowa= $tab[4];
        $user->ilosc_produktow = $tab[5]; 
        $user->save();
        return $user->id();
    }
    
    public function updateProdukt($id,$tab)
    {
        $user=Model::factory('produkt')->find_one($id);
        $user->nazwa_produktu = $tab[0];
        $user->opis_produktu= $tab[1];
        $user->grupa_produktow= $tab[2];
        $user->grupa_wiekowa= $tab[3];
        $user->ilosc_produktow = $tab[4]; 
        $user->save();
    }
    
    public function updateProduktNumber($id,$value)
    {
        $user=Model::factory('produkt')->find_one($id);    
        $ilosc = $user->ilosc_produktow;
        $ilosc=$ilosc-$value;
        $user->ilosc_produktow =$ilosc;
        $user->save();
    }
    
    public function findProdukt($id)
    {
        $q = Model::factory('produkt')->find_one($id);
        return $q;
    }
    
    public function findKoszyk($id)
    {#SELECT * FROM produkt p, produkt_cena pc WHERE p.ID_produktu = ".$_SESSION['koszyk'][$i]." AND pc.PRODUKT_ID_produktu = p.ID_produktu
        $q = ORM::for_table('produkt')->inner_join('produkt_cena', array('produkt_cena.PRODUKT_ID_produktu', '=', 'produkt.ID_produktu'))
                                      ->where('produkt.ID_produktu', $id)
                                      ->find_one();
        if(!$q)
            return false;
        return $q;
    }
    
    public function allProdukt()
    {
        $q = Model::factory('produkt')->find_many();#zmienic tak zeby otrzymywac tablice tablic
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray;
    }
    
    public function grupaProdukt1()
    {
        #$query = ORM::for_table('produkt')->raw_query('SELECT grupa_produktow FROM produkt GROUP BY grupa_produktow ORDER BY grupa_produktow ASC');
        $query = ORM::for_table('produkt')->select('grupa_produktow')->group_by('grupa_produktow')->find_many();
        if(!$query)
            return false;
        return $query;
    }
    
        public function grupaProdukt2()
    {
        #$query = ORM::for_table('produkt')->raw_query('SELECT grupa_wiekowa FROM produkt GROUP BY grupa_wiekowa ORDER BY grupa_wiekowa ASC');
        $query = ORM::for_table('produkt')->select('grupa_wiekowa')->group_by('grupa_wiekowa')->find_many();
        if(!$query)
            return false;
        return $query;
    }
    
    public function sortProdukt($var)
    {
        #$query = ORM::for_table('produkt')->raw_query('SELECT * FROM produkt WHERE grupa_produktow = :grupa_prod ORDER BY grupa_produktow ASC', array('grupa_prod'=> $var));
        $query = ORM::for_table('produkt')->where('grupa_produktow',$var)->order_by_asc('grupa_produktow')->find_many();
        if(!$query)
            return false;
        return $query;
        
    }
    
    public function sortWiek($var)
    {
        #$query = ORM::for_table('produkt')->raw_query('SELECT * FROM produkt WHERE grupa_wiekowa = :grupa_wiek ORDER BY grupa_wiekowa ASC', array('grupa_wiek'=> $var));
        $query = ORM::for_table('produkt')->where('grupa_wiekowa',$var)->order_by_asc('grupa_wiekowa')->find_many();
        if(!$query)
            return false;
        return $query;
        
    }
    public function sortWiekProdukt($var1,$var2)
    {
        #$query = ORM::for_table('produkt')->raw_query('SELECT * FROM produkt WHERE grupa_produktow = :grupa_prod and grupa_wiekowa = :grupa_wiek ORDER BY grupa_produktow, grupa_wiekowa ASC',array('grupa_prod'=>$var1,'grupa_wiek'=>$var2));
        $query = ORM::for_table('produkt')->where('grupa_wiekowa',$var2)->where('grupa_produktow',$var1)->order_by_asc('grupa_wiekowa')->order_by_asc('grupa_produktow')->find_many();
        if(!$query)
            return false;
        return $query;
        
    }
    
    public function deleteProdukt($id){
        $user = Model::factory('produkt')->find_one($id);
        $user->delete();  
    }
    

       
}

class cena extends Model{
   public static $_table = 'cena';
   public static $_id_column = 'ID_ceny';
   
   
      public function prod_cena()
        {
            return $this->has_many('prod_cena', 'CENA_ID_ceny', 'ID_ceny');   
        }
   
	public function pracownik()
	{
		return $this->belongs_to('pracownik', 'ID_pracownika', 'PRACOWNIK_ID_pracownika');
	}
        
        public function addCena($tab){
            $user = Model::factory('cena')->create();
            $user->ID_ceny = NULL;
            $user->cena_magazynowa = $tab[0];
            $user->obowiazuje_od= $tab[1];
            $user->obowiazuje_do= $tab[2];
            $user->PRACOWNIK_ID_pracownika= $tab[3];
            $user->save();
            return $user->id();
    }
    
    public function findCena($id)
    {
        $q = Model::factory('cena')->find_one($id);
        return $q;
    }
    
    public function updateCena($id,$tab)
    {
        $user = Model::factory('cena')->find_one($id);
        $user->cena_magazynowa = $tab[0];
        $user->obowiazuje_do= $tab[1];
        $user->save();
    }
    
    public function deleteCena($id){
        $user = Model::factory('cena')->find_one($id);
        $user->delete();  
    }
}

class opinia extends Model{
   public static $_table = 'opinia';
   public static $_id_column = 'ID_opinii';
   private $user;
   
   
      public function produkt()
	{
		return $this->belongs_to('produkt', 'ID_produktu', 'PRODUKT_ID_produktu');
	}           
   
        public function addOpinia($tab){
            $user = Model::factory('opinia')->create();
            $user->ID_opinii = NULL;
            $user->nick = $tab[0];
            $user->skala= $tab[1];
            $user->komentarz= $tab[2];
            $user->PRODUKT_ID_produktu= $tab[3];
            $user->data_opinii= $tab[4];
            $user->save();
            return $user->id();
    }
    
    public function findOpinia($id)
    {
        $q = Model::factory('opinia')->find_one($id);
        return $q;
    }
    
        public function findOpiniaWhere($id)
    {
        $q = Model::factory('opinia')->where('PRODUKT_ID_produktu',$id)->find_many();
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        if(!$tempArray)
            return false;
        return $tempArray;
    }
    
    public function findOpiniaKlient($id)
    {
        #SELECT * FROM opinia o, klient k, produkt p 
        #WHERE o.PRODUKT_ID_produktu = ".$_GET['id']." AND o.nick = k.ID_klienta AND p.ID_produktu = o.PRODUKT_ID_produktu
        $q = ORM::for_table('opinia')
                                     ->inner_join('produkt',array('produkt.ID_produktu','=','opinia.PRODUKT_ID_produktu'))
                                     ->left_outer_join('klient',array('klient.ID_klienta','=','opinia.nick'))   
                                     ->where('opinia.PRODUKT_ID_produktu',$id)
                                     ->find_many();
        if(!$q)
            return false;
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        if(!$tempArray)
            return false;
        return $tempArray;
    }       
    
    public function deleteOpinia($id){
        $user = Model::factory('opinia')->find_one($id);
        $user->delete();  
    }
    
    public function deleteAllOpiniaWhere($id){
       $user = ORM::for_table('opinia')->where_equal('PRODUKT_ID_produktu', $id)->delete_many(); 
    }
}

class poz_zamowienia extends Model{
   public static $_table = 'pozycja_zamowienia';
   public static $_id_column = 'ID_linii_zamowienia';
   
      public function produkt()
	{
		return $this->belongs_to('produkt', 'ID_produktu', 'PRODUKT_ID_produktu');
	}
        
      public function zamowienie()
	{
		return $this->belongs_to('zamowienie', 'ID_zamowienia', 'ZAMOWIENIE_ID_zamowienia');
	}
        

        
      public function addPozycja($tab){
            $user = Model::factory('poz_zamowienia')->create();
            $user->ID_linii_zamowienia = NULL;
            $user->ZAMOWIENIE_ID_zamowienia = $tab[0];
            $user->PRODUKT_ID_produktu= $tab[1];
            $user->ilosc_sztuk= $tab[2];
            $user->save();
            return $user->id();
    }
    
    public function findPozycja($id)
    {
        $q = Model::factory('poz_zamowienia')->find_one($id);
        return $q;
    }
    
    public function deletePozycja($id){
        $user = Model::factory('poz_zamowienia')->find_one($id);
        $user->delete();  
    }
}

class pracownik extends Model{
   public static $_table = 'pracownik';
   public static $_id_column = 'ID_pracownika';
   private $user;
   
      public function zamowienie()
        {
            return $this->has_many('zamowienie', 'PRACOWNIK_ID_pracownika', 'ID_pracownika');   
        }
        
      public function cena()
        {
            return $this->has_many('cena', 'PRACOWNIK_ID_pracownika', 'ID_pracownika');   
        }
        
      public function addPracownik($tab){
        $user = Model::factory('pracownik')->create();
        $user->ID_pracownika = NULL;
        $user->imie = $tab[0];
        $user->nazwisko= $tab[1];
        $user->telefon= $tab[2];
        $user->email= $tab[3];
        $user->login= $tab[4];
        $user->haslo= $tab[5];
        $user->save();
        return $user->id();
      }
    
    public function profil($id)
    {
        #$q=ORM::for_table('pracownik')->select('imie')->select('nazwisko')->find_one($id);
        $q = Model::factory('pracownik')->select('imie')->select('nazwisko')->find_one($id);
        return $q;  
    }
      
    public function findPracownik($id)
    {
        $q = Model::factory('pracownik')->find_one($id);
        return $q;
    }
    
    public function pracownikKoszyk($id1,$id2)
    {
        #SELECT * FROM pracownik p, produkt pr, produkt_cena pc 
        #WHERE p.ID_pracownika = ".$this->getLoggedAdminId()." AND pr.ID_produktu = ".$_SESSION['koszyk'][$i]." 
        #AND pc.PRODUKT_ID_produktu = pr.ID_produktu
        $q=ORM::for_table('pracownik')->inner_join('produkt_cena',array('produkt_cena.PRODUKT_ID_produktu','=','produkt.ID_produktu'))
                                       ->where('pracownik.ID_pracownika',$id1)
                                       ->where('produkt.ID_produktu',$id2)
                                       ->fine_one();
        if(!$q)
            return false;
        return $q;
    }
    
    
    public function findPracownikWhereLogin($id) #zwraca obiekt lub false
    {
        $q = Model::factory('pracownik')->where('login',$id)->find_one();
        if(!$q)
            return false;
        $data=$q->as_array();
        return $data;
    }
    
    public function deletePracownik($id){
        $user = Model::factory('pracownik')->find_one($id);
        $user->delete();  
    }
}

class prod_cena extends Model{
   public static $_table = 'produkt_cena';
   public static $_id_column = 'ID_obecnej_ceny';
   
      public function produkt()
	{
		return $this->belongs_to('produkt', 'ID_produktu', 'PRODUKT_ID_produktu');
	}
        
      public function cena()
	{
		return $this->belongs_to('cena', 'ID_ceny', 'CENA_ID_ceny');
	}    
   
        
        public function addProdCena($tab){
            $user = Model::factory('prod_cena')->create();
            $user->ID_obecnej_ceny = NULL;
            $user->cena_sprzedazy = $tab[0];
            $user->PRODUKT_ID_produktu= $tab[1];
            $user->CENA_ID_ceny= $tab[2];
            $user->save();
        }
    
    public function updateProdCena1($id,$tab)
    {
        $user=Model::factory('prod_cena')->where('CENA_ID_ceny',$id)->find_one();
        $user->cena_sprzedazy = $tab;
        $user->save();
    }
    
    public function findProdCena($id)
    {
        $q = Model::factory('prod_cena')->find_one($id);
        return $q;
    }
    
    public function deleteProdCena($id){
        $user = Model::factory('prod_cena')->find_one($id);
        $user->delete(); 
    }
    
    public function deleteProdCenaWhere($id){ #nie wiem czy nie powinno byc find_many, jedna cena moze wiec wiele produkt_cen ??
        $user = Model::factory('prod_cena')->where('CENA_ID_ceny',$id)->find_one();
        $user->delete(); 
    }
}
class zamowienie extends Model{
   public static $_table = 'zamowienie';
   public static $_id_column = 'ID_zamowienia';

           /* struktura tej tabeli
`ID_zamowienia` int(5) NOT NULL AUTO_INCREMENT,
  `KLIENT_ID_klienta` int(5) NOT NULL,
  `status` char(1) NOT NULL,
  `data_wystawienia` int(30) NOT NULL,
  `data_oplacenia` int(30) DEFAULT NULL,
  `data_wyslania` int(30) DEFAULT NULL,
  `data_zwrotu` int(30) DEFAULT NULL,
  `data_otrzymania_zwrotu` int(30) DEFAULT NULL,
  `data_zwrotu_pieniedzy` int(30) DEFAULT NULL,
  `rodzaj_przesylki` char(1) NOT NULL,
  `opcja_platnosci` char(1) NOT NULL,
  `PRACOWNIK_ID_pracownika` int(5) DEFAULT NULL,
         *          */
   
   
      public function pozycja()
        {
            return $this->has_many('poz_zamowienia', 'ZAMOWIENIE_ID_zamowienia', 'ID_zamowienia');   
        }   
        
      public function pracownik()
	{
		return $this->belongs_to('pracownikt', 'ID_pracownika', 'PRACOWNIKA_ID_pracownika');
	}
        
      public function klient()
	{
		return $this->belongs_to('klient', 'ID_klienta', 'KLIENT_ID_klienta');
	}
     
        
        public function deleteZamownieKlienta($id)
        {
            $person = ORM::for_table('zamowienie')
            ->where_equal('KLIENT_ID_klienta', $id)
            ->delete_many();
            
        }
        
        public function addZamowienie($tab){
        $user = Model::factory('zamowienie')->create();
        $user->ID_zamowienia = NULL;
        $user->KLIENT_ID_klienta = $tab[0];
        $user->status = $tab[1];
        $user->data_wystawienia= $tab[2];
        $user->data_oplacenia = $tab[3];
        $user->data_wyslania= $tab[4];
        $user->data_zwrotu= $tab[5];
        $user->data_otrzymania_zwrotu= $tab[6];
        $user->data_zwrotu_pieniedzy = $tab[7];
        $user->rodzaj_przesylki= $tab[8];
        $user->opcja_platnosci= $tab[9];
        $user->save();
        return $user->id();
    }
    
    public function findZamowienieIDKlienta($id)
    {
        $q = Model::factory('zamowienie')->where('KLIENT_ID_klienta',$id)->find_many();
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray;
    }
    
    public function findZamowieniaProduktu()
    {#SELECT * FROM zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc, klient k 
    #WHERE k.ID_klienta = z.KLIENT_ID_klienta 
    #AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia 
    #AND pz.PRODUKT_ID_produktu = p.ID_produktu 
    #AND p.ID_produktu = pc.PRODUKT_ID_produktu");
        $q = ORM::for_table('zamowienie')->inner_join('klient',array('klient.ID_klienta','=','zamowienie.KLIENT_ID_klienta'))
                                          ->inner_join('pozycja_zamowienia',array('pozycja_zamowienia.ZAMOWIENIE_ID_zamowienia','=','zamowienie.ID_zamowienia'))
                                          ->inner_join('produkt',array('produkt.ID_produktu','=','pozycja_zamowienia.PRODUKT_ID_produktu'))
                                          ->inner_join('produkt_cena',array('produkt_cena.PRODUKT_ID_produktu','=','produkt.ID_produktu'))
                                          ->find_many();
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray;
    }
    
    public function findZamowieniaProduktu2($id)
    {#SELECT * FROM zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc 
    #WHERE z.KLIENT_ID_klienta = ".$this->getLoggedClientId()." 
    #AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia 
    #AND pz.PRODUKT_ID_produktu = p.ID_produktu 
    #AND p.ID_produktu = pc.PRODUKT_ID_produktu
       $q = ORM::for_table('zamowienie')
                                          ->inner_join('pozycja_zamowienia',array('pozycja_zamowienia.ZAMOWIENIE_ID_zamowienia','=','zamowienie.ID_zamowienia'))
                                          ->inner_join('produkt',array('produkt.ID_produktu','=','pozycja_zamowienia.PRODUKT_ID_produktu'))
                                          ->inner_join('produkt_cena',array('produkt_cena.PRODUKT_ID_produktu','=','produkt.ID_produktu'))
                                          ->where('zamowienie.KLIENT_ID_klienta',$id)
                                          ->find_many();
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray; 
        
    }
    
    public function findZamowienieProduktu3($id1,$id2)
    {#SELECT * FROM klient k, zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc 
    #WHERE k.ID_klienta = ".$this->getLoggedClientId()." 
    #AND k.ID_klienta = z.KLIENT_ID_klienta 
    #AND z.ID_zamowienia = ".$_GET['id']." 
    #AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia 
    #AND pz.PRODUKT_ID_produktu = p.ID_produktu 
    #AND p.ID_produktu = pc.PRODUKT_ID_produktu
        $q = ORM::for_table('zamowienie')->inner_join('klient',array('klient.ID_klienta','=','zamowienie.KLIENT_ID_klienta'))
                                          ->inner_join('pozycja_zamowienia',array('pozycja_zamowienia.ZAMOWIENIE_ID_zamowienia','=','zamowienie.ID_zamowienia'))
                                          ->inner_join('produkt',array('produkt.ID_produktu','=','pozycja_zamowienia.PRODUKT_ID_produktu'))
                                          ->inner_join('produkt_cena',array('produkt_cena.PRODUKT_ID_produktu','=','produkt.ID_produktu'))
                                          ->where('klient.ID_klienta',$id1)
                                          ->where('zamowienie.ID_zamowienia',$id2)
                                          ->find_one();
        return $q; 
        
    }
    
    public function findZamowienie($id) #zwraca obiekt lub false
    {
        $q = Model::factory('zamowienie')->find_one($id);
        return $q;
    }
    
    public function allZamowienie() #zwraca numeryczna tablice asocjacyjnych tablic odpowiadajacych rekorda z tabeli
    {
        $q = Model::factory('zamowienie')->find_many();
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray;
    }
    
        public function allZamowienieWhere($status,$data_wyst) 
            {
                $q = Model::factory('zamowienie')->where_lt('data_wystawienia',$data_wyst)->where('status',$status)->find_many();
                if(!$q)
                    return false;
                $tempArray = array();
                $i=0;        
                foreach($q as $temp)
                {
                    $tempArray[$i]=$temp->as_array();
                    $i++;
                }
               
                
                return $tempArray;
                
              
            }
            
        public function allZamowienieWhere2($array) 
            {
               $q = ORM::for_table('zamowienie')->raw_query('SELECT * FROM zamowienie z, klient k WHERE z.data_wystawienia < :checkedDate AND z.status = :status AND z.KLIENT_ID_klienta = k.ID_klienta', array('checkeddate' => $array[0], 'status' => $array[1]));
               if(!$q)
                   return false;
               
               return $q;

            }
    
    public function deleteZamowienie($id){
        $user = Model::factory('zamowienie')->find_one($id);
        $user->delete();  
    } 
    
    public function updateZamowienie2($id,$tab)
    {
        
        $user=Model::factory('zamowienie')->find_one($id);    
        $user->data_oplacenia =$tab[0];
        $user->status=$tab[1];
        $user->save();
    }
    
        public function updateZamowienie3($id,$tab)
    {
            
        $user=Model::factory('zamowienie')->find_one($id);    
        $user->data_otrzymania_zwrotu =$tab[0];
        $user->status=$tab[1];
        $user->save();    
        
    }
    
         public function updateZamowienie4($id,$tab)
    {
        $user=Model::factory('zamowienie')->find_one($id);    
        $user->data_wyslania =$tab[0];
        $user->status=$tab[1];
        $user->save();      
        
    }
    
          public function updateZamowienie5($id,$tab)
    {
        $user=Model::factory('zamowienie')->find_one($id);    
        $user->data_zwrotu =$tab[0];
        $user->status=$tab[1];
        $user->save();
        
    }
    
    public function findZamowienieOczekiwanie()
    {#SELECT * FROM klient k, zamowienie z, pozycja_zamowienia pz, produkt p, produkt_cena pc 
    #WHERE z.KLIENT_ID_klienta = k.ID_klienta 
    #AND z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia 
    #AND pz.PRODUKT_ID_produktu = p.ID_produktu 
    #AND p.ID_produktu = pc.PRODUKT_ID_produktu 
    #AND z.status = 'o'
        $q = ORM::for_table('zamowienie')->inner_join('klient',array('klient.ID_klienta','=','zamowienie.KLIENT_ID_klienta'))
                                          ->inner_join('pozycja_zamowienia',array('pozycja_zamowienia.ZAMOWIENIE_ID_zamowienia','=','zamowienie.ID_zamowienia'))
                                          ->inner_join('produkt',array('produkt.ID_produktu','=','pozycja_zamowienia.PRODUKT_ID_produktu'))
                                          ->inner_join('produkt_cena',array('produkt_cena.PRODUKT_ID_produktu','=','produkt.ID_produktu'))
                                          ->where('zamowienie.status','o')
                                          ->find_many();
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray;
        
    }
    
    public function findZamowienieZaplacone()
    {
        $q = ORM::for_table('zamowienie')->inner_join('klient',array('klient.ID_klienta','=','zamowienie.KLIENT_ID_klienta'))
                                          ->inner_join('pozycja_zamowienia',array('pozycja_zamowienia.ZAMOWIENIE_ID_zamowienia','=','zamowienie.ID_zamowienia'))
                                          ->inner_join('produkt',array('produkt.ID_produktu','=','pozycja_zamowienia.PRODUKT_ID_produktu'))
                                          ->inner_join('produkt_cena',array('produkt_cena.PRODUKT_ID_produktu','=','produkt.ID_produktu'))
                                          ->where('zamowienie.status','z')
                                          ->find_many();
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray;
        
    }
    
    public function findZamowienieRaport()
    {#select * from zamowienie z, pozycja_zamowienia pz, produkt p, klient k, produkt_cena pc 
    #WHERE z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia 
    #AND p.ID_produktu = pz.PRODUKT_ID_produktu 
    #AND k.ID_klienta = z.KLIENT_ID_klienta 
    #AND p.ID_produktu = pc.PRODUKT_ID_Produktu 
    #ORDER BY z.ID_zamowienia ASC
        $q = ORM::for_table('zamowienie')->inner_join('klient',array('klient.ID_klienta','=','zamowienie.KLIENT_ID_klienta'))
                                          ->inner_join('pozycja_zamowienia',array('pozycja_zamowienia.ZAMOWIENIE_ID_zamowienia','=','zamowienie.ID_zamowienia'))
                                          ->inner_join('produkt',array('produkt.ID_produktu','=','pozycja_zamowienia.PRODUKT_ID_produktu'))
                                          ->inner_join('produkt_cena',array('produkt_cena.PRODUKT_ID_produktu','=','produkt.ID_produktu'))
                                          ->order_by_asc('zamowienie.ID_zamowienia')
                                          ->find_many();
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray;
    }
}


class zdjecia extends Model{
    public static $_table = 'zdjecia';
    public static $_id_column = 'id';
    
          public function produkt()
	{
		return $this->belongs_to('produkt', 'ID_produktu', 'PRODUKT_ID_produktu');
	}
        
        
        
        public function addZdjecia($tab){
            $user = Model::factory('zdjecia')->create();
            $user->id = NULL;
            $user->zdjecie = $tab[0];
            $user->PRODUKT_ID_produktu= $tab[1];
            $user->save();
            
    }
    
    public function findZdjecia($id)
    {
        $q = Model::factory('zdjecia')->find_one($id);
        return $q;
    }
   
    public function allZdjeciaWhere($id)
    {
        $q = Model::factory('zdjecia')->where('PRODUKT_ID_produktu',$id)->find_one();
        return $q;
    }
    
    public function deleteZdjecia($id){
        $user = Model::factory('zdjecia')->find_one($id);
        $user->delete();  
    }
    
        public function deleteZdjeciaWhere($id){
        
        $user = Model::factory('zdjecia')->where('PRODUKT_ID_produktu',$id)->find_one();
        $user->delete();
     
    }
    
    public function findZdjeciaWhere($id)
    {
        #SELECT * FROM zdjecia WHERE PRODUKT_ID_produktu = ".$_GET['id']."
    $q = Model::factory('zdjecia')->where('PRODUKT_ID_produktu',$id)->find_many($id);
    $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        if(!$tempArray)
            return false;
        return $tempArray;        
    }
}

class myqueriesORM
{
    public function pokazORM($id)
    {

        $q = ORM::for_table('produkt')
                                           ->inner_join('produkt_cena', array('produkt_cena.PRODUKT_ID_produktu', '=', 'produkt.ID_produktu'))
                                           ->inner_join('cena', array('cena.ID_ceny', '=', 'produkt_cena.CENA_ID_ceny'))
                                           ->left_outer_join('zdjecia', array('zdjecia.PRODUKT_ID_produktu', '=', 'produkt.ID_produktu'))
                                           ->where('produkt.ID_produktu', $id)
                                           ->find_one();
        return $q;  
    }
    
    public function pokazORM2($id)
    {

        $q = ORM::for_table('produkt')
                                           ->inner_join('produkt_cena', array('produkt_cena.PRODUKT_ID_produktu', '=', 'produkt.ID_produktu'))
                                           ->inner_join('cena', array('cena.ID_ceny', '=', 'produkt_cena.CENA_ID_ceny'))
                                           ->where('produkt.ID_produktu', $id)
                                           ->find_one();
        return $q;  
    }
    
    public function queryMain()
    {
        $q = ORM::for_table('pozycja_zamowienia')->raw_query('SELECT COUNT(pz.PRODUKT_ID_produktu) as top, p.nazwa_produktu, p.ID_produktu FROM pozycja_zamowienia pz, produkt p WHERE p.ID_produktu = pz.PRODUKT_ID_produktu GROUP BY pz.PRODUKT_ID_produktu ORDER BY top DESC LIMIT 3')->find_many();
        if(!$q)
            return false;
        $tempArray = array();
        $i=0;        
        foreach($q as $temp)
        {
            $tempArray[$i]=$temp->as_array();
            $i++;
        }
        return $tempArray;
    }
}