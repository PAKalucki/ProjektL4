<?php
include_once("model/model.php"); 

class raport_Model extends Model 
{
	public $raport = false;

	public function __construct()
	{
		parent::__construct();
		
		if(!$this->isAdmin())
			$this->redirect("index.php?", "error", "Niestety nie posiadasz uprawnien administratora.");
		
		switch ($this->getAction()) {
			case 'faktury':
				$this->faktury();
				break;
		    default:
		    	$this->show();
		}
	}
	public function show()
	{
		
		include "/../view/raport.phtml";
	}
	
	public function faktury()
	{
		require('pdf/fpdf.php');
		$result=mysql_query("select * from zamowienie z, pozycja_zamowienia pz, produkt p, klient k, produkt_cena pc WHERE z.ID_zamowienia = pz.ZAMOWIENIE_ID_zamowienia AND p.ID_produktu = pz.PRODUKT_ID_produktu AND k.ID_klienta = z.KLIENT_ID_klienta AND p.ID_produktu = pc.PRODUKT_ID_Produktu ORDER BY z.ID_zamowienia ASC");
		$number_of_products = mysql_numrows($result);

		$tile = array();
		$rodzaj = array();
		$data_wystawienia = array();
		$nabywca = array();
		$adres = array();
		$telefon = array();
		$data_oplacenia = array();
		$data_wyslania = array();
		$rodzaj_przesylki = array();
		$nazwa_towary = array();
		$ilosc = array();
		$cena = array();

		while($row = mysql_fetch_array($result))
		{
			$tile[] = $row['ID_zamowienia'];
			$data_wystawienia[] = $row['data_wystawienia'];
			$nabywca[] = $row['imie'].' '.$row['nazwisko'];
			$adres[] = $row['kod_pocztowy'].' '.$row['miasto'].', ul.'.$row['ulica'].' '.$row['nr_domu'].'/'.$row['nr_lokalu'];
			$telefon[] = $row['telefon'];
			$data_oplacenia[] = $row['data_oplacenia'];
			$data_wyslania[] = $row['data_wyslania'];
			$nazwa_towary[] = $row['nazwa_produktu'];
			$ilosc[] = $row['ilosc_sztuk'];
			$cena[] = $row['cena_sprzedazy']*$row['ilosc_sztuk'];
			
			switch($row['rodzaj_przesylki'])
			{
				case "l":
					$rodzaj_przesylki[] = "List (zwykla paczka)";
				break;
				case "e":
					$rodzaj_przesylki[] = "List ekonomiczny";
				break;
				case "p":
					$rodzaj_przesylki[] = "Przesylka polecona";
				break;
				case "k":
					$rodzaj_przesylki[] = "Przesylka kurierem";
				break;
			}
			
			if($row['opcja_platnosci'] == 'f')
				$rodzaj[] = "Faktura nr. ";
			elseif($row['opcja_platnosci'] == 'p')
				$rodzaj[] = "Paragon nr. ";
			

		}
		
		$pracownik="";
		$query = mysql_query("SELECT * FROM pracownik WHERE ID_pracownika = ".$this->getLoggedAdminId()."");
		while($x = mysql_fetch_array($query))
		{
			$pracownik = $x['imie'].' '.$x['nazwisko'];
		}
		mysql_close();


		$pdf=new FPDF();
		
		$i = 0;
		for($i=0; $i<$number_of_products; $i++)
		{
			$pdf->AddPage();

			$pdf->SetFillColor(232,232,232);

			$pdf->SetFont('Arial','',9);
			$pdf->Cell(150);
			$pdf->Cell(0, 6, 'Miejscowosc: Rzeszów',0,0,'R',0);
			$pdf->Cell(0, 12, 'Data wystawienia: '.date('Y-m-d', $data_wystawienia[$i]),0,0,'R',0);
			$pdf->Ln(10);
			
			$pdf->SetFont('Arial','B',16);
			$pdf->Cell(80);
			$pdf->Cell(30,10,$rodzaj[$i].$tile[$i],0,0,'C');
			$pdf->Ln(20);
			
			$pdf->SetFont('Arial','',10);
			$pdf->SetX(15);
			$pdf->Cell(0, 6, 'Nabywca: '.$nabywca[$i],0,0,'L',0);
			$pdf->Cell(0, 6, 'Data oplacenia: '.date('Y-m-d', $data_oplacenia[$i]),0,0,'R',0);
			$pdf->Ln();
			$pdf->SetX(15);
			$pdf->Cell(0, 6, 'Adres: '.$adres[$i],0,0,'L',0);
			$pdf->Cell(0, 6, 'Data wyslania towaru: '.date('Y-m-d', $data_wyslania[$i]),0,0,'R',0);
			$pdf->Ln();
			$pdf->SetX(15);
			$pdf->Cell(0, 6, 'Telefon: '.$telefon[$i],0,0,'L',0);
			$pdf->Cell(0, 6, 'Rodzaj przesylki: '.$rodzaj_przesylki[$i],0,0,'R',0);
			$pdf->Ln(20);
			
			$pdf->SetFont('Arial','B',10);
			$pdf->SetX(45);
			$pdf->Cell(10,6,'Lp.',1,0,'C',1);
			$pdf->SetX(55);
			$pdf->Cell(60,6,'Nazwa towaru',1,0,'C',1);
			$pdf->SetX(115);
			$pdf->Cell(10,6,'Ilosc',1,0,'C',1);
			$pdf->SetX(125);
			$pdf->Cell(40,6,'Cena do zaplaty',1,0,'C',1);
			$pdf->Ln();

			$pdf->SetFont('Arial','',10);
			$pdf->SetX(45);
			$pdf->Cell(10,6,'1.',1,0,'C',0);
			$pdf->SetX(55);
			$pdf->Cell(60,6,$nazwa_towary[$i],1,0,'C',0);
			$pdf->SetX(115);
			$pdf->Cell(10,6,$ilosc[$i],1,0,'C',0);
			$pdf->SetX(125);
			$pdf->Cell(40,6,$cena[$i].'zl',1,0,'C',0);
			$pdf->Ln(50);
			
			$pdf->SetFont('Arial','',7);
			$pdf->SetY(-27);
			$pdf->SetX(15);
			$pdf->Cell(0, 6, 'Wygenerowane przez: '.$pracownik.', '.date('Y-m-d G:i:s', time()),0,0,'L',0);
			
		}

		$pdf->Output();
	}


}
?>