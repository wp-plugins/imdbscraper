<?php
/*
Plugin Name: ImdbScraper
Plugin URI: 
Description: Gives you the MOVIEmeter from IMDB.com that contains the hottest movies right now
Version: 1.0
Author: Erik Nilsson
Author 
License: GPLv2 or later
*/
require_once('ImdbScraper.php');
function init() {
		$imdb = new ImdbScraper();
		$arr = $imdb->getTop10Data();
		$html = "<ul id='imdb'>";
		for ($i=0; $i < count($arr); $i++) { 
			$html .= "<li><a href='".$arr[$i]["link"]."'>".$arr[$i]["title"]."</a></li>";
		}
		$html .= "</ul>"; 
		echo $html; }

add_shortcode('ImdbScraper', 'init');
?>