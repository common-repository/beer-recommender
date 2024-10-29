<?php
/*
Plugin Name: Beer Recommender
Pluging URI: http://b-zweimueller.net
Version: 1.0
Author: Christoph Eberl & Bernhard Zweimüller
Description: API Abfragen mit brewerydb.com
*/

//====================================================================
//		CONFIGURATION - Insert your brewerydb.com AIP Key here
//====================================================================

$apikey = "";

//====================================================================
//					SHORTCODE [beerrecommender]
//====================================================================

function beerrecommender_shortcode($atts) {
	extract(shortcode_atts(array(
	  'do' => '',
	  'mid' => '22',
    ), $atts));

    $output = '';
	$jsonData = "http://api.brewerydb.com/v2/beer/random?key=3a0e5d1b653acef065c73891879aa27d";

	//Als Funktion, wenn relevante Felder leer -> Rekursiver aufruf???

	$phpJsonString = file_get_contents( $jsonData );
	$data = json_decode( $phpJsonString );  
	
	$beerId = $data->data->id;
	$beerName = $data->data->name;
	$beerAbv = $data->data->abv;
	
	if ($data->data->description != "") {
		$beerDescripton = $data->data->description;
	} else {
		$beerDescripton = "Sorry, no description found! :(";
	}
	
	$ausgabe = "<p><h2>" . $beerName . "</h2> Abv:". $beerAbv ."% <p>" . $beerDescripton . "</p></p>";

    return utf8_encode($ausgabe);
}

/* Add Shortcode */
add_shortcode('beerrecommender', 'beerrecommender_shortcode');


//====================================================================
//					SHORTCODE BUTTON TINY MCE 
//====================================================================


 add_action('init', 'beerRecommender_shortcode_button_init');
 function beerRecommender_shortcode_button_init() {

      if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
           return;

      add_filter("mce_external_plugins", "beerRecommender_register_tinymce_plugin"); 
      add_filter('mce_buttons', 'beerRecommender_add_tinymce_button');
}


function beerRecommender_register_tinymce_plugin($plugin_array) {
											   //http://localhost:8080
    $plugin_array['beerRecommender_button'] = '/wp-content/plugins/beerrecommender/shortcode.js';
    return $plugin_array;
}


function beerRecommender_add_tinymce_button($buttons) {
    $buttons[] = "beerRecommender_button";
    return $buttons;
}

//====================================================================
//					RANDOM BEEER WIDGET 
//====================================================================


class beerRecommenderWidget extends WP_Widget {
	function beerRecommenderWidget() {
		parent::__construct( false, 'Beer Recommender Widget');
	}
	
	function widget( $args, $instance ) {
		//Widget output	
		$jsonData = "http://api.brewerydb.com/v2/beer/random?key=3a0e5d1b653acef065c73891879aa27d";
	
		$phpJsonString = file_get_contents( $jsonData );
		$data = json_decode( $phpJsonString );  
		
		$beerId = $data->data->id;
		$beerName = $data->data->name;
		$beerAbv = $data->data->abv;
		
		if ($data->data->description != "") {
			$beerDescripton = $data->data->description;
		} else {
			$beerDescripton = "Sorry, no description found! :(";
		}
		echo("<p><h2>" . $beerName . "</h2> Abv:". $beerAbv ."% <p>" . $beerDescripton . "</p></p>");
	}
	
	function update ( $new_instance, $old_instance ) {
		//Save widget options
	}
	
	function form () {
		// Why is there an error in line 203???????
		echo("A Widget that recommends you a delicious random beer! :)");
	}
}

add_action('widgets_init', 'beerRecommender_register_widgets');

function beerRecommender_register_widgets() {
	register_widget('beerRecommenderWidget');
}


?>