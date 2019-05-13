<?php
/**
 * Created by PhpStorm.
 * User: Mati
 * Date: 11.04.2019
 * Time: 16:59
 */

//header("Content-type: text/html");
//header("Cache-Control: no-cache, must-revalidate");
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past

define('CID',false);
define('READ_ONLY_SESSION',true);
require_once('../../include.php');
ModuleManager::load_modules();

if(!Acl::is_user()) return false;

$resultsReturned = [];
if($_GET['fieldsFor'] == "farmer") {

    $rboCompany = new RBO_RecordsetAccessor("company");
    $farmer = $rboCompany->get_record($_GET['id']);

    $pelnomocnik = Utils_RecordBrowserCommon::get_records("contact" , array('related_companies' => array($_GET['id'])), array() , array());
    $pelnomocnikRecord = null;
    foreach($pelnomocnik as $p){
        $pelnomocnikRecord = $p;
        break;
    }

    $trader =  Utils_RecordBrowserCommon::get_record("contact" , $farmer['account_manager']);

    $farmer['traderid'] = $farmer['account_manager'];
    $farmer['tradername'] = $trader['last_name']." ".$trader['first_name'];
    $farmer['traderemail'] = $trader['email'];
    $farmer['traderphone'] = $trader['mobile_phone'];

    $farmer['pelnomocnik2name'] = $pelnomocnikRecord['last_name']." ".$pelnomocnikRecord['first_name'];
    $farmer['pelnomocnik2id'] = $pelnomocnikRecord['id'];
    $farmer['pelnomocnik2pesel'] = $pelnomocnikRecord['pesel'];



    $resultsReturned['farmerDetails'] = $farmer;
    


}
if($_GET['fieldsFor'] == "trader"){
    $rboContacts = new RBO_RecordsetAccessor("contact");
    $trader = $rboContacts->get_record($_GET['id']);
    $resultsReturned['traderDetails'] = $trader;
}


if($_GET['fieldsFor'] == "parent"){
    $rboUmowy = new RBO_RecordsetAccessor("umowy");
    $id = $_GET['id'];
    $umowa = $rboUmowy->get_record($id);
    $rboSubPos = new RBO_RecordsetAccessor('umowy_extend');
    $subPos = $rboSubPos->get_records(array("id_umowy" => $id),array(),array());
    foreach ($subPos as $sub){
        $subPos = $sub;
        break;
    }
    if($subPos['farmer']) {
        $r = Utils_RecordBrowserCommon::get_record('company', $subPos['farmer']);
        $subPos['farmername'] = $r['company_name'];
    }
    if($subPos['trader']) {
        $r = Utils_RecordBrowserCommon::get_record('contact', $subPos['trader']);
        $subPos['tradername'] = $r['last_name'] . " " . $r['first_name'];
    }
    if($subPos['pelnomocnik1']) {
        $r = Utils_RecordBrowserCommon::get_record('contact', $subPos['pelnomocnik1']);
        $subPos['pelnomocnik1name'] = $r['last_name'] . " " . $r['first_name'];
    }
    if($subPos['pelnomocnik2']) {
        $r = Utils_RecordBrowserCommon::get_record('contact', $subPos['pelnomocnik2']);
        $subPos['pelnomocnik2name'] = $r['last_name'] . " " . $r['first_name'];
    }
    if($subPos['dateStart']) {
        $d = Base_RegionalSettingsCommon::time2reg($subPos['dateStart'],false,true,true,true);
        $subPos['dateStart'] = $d;
    }
    if($subPos['dateSigning']) {
        $d = Base_RegionalSettingsCommon::time2reg($subPos['dateSigning'],false,true,true,true);
        $subPos['dateSigning'] = $d;
    }
    if($subPos['dateFrom']) {
        $d = Base_RegionalSettingsCommon::time2reg($subPos['dateFrom'],false,true,true,true);
        $subPos['dateFrom'] = $d;
    }
    if($subPos['dateEnd']) {
        $d = Base_RegionalSettingsCommon::time2reg($subPos['dateEnd'],false,true,true,true);
        $subPos['dateEnd'] = $d;
    }

    $resultsReturned['parent'] = $subPos;
}

echo json_encode($resultsReturned);




?>