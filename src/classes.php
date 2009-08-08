<?php
class Benutzer
{	
	public $name, $password, $picture = NULL, $role, $id;
	function Benutzer($name, $password)
	{
		$this->name = $name;
		$this->password = $password;
		$this->role = 1;
	}
}

class Spieltag
{
	public $saison, $tipps, $id;
	function Spieltag($saison)
	{
		
	}
}

class Spiel
{
	public $saison, $t1, $t2, $ergebnis, $anstoss;
	function Spiel($saison, $t1, $t2, $ergebnis, $anstoss)
	{
		$this->saison = $saison;
		$this->saison = $t1;
		$this->saison = $t2;
		$this->saison = $ergebnis;
		$this->saison = $anstoss;
	}
}
class Saison
{
	
}

class Tipp
{
public $season, $spieler, $spieltag, $id;

}

/*class Table
{
	private $html_string;
	function Table()
	{
	}
	function begin()
	{
		$html_string = "<table><br>";
	}
	function end()
	{
		$html_string = "</table><br>";
	}
	function add
}*/

function berechnePunkte($tipp, $ergebnis)
{
	$exp = "[0-9]{1}:[0-9]{1}";
	if (empty($tipp) || empty($ergebnis))
		return 0;
	if (!ereg($exp, $tipp) || !ereg($exp, $tipp))
		return 0;
	// Ergebnis und Tipp sind gleich
	if ($ergebnis == $tipp)
		return 8;
	$erg_arr = explode(":", $ergebnis);
	$tipp_arr = explode(":", $tipp);
	
	$erg_tore_links = $erg_arr[0];
	$erg_tore_rechts = $erg_arr[1];
	
	$tipp_tore_links = $tipp_arr[0];
	$tipp_tore_rechts = $tipp_arr[1];
	
	$erg_diff = $erg_tore_links - $erg_tore_rechts;
	$tipp_diff = $tipp_tore_links - $tipp_tore_rechts;
	
	// Unentschiden getippt, 
	// Tipp und Ergebnisse unterscheiden sich in einem Tor Differenz -> 2 Punkte (1:1 2:2)
	// sonst 1 Punkt (1:1 3:3)
	if ($erg_diff == 0 && $tipp_diff == 0) {
		$erg_tipp_diff = abs($erg_tore_links - $tipp_tore_links);
		if ( $erg_tipp_diff == 1)
			return 4;
		else
			return 2;
	}
	// Team1 gewinnt gegen Team2 (Team1 = Heimteam ???)
	// Bei gleicher Tordifferenz aber unterschiedlichem Ergebnis -> 2 Punkte
	// Bei unterschiedlichem Ergebnis und Tordifferenz -> 1 Punkt
	if ($erg_tore_links > $erg_tore_rechts && $tipp_tore_links > $tipp_tore_rechts){
		if ($erg_diff == $tipp_diff)
			return 4;
		else
			return 2;
	}
	// Team 1 verliert gegen Team2 (Team2 = Auswärtsteam ???)
	// s.o.
	if ($erg_tore_links < $erg_tore_rechts && $tipp_tore_links < $tipp_tore_rechts){
		if ($erg_diff == $tipp_diff)
			return 4;
		else
			return 2;
	}
	return 0;
}
?>