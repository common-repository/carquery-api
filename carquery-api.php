<?php
/*
Plugin Name: CarQuery API Vehicle Data Plugin
Plugin URI: https://www.carqueryapi.com/wordpress-plugin/
Description: The CarQuery API Plugin easily creates dependent vehicle year, make, model, and trim dropdowns.
Version: 1.6
Author: CarQueryAPI
Author URI: https://www.carqueryapi.com
License: GPL2

Copyright 2019 CarQueryAPI  (email : contact@carqueryapi.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

Class CarQueryAPI{

	static $add_script;

	static function init() {

		//Register ShortCodes
		add_shortcode("cq-year", 	array(__CLASS__, 'cq_year' ));
		
		add_shortcode("cq-make", 	array(__CLASS__, 'cq_make' ));
		
		add_shortcode("cq-model", 	array(__CLASS__, 'cq_model'));
		
		add_shortcode("cq-trim", 	array(__CLASS__, 'cq_trim' ));
		
		add_shortcode("cq-button", 	array(__CLASS__, 'cq_button' ));
		
		//Load javascript in wp_footer
		
		add_action('init', 		array(__CLASS__, 'register_script' ));
		
		add_action('wp_footer', array(__CLASS__, 'print_script' ));
	}
	


	//Return HTML for year drop down
	static function cq_year() {

		//Trigger javascript scripts to load
		self::$add_script = true;

	 	return '<select name="cq-year" id="cq-year"></select>';
	}
	
	//Return HTML for submit button
	static function cq_button() {

		//Trigger javascript scripts to load
		self::$add_script = true;

	 	return '<input id="cq-show-data" type="button" value="Show Data"/>
				<div id="car-model-data"> </div>';
	}


	//Return HTML for makes dropdown
	static function cq_make() {

		//Trigger javascript scripts to load
		self::$add_script = true;

		return '<select name="cq-make" id="cq-make"></select>';
	}


	//Return HTML for models drop down
	static function cq_model() {

		//Trigger javascript scripts to load
		self::$add_script = true;

		return '<select name="cq-model" id="cq-model"></select>';
	}


	//Return HTML for trims dropdown
	static function cq_trim() {

		//Trigger javascript scripts to load
		self::$add_script = true;

		return '<select name="cq-trim" id="cq-trim"></select>';
	}


	//Include necessary javascript files
	static function register_script() {

		wp_register_script('carquery-api-js', 'https://www.carqueryapi.com/js/carquery.0.3.4.js', array('jquery'), '0.3.4', true);

	}


	//check if the short codes were used, print js if required
	static function print_script() {

		//Only load javascript if the short code events were triggered
		if ( ! self::$add_script )
			return;

		wp_print_scripts('carquery-api-js');

		//initialize the carquery objects
		self::carquery_init();
	}


	//Output required carquery javascript to footer.
	static function carquery_init()
	{
		?>

	<script type='text/javascript'>
	
	$(document).ready(
	
	function()
	{
     //Create a variable for the CarQuery object.  You can call it whatever you like.
     var carquery = new CarQuery();

     //Run the carquery init function to get things started:
     
	 carquery.init();
     
     //Optionally, you can pre-select a vehicle by passing year / make / model / trim to the init function:
     //carquery.init('2000', 'dodge', 'Viper', 11636);

     //Optional: Pass sold_in_us:true to the setFilters method to show only US models. 
     
	 carquery.setFilters( {sold_in_us:true} );
	
     //Optional: initialize the year, make, model, and trim drop downs by providing their element IDs
    
	carquery.initYearMakeModelTrim('cq-year', 'cq-make', 'cq-model', 'cq-trim');

     //Optional: set the onclick event for a button to show car data.
     
	 $('#cq-show-data').click(  function(){ carquery.populateCarData('car-model-data'); } );

     //Optional: initialize the make, model, trim lists by providing their element IDs.
     
	 carquery.initMakeModelTrimList('make-list', 'model-list', 'trim-list', 'trim-data-list');

     //Optional: set minimum and/or maximum year options.
     
	 carquery.year_select_min=1941;
     
	 carquery.year_select_max=2019;
 
     //Optional: initialize search interface elements.
     //The IDs provided below are the IDs of the text and select inputs that will be used to set the search criteria.
     //All values are optional, and will be set to the default values provided below if not specified.
     
	 var searchArgs =
	 ({
         body_id:                       "cq-body"
        ,default_search_text:           "Keyword Search"
        ,doors_id:                      "cq-doors"
        ,drive_id:                      "cq-drive"
        ,engine_position_id:            "cq-engine-position"
        ,engine_type_id:                "cq-engine-type"
        ,fuel_type_id:                  "cq-fuel-type"
        ,min_cylinders_id:              "cq-min-cylinders"
        ,min_mpg_hwy_id:                "cq-min-mpg-hwy"
        ,min_power_id:                  "cq-min-power"
        ,min_top_speed_id:              "cq-min-top-speed"
        ,min_torque_id:                 "cq-min-torque"
        ,min_weight_id:                 "cq-min-weight"
        ,min_year_id:                   "cq-min-year"
        ,max_cylinders_id:              "cq-max-cylinders"
        ,max_mpg_hwy_id:                "cq-max-mpg-hwy"
        ,max_power_id:                  "cq-max-power"
        ,max_top_speed_id:              "cq-max-top-speed"
        ,max_weight_id:                 "cq-max-weight"
        ,max_year_id:                   "cq-max-year"
        ,search_controls_id:            "cq-search-controls"
        ,search_input_id:               "cq-search-input"
        ,search_results_id:             "cq-search-results"
        ,search_result_id:              "cq-search-result"
        ,seats_id:                      "cq-seats"
        ,sold_in_us_id:                 "cq-sold-in-us"
     }); 
	 
     carquery.initSearchInterface(searchArgs);

     //If creating a search interface, set onclick event for the search button.  Make sure the ID used matches your search button ID.
     
	 $('#cq-search-btn').click( function(){ carquery.search(); } );
	});

	</script>
	    <?php
	}
}
//Initilazed the object
CarQueryAPI::init();

?>