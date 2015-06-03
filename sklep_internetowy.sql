-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 03 Cze 2015, 00:20
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
  `obowiazuje_od` int(30) NOT NULL,
  `obowiazuje_do` date DEFAULT NULL,
  `PRACOWNIK_ID_pracownika` int(5) NOT NULL,
  PRIMARY KEY (`ID_ceny`),
  KEY `CENA_PRACOWNIK_FK` (`PRACOWNIK_ID_pracownika`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `cena`
--

INSERT INTO `cena` (`ID_ceny`, `cena_magazynowa`, `obowiazuje_od`, `obowiazuje_do`, `PRACOWNIK_ID_pracownika`) VALUES
(1, 19, 1433173646, NULL, 1),
(2, 19, 1433173780, NULL, 1),
(3, 30, 1433174030, '2015-06-03', 1),
(4, 49, 1433290438, '2015-06-04', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `klient`
--

INSERT INTO `klient` (`ID_klienta`, `imie`, `nazwisko`, `miasto`, `kod_pocztowy`, `ulica`, `nr_domu`, `nr_lokalu`, `email`, `login`, `haslo`, `telefon`, `data_modyfikacji`) VALUES
(2, 'Test', 'Nazwisko', 'Rzeszow', '38-100', 'Kurpiowska', 23, 53, 'test@test.pl', 'test', 'test123', 3442342, 1433070194),
(4, 'bazy', 'bazy', 'bazy', '38-100', 'Zaborów', 208, 20, 'asd@ow.pl', 'bazy', 'bazy123', 277858945, 1433286409),
(7, 'Mear', 'Elin', 'Rzeszów', '23-233', 'D?browskiego', 25, 3, 'koszar93@gmail.com', 'mearelin', 'mearelin', 556554552, 1433175904);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `opinia`
--

INSERT INTO `opinia` (`ID_opinii`, `nick`, `skala`, `komentarz`, `PRODUKT_ID_produktu`, `data_opinii`) VALUES
(1, '2', 5, 'Super produkt!', 3, 1433284972);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `pozycja_zamowienia`
--

INSERT INTO `pozycja_zamowienia` (`ID_linii_zamowienia`, `ZAMOWIENIE_ID_zamowienia`, `PRODUKT_ID_produktu`, `ilosc_sztuk`) VALUES
(3, 28, 2, 2),
(4, 29, 2, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `pracownik`
--

INSERT INTO `pracownik` (`ID_pracownika`, `imie`, `nazwisko`, `telefon`, `email`, `login`, `haslo`) VALUES
(1, 'Bartosz', 'Koszarski', 669004340, 'koszar93@gmail.com', 'koszar93', 'koszar1993'),
(2, 'Admi', 'Nistrator', 667004320, 'koszar93@gmail.com', 'admin', 'admin');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `produkt`
--

INSERT INTO `produkt` (`ID_produktu`, `nazwa_produktu`, `rozmiar`, `opis_produktu`, `grupa_produktow`, `grupa_wiekowa`, `ilosc_produktow`) VALUES
(1, 'Czarne i czerwone', 0, 'Czarne i czerwone', 'foto', 'brak', 17),
(2, 'Czerwone', 0, 'Czerwone', 'foto', 'brak', 0),
(3, 'Zielone i czarne', 0, 'Zielone i czarne', 'kolor', '+3', 10),
(4, 'Plecak', 0, 'Plecak na plecy', 'foto', 'brak', 20);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `produkt_cena`
--

INSERT INTO `produkt_cena` (`ID_obecnej_ceny`, `cena_sprzedazy`, `PRODUKT_ID_produktu`, `CENA_ID_ceny`) VALUES
(1, 24, 1, 1),
(2, 45, 2, 2),
(3, 45, 3, 3),
(4, 59, 4, 4);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Zrzut danych tabeli `zamowienie`
--

INSERT INTO `zamowienie` (`ID_zamowienia`, `KLIENT_ID_klienta`, `status`, `data_wystawienia`, `data_oplacenia`, `data_wyslania`, `data_zwrotu`, `data_otrzymania_zwrotu`, `data_zwrotu_pieniedzy`, `rodzaj_przesylki`, `opcja_platnosci`, `PRACOWNIK_ID_pracownika`) VALUES
(28, 7, 's', 1433285842, 1433285922, 1433288254, NULL, NULL, NULL, 'p', 'p', NULL),
(29, 2, 'k', 1433287809, 1433288222, 1433288253, 1433288275, 1433288307, NULL, 'l', 'p', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia`
--

CREATE TABLE IF NOT EXISTS `zdjecia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zdjecie` varchar(32) NOT NULL,
  `PRODUKT_ID_produktu` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `zdjecia`
--

INSERT INTO `zdjecia` (`id`, `zdjecie`, `PRODUKT_ID_produktu`) VALUES
(1, 'czarne.jpg', 1),
(2, 'czerwone.jpg', 1),
(3, 'red.jpg', 2),
(4, 'black.jpg', 3),
(5, 'green.jpg', 3),
(6, 'dlugopis.jpg', 0),
(7, 'plecak.jpg', 4);

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
