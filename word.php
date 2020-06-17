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

require_once 'Template.php';
ModuleManager::load_modules();

$umowaID = $_REQUEST['umowaID'];
$reqToFarmer = $_REQUEST['toFarmer'];

if($umowaID != 0){
    $record = Utils_RecordBrowserCommon::get_record("umowy_extend",$umowaID);
    $umowa = Utils_RecordBrowserCommon::get_record("umowy",$record['id_umowy']);
    $documentName = $record['childtype'];

    if($documentName == "umowa_warchlak_gruzja"){
        $company = Utils_RecordBrowserCommon::get_record("company", $record['farmer']);
        $value = $company['agreement_piglet'];
        $record['warchlakinumber'] = str_replace("BEZTERMINOWA KREDYTOWA", "", $value);
    }
    $record['placesigning'] = '';
    if($record['farmer']){
        $company = Utils_RecordBrowserCommon::get_record("company", $record['farmer']);
        $record['placesigning'] = $company['city'];
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
    if($record['datefrom']){
        $days = Utils_CommonDataCommon::get_value("Umowy/zakonczenie_umowy");
        $record['dateendrange'] = date("d-m-Y",strtotime($record['datefrom']." +$days days"));
    }else{ 
        $record['dateendrange'] = '';
    }
    $record['pelnomocnik1'] = $record['farmer'];

    $record['number'] = $umowa['number'];
    $mainComapny = CRM_ContactsCommon::get_main_company();
    $mainComapny = CRM_ContactsCommon::get_company($mainComapny);
    $record['mainpesel'] = $mainComapny['pesel'];

}
else{
    $documentDOCX = new Template();
    $documentName = $_REQUEST['document'];
    $documentDOCX->open(__DIR__."/templates/".$documentName."_data.docx");
    $fieldsInDocument = $documentDOCX->getVariables();
    $fieldsAndType = array();
    foreach ($fieldsInDocument as $field){
        $text = $field;
        $text = str_replace("{","",$text);
        $text = str_replace("}","",$text);
        $text = explode("_",$text);
        $type = $text[0];
        $name = $text[1];
        $record[$name] = "";
    }

    $record['number'] = "";
    $record['pelnomocnik1'] = "";
}

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
$name = $documentName;
$word->saveAs("data/document.docx");
exec("unoconv -f pdf /var/www/epesi/data/document.docx");
header("Content-type: application/pdf"); 
header("Content-Disposition: inline; filename=document.pdf"); 
@readfile('data/document.pdf');
unlink('data/document.docx');
unlink('data/document.pdf');




exit();
