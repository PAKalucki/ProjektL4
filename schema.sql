-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 31 Maj 2015, 15:33
-- Wersja serwera: 5.5.24-log
-- Wersja PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `sklep_internetowy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cena`
--

CREATE TABLE IF NOT EXISTS `cena` (
  `ID_ceny` int(5) NOT NULL AUTO_INCREMENT,
  `cena_magazynowa` int(11) NOT NULL,
  `obowiazuje_od` date NOT NULL,
  `obowiazuje_do` date DEFAULT NULL,
  `PRACOWNIK_ID_pracownika` int(5) NOT NULL,
  PRIMARY KEY (`ID_ceny`),
  KEY `CENA_PRACOWNIK_FK` (`PRACOWNIK_ID_pracownika`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Zrzut danych tabeli `cena`
--

INSERT INTO `cena` (`ID_ceny`, `cena_magazynowa`, `obowiazuje_od`, `obowiazuje_do`, `PRACOWNIK_ID_pracownika`) VALUES
(34, 19, '0000-00-00', '2015-05-25', 1),
(35, 30, '0000-00-00', '2015-05-31', 1),
(36, 30, '0000-00-00', '2015-06-25', 1),
(37, 30, '0000-00-00', '2015-05-25', 1),
(38, 56, '0000-00-00', '2015-06-25', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klient`
--

CREATE TABLE IF NOT EXISTS `klient` (
  `ID_klienta` int(5) NOT NULL AUTO_INCREMENT,
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
  `data_modyfikacji` int(30) DEFAULT NULL,
  PRIMARY KEY (`ID_klienta`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Zrzut danych tabeli `klient`
--

INSERT INTO `klient` (`ID_klienta`, `imie`, `nazwisko`, `miasto`, `kod_pocztowy`, `ulica`, `nr_domu`, `nr_lokalu`, `email`, `login`, `haslo`, `telefon`, `data_modyfikacji`) VALUES
(2, 'Test', 'Nazwisko', 'Rzeszow', '38-100', 'Kurpiowska', 23, 53, 'test@test.pl', 'test', 'test123', 3442342, 1433070194),
(4, 'bazy', 'bazy', 'bazy', '38-100', 'Zaborów', 208, 20, 'meabazy@o2.pl', 'bazy', 'bazy123', 277858945, 1432903222);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `opinia`
--

CREATE TABLE IF NOT EXISTS `opinia` (
  `ID_opinii` int(5) NOT NULL AUTO_INCREMENT,
  `nick` varchar(30) NOT NULL,
  `skala` int(1) NOT NULL,
  `komentarz` varchar(255) NOT NULL,
  `PRODUKT_ID_produktu` int(5) NOT NULL,
  `data_opinii` int(32) NOT NULL,
  PRIMARY KEY (`ID_opinii`),
  KEY `OPINIA_PRODUKT_FK` (`PRODUKT_ID_produktu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Zrzut danych tabeli `opinia`
--

INSERT INTO `opinia` (`ID_opinii`, `nick`, `skala`, `komentarz`, `PRODUKT_ID_produktu`, `data_opinii`) VALUES
(1, '', 3, 'asd', 40, 0),
(2, '', 3, 'asd', 40, 0),
(3, '1', 5, 'asd', 40, 0),
(4, '2', 3, 'asd', 44, 0),
(5, '1', 1, 'asd', 40, 0),
(6, '2', 5, 'Dobra nuta!', 44, 1433086121);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pozycja_zamowienia`
--

CREATE TABLE IF NOT EXISTS `pozycja_zamowienia` (
  `ID_linii_zamowienia` int(5) NOT NULL AUTO_INCREMENT,
  `ZAMOWIENIE_ID_zamowienia` int(5) NOT NULL,
  `PRODUKT_ID_produktu` int(5) NOT NULL,
  `ilosc_sztuk` int(11) NOT NULL,
  PRIMARY KEY (`ID_linii_zamowienia`),
  KEY `POZYCJA_ZAMOWIENIA_PRODUKT_FK` (`PRODUKT_ID_produktu`),
  KEY `POZYCJA_ZAMOWIENIA_ZAMOWIENIE_FK` (`ZAMOWIENIE_ID_zamowienia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Zrzut danych tabeli `pozycja_zamowienia`
--

INSERT INTO `pozycja_zamowienia` (`ID_linii_zamowienia`, `ZAMOWIENIE_ID_zamowienia`, `PRODUKT_ID_produktu`, `ilosc_sztuk`) VALUES
(21, 23, 44, 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownik`
--

CREATE TABLE IF NOT EXISTS `pracownik` (
  `ID_pracownika` int(5) NOT NULL AUTO_INCREMENT,
  `imie` varchar(30) NOT NULL,
  `nazwisko` varchar(30) NOT NULL,
  `telefon` int(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `login` varchar(30) NOT NULL,
  `haslo` varchar(30) NOT NULL,
  PRIMARY KEY (`ID_pracownika`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `pracownik`
--

INSERT INTO `pracownik` (`ID_pracownika`, `imie`, `nazwisko`, `telefon`, `email`, `login`, `haslo`) VALUES
(1, 'Bartosz', 'Koszarski', 669004340, 'koszar93@gmail.com', 'koszar93', 'koszar1993');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkt`
--

CREATE TABLE IF NOT EXISTS `produkt` (
  `ID_produktu` int(5) NOT NULL AUTO_INCREMENT,
  `nazwa_produktu` varchar(40) NOT NULL,
  `rozmiar` int(11) NOT NULL,
  `opis_produktu` char(255) NOT NULL,
  `grupa_produktow` varchar(30) DEFAULT NULL,
  `grupa_wiekowa` varchar(30) DEFAULT NULL,
  `ilosc_produktow` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_produktu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Zrzut danych tabeli `produkt`
--

INSERT INTO `produkt` (`ID_produktu`, `nazwa_produktu`, `rozmiar`, `opis_produktu`, `grupa_produktow`, `grupa_wiekowa`, `ilosc_produktow`) VALUES
(40, 'Dlugopis', 0, 'Dlugopis', 'foto', 'brak', 20),
(41, 'Livestrong Opaska', 0, 'asd', 'test', 'brak', 20),
(42, 'Kubek', 0, 'asd', 'test', 'brak', 6),
(43, 'Czarne i czerwone', 0, 'Czarne i czerwone', 'foto', '+3', 20),
(44, 'Gang Albanii', 0, 'Gang Albanii', 'muzyka', '+18', 17);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkt_cena`
--

CREATE TABLE IF NOT EXISTS `produkt_cena` (
  `ID_obecnej_ceny` int(5) NOT NULL AUTO_INCREMENT,
  `cena_sprzedazy` int(11) DEFAULT NULL,
  `PRODUKT_ID_produktu` int(5) NOT NULL,
  `CENA_ID_ceny` int(5) NOT NULL,
  PRIMARY KEY (`ID_obecnej_ceny`),
  KEY `PRODUKT_CENA_CENA_FK` (`CENA_ID_ceny`),
  KEY `PRODUKT_CENA_PRODUKT_FK` (`PRODUKT_ID_produktu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Zrzut danych tabeli `produkt_cena`
--

INSERT INTO `produkt_cena` (`ID_obecnej_ceny`, `cena_sprzedazy`, `PRODUKT_ID_produktu`, `CENA_ID_ceny`) VALUES
(32, 29, 40, 34),
(33, 31, 41, 35),
(34, 31, 42, 36),
(35, 45, 43, 37),
(36, 99, 44, 38);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienie`
--

CREATE TABLE IF NOT EXISTS `zamowienie` (
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
  PRIMARY KEY (`ID_zamowienia`),
  KEY `ZAMOWIENIE_KLIENT_FK` (`KLIENT_ID_klienta`),
  KEY `ZAMOWIENIE_PRACOWNIK_FK` (`PRACOWNIK_ID_pracownika`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Zrzut danych tabeli `zamowienie`
--

INSERT INTO `zamowienie` (`ID_zamowienia`, `KLIENT_ID_klienta`, `status`, `data_wystawienia`, `data_oplacenia`, `data_wyslania`, `data_zwrotu`, `data_otrzymania_zwrotu`, `data_zwrotu_pieniedzy`, `rodzaj_przesylki`, `opcja_platnosci`, `PRACOWNIK_ID_pracownika`) VALUES
(23, 2, 'p', 1433086249, NULL, NULL, NULL, NULL, NULL, 'p', 'f', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia`
--

CREATE TABLE IF NOT EXISTS `zdjecia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zdjecie` varchar(32) NOT NULL,
  `PRODUKT_ID_produktu` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Zrzut danych tabeli `zdjecia`
--

INSERT INTO `zdjecia` (`id`, `zdjecie`, `PRODUKT_ID_produktu`) VALUES
(35, 'czarne.jpg', 40),
(36, 'czerwone.jpg', 40),
(37, 'gang.jpg', 44);

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `cena`
--
ALTER TABLE `cena`
  ADD CONSTRAINT `CENA_PRACOWNIK_FK` FOREIGN KEY (`PRACOWNIK_ID_pracownika`) REFERENCES `pracownik` (`ID_pracownika`);

--
-- Ograniczenia dla tabeli `opinia`
--
ALTER TABLE `opinia`
  ADD CONSTRAINT `OPINIA_PRODUKT_FK` FOREIGN KEY (`PRODUKT_ID_produktu`) REFERENCES `produkt` (`ID_produktu`);

--
-- Ograniczenia dla tabeli `pozycja_zamowienia`
--
ALTER TABLE `pozycja_zamowienia`
  ADD CONSTRAINT `POZYCJA_ZAMOWIENIA_PRODUKT_FK` FOREIGN KEY (`PRODUKT_ID_produktu`) REFERENCES `produkt` (`ID_produktu`),
  ADD CONSTRAINT `POZYCJA_ZAMOWIENIA_ZAMOWIENIE_FK` FOREIGN KEY (`ZAMOWIENIE_ID_zamowienia`) REFERENCES `zamowienie` (`ID_zamowienia`);

--
-- Ograniczenia dla tabeli `produkt_cena`
--
ALTER TABLE `produkt_cena`
  ADD CONSTRAINT `PRODUKT_CENA_CENA_FK` FOREIGN KEY (`CENA_ID_ceny`) REFERENCES `cena` (`ID_ceny`),
  ADD CONSTRAINT `PRODUKT_CENA_PRODUKT_FK` FOREIGN KEY (`PRODUKT_ID_produktu`) REFERENCES `produkt` (`ID_produktu`);

--
-- Ograniczenia dla tabeli `zamowienie`
--
ALTER TABLE `zamowienie`
  ADD CONSTRAINT `ZAMOWIENIE_KLIENT_FK` FOREIGN KEY (`KLIENT_ID_klienta`) REFERENCES `klient` (`ID_klienta`),
  ADD CONSTRAINT `ZAMOWIENIE_PRACOWNIK_FK` FOREIGN KEY (`PRACOWNIK_ID_pracownika`) REFERENCES `pracownik` (`ID_pracownika`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
