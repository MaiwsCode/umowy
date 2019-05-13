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

    public static function autoselect_company($str, $crits, $format_callback) {
        $str = explode(' ', trim($str));
        foreach ($str as $k=>$v)
            if ($v) {
                $v = "%$v%";
                $crits = Utils_RecordBrowserCommon::merge_crits($crits, array('(~company_name'=>$v,'|~tax_id'=>$v));
            }
        $recs = Utils_RecordBrowserCommon::get_records('company', $crits, array(), array('company_name'=>'ASC'), 10);
        $ret = array();
        foreach($recs as $v) {
            $ret[$v['id']."__".$v['company_name']] = call_user_func($format_callback, $v, true);
        }
        return $ret;
    }
    public static function contact_format_company($record, $nolink=false){

        $ret = $record['company_name'];
        return $ret;
    }

    public static function autoselect_contacts($str, $crits, $format_callback) {
        $str = explode(' ', trim($str));
        foreach ($str as $k=>$v)
            if ($v) {
                $v = "%$v%";
                $crits = Utils_RecordBrowserCommon::merge_crits($crits, array('(~first_name'=>$v,'|~last_name'=>$v));
            }
        $recs = Utils_RecordBrowserCommon::get_records('contact', $crits, array(), array('last_name'=>'ASC'), 10);
        $ret = array();
        foreach($recs as $v) {
            $ret[$v['id']."__".$v['last_name']." ".$v['first_name']] = call_user_func($format_callback, $v, true);
        }
        return $ret;
    }
    public static function contact_format_contact($record, $nolink=false){

        $ret = $record['last_name']." ".$record['first_name'];
        return $ret;
    }

    public static function autoselect_umowy($str, $crits, $format_callback) {
        $str = explode(' ', trim($str));
        foreach ($str as $k=>$v)
            if ($v) {
                $v = "%$v%";
                $crits = Utils_RecordBrowserCommon::merge_crits($crits, array('~number'=>$v,'|~type'=>$v));
            }
        $recs = Utils_RecordBrowserCommon::get_records('umowy', $crits, array(), array('number'=>'ASC'), 10);
        $ret = array();
        foreach($recs as $v) {
            $ret[$v['id']."__".$v['number']] = call_user_func($format_callback, $v, true);
        }
        return $ret;
    }
    public static function format_umowy($record, $nolink=false){

        $ret = $record['number'];
        return $ret;
    }

    public static function getFarmerName($id){
        $farmer = Utils_RecordBrowserCommon::get_record("company",$id);
        return $farmer['company_name'];
    }

    public static function  getTraderName($id){
        $trader = Utils_RecordBrowserCommon::get_record("contact",$id);
        return $trader['last_name']." ".$trader['first_name'];
    }




    public static function automulti_format($records){

        return $records;
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
}
