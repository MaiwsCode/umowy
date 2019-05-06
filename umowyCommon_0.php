<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class umowyCommon extends ModuleCommon {

    public static function menu() {
		return array(__('Module') => array('__submenu__' => 1, __('Umowy') => array(
	    	'__icon__'=>'umowa.png','__icon_small__'=>'umowa.png'
			)));
    }


	public static function __date($strdate){

		$transl['styczeń'] = "January";
		$transl['luty'] = "February";
		$transl["marzec"] = "March";
		$transl["kwiecień"] = "April";
		$transl["maj"] = "May";
		$transl["czerwiec"] = "June";
		$transl["lipiec"] = "July";
		$transl["sierpień"] = "August";
		$transl["wrzesień"] = "September";
		$transl["pażdziernik"] = "October";
		$transl["listopad"] = "November";
		$transl["grudzień"] = "December";

		$transl['sty'] = "Jan";
		$transl['lut'] = "Feb";
		$transl["mar"] = "Mar";
		$transl["kwi"] = "Apr";
		$transl["maj"] = "May";
		$transl["cze"] = "Jun";
		$transl["lip"] = "Jul";
		$transl["sie"] = "Aug";
		$transl["wrz"] = "Sep";
		$transl["paź"] = "Oct";
		$transl["lis"] = "Nov";
		$transl["gru"] = "Dec";

		$dates = explode(" ", $strdate); 
		$dates[1] = $transl[$dates[1]];
		$strdate = $dates[0]." ".$dates[1]." ".$dates[2];
		return $strdate;

	}
	public static function test(){
        print("OY");
    }

}
