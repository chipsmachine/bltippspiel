<?php
class Benutzer
{	
	public $name, $password, $picture = NULL, $role, $id;
	function Benutzer($name, $password)
	{
		$this->name = $name;
		$this->password = $password;
	}
}

class Spieltag
{
var
	$saison, $tipps, $id;
	function Spieltag($saison)
	{
		
	}
}

class Saison
{
	
}

class Tipp
{
var $season, $spieler, $spieltag, $id;

}
?>