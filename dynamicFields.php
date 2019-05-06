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
    $resultsReturned['parent'] = $subPos;
}

echo json_encode($resultsReturned);




?>