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

class Saison
{
	
}

class Tipp
{
public $season, $spieler, $spieltag, $id;

}

function tippBezeichner()
{
	return array('Sieg Team 1', 'Sieg Team 2', 'Unentschieden', 'Ergebnis');
}
function tippToNr($string)
{
	$tippID = array('Sieg Team 1' => 1,
					'Sieg Team 2' => 2,
					'Unentschieden' => 3,
					'Ergebnis' => 4);
	return $tippID[$string];
}

function berechnePunkte($tipp, $ergebnis)
{
	
}
?>