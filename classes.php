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
?>