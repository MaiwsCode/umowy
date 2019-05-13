<?php
/**
 * Created by PhpStorm.
 * User: Mati
 * Date: 06.06.2019
 * Time: 12:29
 */

$cid = $_REQUEST['cid'];
define('CID', $cid);
define('READ_ONLY_SESSION',true);

require_once('../../include.php');

ModuleManager::load_modules();



$umowaID = $_REQUEST['umowaID'];

$record = Utils_RecordBrowserCommon::get_record("umowy_extend",$umowaID);
$umowa = Utils_RecordBrowserCommon::get_record("umowy",$record['id_umowy']);

$documentName = $record['childtype'];

if($documentName == "umowa_warchlak_gruzja"){
    $company = Utils_RecordBrowserCommon::get_record("company", $record['farmer']);
    $value = $company['agreement_piglet'];
    $record['warchlakinumber'] = str_replace("BEZTERMINOWA KREDYTOWA", "", $value);
}

if($record['farmer']){
    $record['farmer'] = umowyCommon::getFarmerName($record['farmer']);
    $record['farmer'] = preg_replace('/TN/', '', $record['farmer']);
    $record['farmer'] = preg_replace('/[0-9]/', '', $record['farmer']);
}
if($record['trader']){
    $record['trader'] = umowyCommon::getTraderName($record['trader']);
}
if($record['pelnomocnik2']){
    $record['pelnomocnik2'] = umowyCommon::getTraderName($record['pelnomocnik2']);
}
$record['pelnomocnik1'] = $record['farmer'];

$record['number'] = $umowa['number'];

$word = new \PhpOffice\PhpWord\TemplateProcessor(__DIR__."/templates/".$documentName.".docx");



foreach($record as $key => $item){
    if(strlen($item) == 0){
        $item = "............................";
    }
    else if(preg_match("/date+[a-zA-Z]+/",$key)){
        $item = Base_RegionalSettingsCommon::time2reg($item,false,true,true,true);
    }
    $word->setValue($key,$item);
}
$name = $record['number'].'_'.$record['childtype'].".docx";
header("Content-Description: File Transfer");
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$name.'"');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
$word->saveAs("php://output");
exit();
