<?php

include("htmldom.php");


// ARRAY WITH SHIPS NAMES AND MMSI NUMBERS
$ships = array(
	'sea'    => '236310000',
	'chaser' => '236317000',
	'sky'    => '236384000',
	'star'   => '236483000',
	'spirit' => '236538000',
	'swift'  => '236111851',
	'sword'  => '236111902'
);

// CREATE ROOT XML ELEMENT
$xml = new DOMDocument();
$xml_ships = $xml->createElement("ships");
//---

foreach($ships as $ship=>$val){

	//----------GET DATA---------------
    	// PARSE vesselfinder SITE AS PER 07 Oct 2020
    	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)'); 
    	$html = file_get_html('https://www.vesselfinder.com/vessels/SANCO-'. $ship .'-MMSI-' . $val);
 
    	$results = $html->find('div[class=column ship-section]', 0)->plaintext;
    	$matches = array();
    	preg_match('/\(coordinates(.+)(E|W)\)\s/', $results, $matches);
    
   
    	$withoutLetters = preg_replace("/[a-zA-Z]/", "", $matches[1]);
    	$withoutWhitespace = str_replace(' ', '', $withoutLetters);
    
	// CREATE 
	$longLat = explode('/', $withoutWhitespace);
	$long = $longLat[0];
   	 $lat = $longLat[1];
	
	
	//----------PUT INTO XML OBJECT---------------
	
	// CREATE SHIP CHILD
	$xml_ship = $xml->createElement("ship");
		
		// CREATE NAME CHILD
		$xml_ship_child = $xml->createElement("name");	
		$xml_ship_child->nodeValue = $ship;
		$xml_ship->appendChild($xml_ship_child);
		
		// CREATE MMSI CHILD
		$xml_ship_child = $xml->createElement("mmsi");	
		$xml_ship_child->nodeValue = $val;
		$xml_ship->appendChild($xml_ship_child);
		
		// CREATE LONG CHILD
		$xml_ship_child = $xml->createElement("long");	
		$xml_ship_child->nodeValue = $long;
		$xml_ship->appendChild($xml_ship_child);
		
		// CREATE LAT CHILD
		$xml_ship_child = $xml->createElement("lat");	
		$xml_ship_child->nodeValue = $lat;
		$xml_ship->appendChild($xml_ship_child);
		
	// APPEND SHIP CHILD TO SHIPS ROOT
	$xml_ships->appendChild( $xml_ship );
}

// FINNISH OFF AND SAVE SHIPS.XML FILE
$xml->appendChild( $xml_ships );
$xml->formatOutput = true;
$xml->save("ships.xml");

