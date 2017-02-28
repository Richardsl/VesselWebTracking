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
	
	// PARSE MARINE TRAFFIC SITE AS PER 21.05.14
	$html = file_get_html('http://www.marinetraffic.com/no/ais/details/ships/' . $val);
	$item = $html->find('a[class=details_data_link]', 0)->plaintext;
	
	// CREATE 
	$longLat = explode('/', $item);
	$long = $longLat[0];
	$lat = $longLat[1];
	
	//---------------------------------
	
	
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

