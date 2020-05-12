<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class umowy extends Module {

    public function body()
    {
        Base_LangCommon::install_translations('umowy');
        if(!$this->get_module_variable('view')) {
            $this->set_module_variable('view', 'all');
        }
        if(isset($_REQUEST['view'])){
            $this->set_module_variable('view', $_REQUEST['view']);
            if($_REQUEST['view'] == "all"){
                $this->set_module_variable('forRecord', "");
            }else {
                $this->set_module_variable('forRecord', $_REQUEST['forRecord']);
            }
        }

        if(isset($_REQUEST['__jump_to_RB_table'])){
            $rs = new RBO_RecordsetAccessor($_REQUEST['__jump_to_RB_table']);
            $rb = $rs->create_rb_module($this);
            $this->display_module($rb);
        }

        $rboUmowy = new RBO_RecordsetAccessor("umowy");
        $del_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/Calendar/delete.png' alt='Usuń' title='Usuń' />";
        $edit_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/Calendar/edit.png' title='Edytuj' alt='Edytuj' />";
        $word_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/FileStorage/z-attach.png' title='Pobierz Word' alt='Pobierz Word' />";
        $createByExist = "<img border='0' src='data/Base_Theme/templates/default/Utils/FileStorage/icon_small.png' 
                            title='Utwórz nowy na podstawie' alt='Utwórz nowy na podstawie' />";
        $view = "<img border='0' src='data/Base_Theme/templates/default/Utils/GenericBrowser/view.png' title='Pokaż szczegóły' alt='Pokaż szczegóły' />";
        $arrowUp = 'style="padding-right: 12px; margin-right: 12px; background-image: url(data/Base_Theme/templates/default/Utils/GenericBrowser/sort-ascending.png);
         background-repeat: no-repeat; background-position: right;"';
        $arrowDown = 'style="padding-right: 12px; margin-right: 12px; background-image: url(data/Base_Theme/templates/default/Utils/GenericBrowser/sort-descending.png);
         background-repeat: no-repeat; background-position: right;"';
      //  Base_ThemeCommon::install_default_theme($this->get_type());
        Base_ThemeCommon::load_css('umowy','default');
       // Base_LangCommon::install_translations('umowy');
        if($_REQUEST['action'] == 'sortBy'){
            $crits = $this->get_module_variable("sortCrits");
            $crits[$_REQUEST['field']] = $_REQUEST['type'];
            $this->set_module_variable("sortCrits", $crits);
            $this->set_module_variable($_REQUEST['field']."Sort", $this->changeSort($_REQUEST['type']));
          //  print("sort".$_REQUEST['field']);
        }

        //main view
        if($this->get_module_variable('view') == "reply"){
            $umowa = $rboUmowy->get_record($this->get_module_variable('forRecord'));
            $form = & $this->init_module('Libs/QuickForm');
            $form->addElement("textarea",'comment','Uwagi', array("cols" => "180", "rows" => "20"));
            Base_ActionBarCommon::add(
                'back',
                'Wtecz',
                $this->create_callback_href(array($this,'back'),array("details")),
                null,
                1
            );
            if ($form->validate()) {
                $array_fields = $form->exportValues();
                $rboRecord = new RBO_RecordsetAccessor("umowy_comments");
                $record = $rboRecord->new_record();
                $record['id_umowy'] = $this->get_module_variable('forRecord');
                $record['comment'] = $array_fields['comment'];
                $record->save();
                $this->back("details");
            }else{
                Base_ActionBarCommon::add('send', __('Send'), $form->get_submit_form_href(),null,2);
                $form->display();
            }
        }

        if($this->get_module_variable('view') == "all") {

            Base_ActionBarCommon::add(
                'add',
                'Dodaj nową umowe',
                $this->create_callback_href(array($this,'addNew')),
                null,
                2
            );
            if($_REQUEST['action'] == "clearBrowser"){
                $this->set_module_variable("searchMain",null);
                $this->set_module_variable("searchMini",null);
            }

          //  $crits = $this->get_module_variable("sortCrits");
            require_once 'HtmlView.php';
            //$searchValuesMain = $this->get_module_variable("searchMain");
            $fields = array(
                array("text","number","Numer Umowy" ),
                array("select","status","Status",array("---"=>"---") + Utils_CommonDataCommon::get_array("Umowy/status")),
                array("select", "type", "Typ",  array("---"=>"---") + Utils_CommonDataCommon::get_array("Umowy/typyUmow")),
              /*  array("select",'trader', "Handlowiec", array()),*/
               );



            $htmlElements = new Browser($this->init_module('Libs/QuickForm'), $fields);
            $crits2 = array('status' => 'DESC');
            $fcallback = array('umowyCommon','contact_format_company');
            $htmlElements->form->addElement('autoselect', 'farmer', __('farmer'), array(),
                array(array('umowyCommon','autoselect_company'), array($crits, $fcallback)), $fcallback);
            $htmlElements->display($this->create_href(array('action'=>'clearBrowser')));
            $this->set_module_variable("parentType", 'Null');
            if($htmlElements->form->isSubmitted()){
                if($htmlElements->form->isSubmitted()) {
                    $submitedValues = $htmlElements->form->getSubmitValues();
                    $search = array("_active" => 1);
                    foreach ($submitedValues as $key => $value) {
                        if ($value != null && $value != "---" && $key != "submited" && $key != "__action_module__" && $key != "find") {
                            $search["~$key"] = "%$value%";
                        }
                    }
                }else{
                    $search = array("_active" => 1) + $this->get_module_variable("searchMini");
                }
                $ids = [];
                $rboUmowyExpand = new RBO_RecordsetAccessor("umowy_extend");
                $rboExpandUmowy = $rboUmowyExpand->get_records($search,array(),array('id' => 'DESC'));
                foreach ($rboExpandUmowy as $expandUmowa){
                    if($expandUmowa->_active){
                        $ids[$expandUmowa->id_umowy] = $expandUmowa->id_umowy;
                    }
                }
                $this->set_module_variable("searchMini",$search);
                $mainCrits = array('id' => $ids, '_active'=>1);
                if($submitedValues['number']){
                    $mainCrits['~number'] = "%".$submitedValues['number']."%";
                }
                else if($submitedValues['status'] != "---"){
                    $mainCrits['~status'] = "%".$submitedValues['status']."%";
                }
                else if($submitedValues['farmer']){
                    $mainCrits['~farmer'] = "%".$submitedValues['farmer']."%";
                }
                else if($submitedValues['type']){
                    $mainCrits['~type'] = "%".$submitedValues['type']."%";
                }
                $umowy = $rboUmowy->get_records($mainCrits, array(),  $crits2);
                $this->set_module_variable("searchMain",$mainCrits);
            }else {
                $umowy = $rboUmowy->get_records(array(), array(), $crits2);
            }
            usort($umowy, function ($item1, $item2) {
                if ($item1['created_on'] == $item2['created_on']) return 0;
                return $item1['created_on'] > $item2['created_on'] ? -1 : 1;
            });
            load_css($this->get_module_dir().'theme/default.css');
            $tables = $this->getTables();
            $lis = "";
            Base_ThemeCommon::install_default_theme($this->get_type());
            foreach($tables as $key => $value){ 
                $href = $this->downloadBlankWord(0, $key);
                $childTables = $this->getChildTable($key);
                $childs = "";
                foreach($childTables as $childTableKey => $childTableValue){
                    if($childTableValue != "---"){
                        $href2 = $this->downloadBlankWord(0, $childTableKey);
                        $childs .= "<div style='margin-left:10px;padding-top:3px;padding-bottom:2px;'><a $href2> $childTableValue </a> </div>";
                    }
                }
                $lis .= "<div class='cardDownloadsBox'><a $href> $value </a>
                            <div style='margin-top:5px;'> $childs </div>
                </div>";
            }
            print("<div id='downloadBox'  > 
                        <span style='margin-left:10px;'> POBIERZ SZABLON </span>
                            $lis
                    </div>");
            $gb = &$this->init_module('Utils/GenericBrowser', null, '');
            $gb->set_table_columns(
                array(
                    array('name' => '', 'width' => 5),
                    array("name" => '<a ' . $this->create_href(
                        array('action' => 'sortBy', 'field' => 'farmer',
                            'type' => $this->get_module_variable("farmerSort"))) . ' >  Rolnik </a>', 'width' => 20),
                    array('name' => '<a ' . $this->create_href(
                        array('action' => 'sortBy', 'field' => 'number', 'type' => $this->get_module_variable("numberSort"))) . ' >Numer </a>', 'width' => 20),
                    array('name' => '<a ' . $this->create_href(
                        array('action' => 'sortBy', 'field' => 'type', 'type' => $this->get_module_variable("typeSort"))) . '  >Typ </a>', 'width' => 20),
                    array('name' => '<a ' . $this->create_href(
                        array('action' => 'sortBy', 'field' => 'status', 'type' => $this->get_module_variable("statusSort"))) . ' > Status </a>', 'width' => 20)
                )
            );
            foreach ($umowy as $umowa) {
                $farmerName = Utils_RecordBrowserCommon::get_records("umowy_extend", array("id_umowy" => $umowa->id, "childType" => $umowa->type),array(),array());
                foreach($farmerName as $names){
                    $farmerName = $names['farmer'];
                    break;
                }
                $edit = "";
                $del = "";
                if($umowa->status == 2 || $umowa->status == 4) {
                    $edit = $umowa->record_link($edit_btn,false,"edit");
                    $del = "<a " . $this->create_confirm_callback_href("Na pewno usunąć tą umowę i wszystkie należące pod nią?",
                            array($this, "recordDelete"), array($umowa->id, "umowy")) . ">" . $del_btn . "</a>";
                }
                $gb->add_row(
                    $this->showDetailsFor($view, $umowa->id)." ".
                    $edit." ".
                   $del,
                   $this->getFarmerName($farmerName),
                   $umowa->get_val("number"),
                    $umowa->get_val("type"),
                    $umowa->get_val("status")
                );
            }
            $gb->get_limit(count($umowy));
            $gb->automatic_display($paging=true);
        }
        // <---------------- szczegolowy ---------------- >
        if($this->get_module_variable('view') == "details"){
            $umowa = $rboUmowy->get_record($this->get_module_variable('forRecord'));
            Base_ActionBarCommon::add(
                'back',
                'Wtecz',
                $this->create_callback_href(array($this,'back'),array("all")),
                null,
                1
            );
            if($umowa->status == "2" || $umowa->status == "4"){
                Base_ActionBarCommon::add(
                    'add',
                    'Dodaj nową umowe',
                    $this->create_callback_href(array($this,'addNew')),
                    null,
                    2
                );
                Base_ActionBarCommon::add(
                    'send',
                    'Wyślij do akceptacji',
                    $this->create_callback_href(array($this,'manageRecord'), array("send",$umowa->id)),
                    null,
                    2
                );
            }
            if((Base_AclCommon::i_am_sa() == "1" || Base_AclCommon::i_am_admin() == "1") && $umowa->status == "3" ){
                Base_ActionBarCommon::add(
                    'send',
                    'Zaakceptuj',
                    $this->create_callback_href(array($this,'manageRecord'),array("accept",$umowa->id)),
                    null,
                    3
                );
                Base_ActionBarCommon::add(
                    'reply',
                    'Zwróć',
                    $this->create_callback_href(array($this,'manageRecord'),array("reply",$umowa->id)),
                    null,
                    4
                );
          
                Base_ActionBarCommon::add(
                    'delete',
                    'Odrzuć',
                    $this->create_callback_href(array($this,'manageRecord'),array("delete",$umowa->id)),
                    null,
                    5
                ); 
            }

            if($umowa->status == 4){
                $rboComment = new RBO_RecordsetAccessor("umowy_comments");
                $comments = $rboComment->get_records(array("_active" => 1 , "id_umowy" => $umowa->id),array(), array());
                foreach($comments as $comment){
                    print("<div position:fixed;z-index:9999;width:100%;height:100%;display:block; background-color: rgba(0,0,0,0.4);left:0;top:0;'>");
                        print("<div style='background-color: #fefefe;width: 40%;position: relative;border-radius:12px 12px;padding:10px;'>");
                            print("<h1 style='color:red;'> UWAGI </h1>");
                            print("<p style='font-size:18px;'>".nl2br($comment->comment)."</p>");
                        print("</div>");
                    print("</div>");
                }
            }

            $rboExpand = new RBO_RecordsetAccessor("umowy_extend");
            $subUmowy = $rboExpand->get_records(array('id_umowy'=>$umowa->id),array("!id"),array('childType' => "ASC"));
            $this->set_module_variable("parentType", $umowa['type']);
            print("<div style='text-align: left;padding-left:15%;' ><h2>
                    Umowa ".$this->getTableDisplayName($umowa->type).
                    "<br>"
                    ." Numer:   ".$umowa->number."<br> Status: ".$umowa->get_val('status')."</h2></div><BR>");
            foreach ($subUmowy as $subUmowa){
                print("<table style='background: rgb(255,255,255);text-align: center;' class='ttable' >");
                $tr1 = "";
                foreach ($subUmowa as $key => $value){
                    if($value != null && $key != "_active" && $key != "created_by" && $key != "childtype"
                        && $key != "created_on" && $key != "id" && $key != "id_umowy" ){
                        $tr1 .= "<tr style='display:none;' class='selected_tr'>";
                        if(preg_match("/date+[a-zA-Z]+/",$key)){
                           $time =  Base_RegionalSettingsCommon::time2reg($value,false,true,true,true);
                           $tr1 .= "<td style='padding-left:30px;background-color:#80d9f7;border-bottom:1px solid #4db3d6;'>".__($key).
                               "</td><td style='text-align:left;padding-left:30px;border-left:1px solid #2a7189;'>".$time."</td>";
                        }
                        else if($key == "farmer"  || $key == "pelnomocnik1"){
                            $tr1 .= "<td style='padding-left:30px;background-color:#80d9f7;border-bottom:1px solid #4db3d6;'>".__($key).
                                "</td><td style='text-align:left;padding-left:30px;border-left:1px solid #2a7189;'>".$this->getFarmerName($value)."</td>";
                        }
                        else if($key == "trader" ||  $key == "pelnomocnik2"){
                            $tr1 .= "<td style='padding-left:30px;background-color:#80d9f7;border-bottom:1px solid #4db3d6;'>".__($key).
                                "</td><td style='text-align:left;padding-left:30px;border-left:1px solid #2a7189;'>".$this->getTraderName($value)."</td>";
                        }
                        else{
                            $tr1 .= "<td style='padding-left:30px;background-color:#80d9f7;border-bottom:1px solid #4db3d6;'>".__($key).
                                "</td><td style='text-align:left;padding-left:30px;border-left:1px solid #2a7189;'>".$value."</td>";
                        }
                        $tr1 .= "</tr>";
                    }
                }
                $tableName = $this->getTableDisplayName($subUmowa["childType"]);
                if(!strlen($tableName)) $tableName = $this->getTableDisplayName($umowa->type);
                $del = "";
                $edit = "";
                $newByExist = "";

                if($this->isMainTable($subUmowa['childType']) ){
                    $newByExist = $this->createLink($createByExist,$this->create_callback_href(array($this,"addNewFromExist"),
                        array($subUmowa->id)));
                }

                if($umowa->status == 2 || $umowa->status == 4) {
                    $del = "<a " . $this->create_confirm_callback_href("Na pewno usunąć tą umowę?", array($this, "recordDelete"), array($subUmowa['id'], "umowy_extend")) . ">" . $del_btn . "</a>";
                    $edit = $this->createLink($edit_btn, $this->create_callback_href(array($this, "recordEdit"), array($subUmowa->id)));
                }
                if($umowa->status == 1){
                    $download =  $this->createLink($word_btn,$this->downloadWord($subUmowa->id));
                }else{
                    $download = "";
                }
                print("<tr ><th style='width:8%;'>".
                $edit." ".
                $newByExist." ".
                $download." ".
                $del.
                    " </th><th style='font-size:16px;background-color:#5090C1;color:white;text-align:left;cursor: pointer;' ><span class='expand' ".$arrowDown."> ".$tableName  ." </span> </th> </tr>");
                print($tr1);
                print("</table><br>");
                load_js($this->get_module_dir().'js/view.js');
                // <---------------- end szczegolowy ---------------- >
            }
        }
        return true;
    }

    public function back($view){
        $this->set_module_variable("view",$view);
    }
    public function manageRecord($action,$id){
        $rboUmowy = new RBO_RecordsetAccessor("umowy");
        switch($action){
            case "send":
                $umowa = $rboUmowy->get_record($id);
                if($umowa->status == "4"){
                    $commentsRBO = new RBO_RecordsetAccessor("umowy_comments");
                    $comments = $commentsRBO->get_records(array("_active" => 1 , 'id_umowy' => $id), array(), array());
                    foreach($comments as $comment){
                        $comment->delete();
                    }
                }
                $umowa->status = '3';
                $umowa->save();
                $email = Utils_CommonDataCommon::get_value("Umowy/notify_email");
                $msg  = "Umowa ".$umowa["number"]." oczekuje na weryfikację";
                Base_MailCommon::send($email,'[UMOWY] Nowa umowa została wysłana -'.$umowa["number"],$msg);
                break;
            case "accept":
                $umowa = $rboUmowy->get_record($id);
                $umowa->status = '1';
                $umowa->save();
                $rboTucze = new RBO_RecordsetAccessor("kontrakty");
                $companyRbo = new RBO_RecordsetAccessor("company");
               /* for($i =0;$i<$umowa['rzuty'];$i++){
                    $sub = Utils_RecordBrowserCommon::get_records("umowy_extend", array("id_umowy" => $umowa->id, "childType" => $umowa->type),array(),array());
                    $farmerName ="";
                    $date = "";
                    foreach($sub as $e){
                        $farmerName = $e['farmer'];
                        $date = $e['datestart'];
                        break;
                    }
                    $rolnik = $companyRbo->get_record($farmerName);
                    $d = 90 * $i;
                    $date = date('Y-m-d', strtotime($date. " + $d days"));
                    $name = $rolnik['company_name']." ".$date." (RZUT ".($i+1).")";
                    $tucz = array("farmer" => $farmerName, "kolczyk" => "", 
                                    "name_number" => $name, "status" => "Planned","data_start" => $date);
                    $newTucz = $rboTucze->new_record($tucz);
                    $newTucz->save();
    
                }*/
                break;
            case "reply":
                $umowa = $rboUmowy->get_record($id);
                $umowa->status = '4';
                $umowa->save();
                $this->set_module_variable("view","reply");
                break;
            case "delete":
                $umowa = $rboUmowy->get_record($id);
                $umowa->status = '0';
                $umowa->save();
                break;
        }
    }

    public $offsets = array(
    "nowa_formula" => 26 ,
    "doradztwo_hodowlano_zywieniowe" => 5 ,
    "umowa_warchlak_gruzja" => 76 ,
    "kupno_sprzedaz_trzoda" => 16 ,
    "zakup_warchlakow" => 32
    );
    public $offset = 32;

    public function addNew(){
        if($this->is_back()) return false;
        require_once 'HtmlView.php';
        require_once 'Template.php';

        $status = true;
        $rboUmowy = new RBO_RecordsetAccessor("umowy");
        $rboSubItems = new RBO_RecordsetAccessor("umowy_extend");
        $form = & $this->init_module('Libs/QuickForm');
        if($form->validate_with_message("Dodano","Coś poszło nie tak, sprawdź poprawność danych")) {
            $submitedValues = $form->getSubmitValues();
            $fieldsForNewUmowa = array();
            $fieldsForSubUmowa = array();
            $fieldsForNewUmowa['rzuty'] = $submitedValues['rzuty'];
            foreach($submitedValues as $key => $item){
                $key = strtolower($key);
                if($key != "submited" || $key != "submit" || $key != "__action_module__"){
                    //get table
                    if($key == "number"){
                        $fieldsForNewUmowa[$key]  = $item;
                        $fieldsForNewUmowa['status'] = 2;
                        $fieldsForNewUmowa['type'] = $submitedValues['documentType'];
                    }
                    else if ($key == "farmer"){
                        if(!is_numeric($submitedValues['farmer'])){
                            $_id = "";    
                            preg_match('/[+[0-9]+]/', $submitedValues['farmer'], $_id);
                            $fieldsForSubUmowa['farmer'] = $_id[0];
                            $fieldsForSubUmowa['farmer'] = str_replace("[","",$fieldsForSubUmowa['farmer'] );
                            $fieldsForSubUmowa['farmer'] = str_replace("]","",$fieldsForSubUmowa['farmer'] );
                        }else{
                            $fieldsForSubUmowa['farmer'] = $submitedValues['farmer'];
                        }

                    }
                    else if ($key == "pelnomocnik2"){
                        if(!is_numeric($submitedValues['pelnomocnik2'])){
                            $_id = "";    
                            preg_match('/[+[0-9]+]/', $submitedValues['pelnomocnik2'], $_id);
                            $fieldsForSubUmowa['pelnomocnik2'] = $_id[0];
                            $fieldsForSubUmowa['pelnomocnik2'] = str_replace("[","",$fieldsForSubUmowa['pelnomocnik2'] );
                            $fieldsForSubUmowa['pelnomocnik2'] = str_replace("]","",$fieldsForSubUmowa['pelnomocnik2'] );
                        }else{
                            $fieldsForSubUmowa['pelnomocnik2'] = $submitedValues['pelnomocnik2'];
                        }
                    }
                    else if($key == "pelnomocnik1"){
                        $fieldsForSubUmowa['pelnomocnik1'] = $fieldsForSubUmowa['farmer'];
                    }
                    else{
                        if(preg_match("/date+[a-zA-Z]+/",$key)){
                            $date = __($item);
                            $date = umowyCommon::__date($date);
                            $date = date("Y-m-d",strtotime($date));
                            $fieldsForSubUmowa[$key] = $date;

                        }else {
                            $fieldsForSubUmowa[$key] = $item;
                        }
                    }
                }
            }
            $isMainType = $this->isMainTable($submitedValues['documentType']);
            if($this->get_module_variable("forRecord") == "" || $isMainType) {
                 $newUmowa = $rboUmowy->new_record($fieldsForNewUmowa);
                 $newUmowa->save();
                 $fieldsForSubUmowa['id_umowy'] = $newUmowa->id;
                 $fieldsForSubUmowa['childType'] = $submitedValues['documentType'];
                 $newSubUmoaw = $rboSubItems->new_record($fieldsForSubUmowa);
                 $newSubUmoaw->save();
                 $this->set_module_variable("view",'details');
                 $this->set_module_variable("forRecord",$newUmowa->id);
                 $status =  false;
            }else{
                $fieldsForSubUmowa['id_umowy'] = $this->get_module_variable("forRecord");
                $fieldsForSubUmowa['childType'] = $submitedValues['documentType'];
                $newSubUmoaw = $rboSubItems->new_record($fieldsForSubUmowa);
                $newSubUmoaw->save();
            }
        }
        if($status){
        $htmlElements = new HtmlView();
        $theme = $this->init_module('Base/Theme');
        load_css($this->get_module_dir().'theme/default.css');
        $theme->assign("header", "Dodaj nową umowe");
        if($this->get_module_variable("parentType") == "Null"){
            print("<h3>Wybierz typ umowy: " .$htmlElements->selectListHref(array("---"=> "---") + $this->getTables(),'', true, "Dalej",
                                                                "getType", $this->create_href(array('docType' => 'null'))));
        }else{

            $tables = $this->getChildTable($this->get_module_variable("parentType"));
            $tablesExist = $rboSubItems->get_records(array("id_umowy" => $this->get_module_variable("forRecord")),array(),array());
            $tablesToAdd = array();
            foreach($tables as $key => $value){
                $add = true;
                foreach($tablesExist as $table ){
                    if($key == $table['childType']){
                        $add = false;
                    }
                }
                if($add){
                    $tablesToAdd[$key] = $value;
                }
            }
            print("<h3>Wybierz typ umowy: " .$htmlElements->selectListHref($tablesToAdd ,'', true, "Dalej",
                                                                "getType", $this->create_href(array('docType' => 'null'))));
        }
        Base_ActionBarCommon::add(
            'back',
            'Wróć',
            $this->create_back_href(),
            null,
            0
        );
        if(isset($_REQUEST['docType'])){
            $theme->assign("documentType", $this->getTableDisplayName($_REQUEST['docType']));
            //test path
            $isChild = true;
            $documentName = $_REQUEST['docType'];
            $childTables = $this->getTables();
            foreach($childTables as $tab){
                if($tab == $documentName){
                    $isChild = false;
                }
            }
            $documentDOCX = new Template();
            $documentDOCX->open(__DIR__."/templates/".$documentName."_data.docx");
            $fieldsInDocument = $documentDOCX->getVariables();
            $fieldsAndType = array();
            foreach ($fieldsInDocument as $field){
                $text = $field;
                $text =str_replace("{","",$text);
                $text = str_replace("}","",$text);
                $text = explode("_",$text);
                $type = $text[0];
                $name = $text[1];
                $fieldsAndType[$name] = $type;
            }
            $parentUmowa = $rboUmowy->get_record($this->get_module_variable('forRecord'));
            $parentRecord = $rboSubItems->get_records(array('childType' => $this->get_module_variable("parentType"),
            'id_umowy'=>$this->get_module_variable('forRecord') ),array(),array());
            foreach($parentRecord as $r){
                $parentRecord = $r;break;
            }
            foreach($fieldsAndType as $name => $type) {
               $name = strtolower($name);
                if($name == "farmer" && $type != "parent"){
                    $crits = array();
                    $fcallback = array('umowyCommon','contact_format_company');
                    $form->addElement('autoselect', 'farmer', __('farmer'), array(),
                                    array(array('umowyCommon','autoselect_company'), array($crits, $fcallback)), $fcallback);
                }
                else if (($name == "trader" || $name == "pelnomocnik2") && $type != "parent"){
                    $crits = array();
                    $fcallback = array('umowyCommon','contact_format_contact');
                    $form->addElement('autoselect', $name, __($name), array(),
                                    array(array('umowyCommon','autoselect_contacts'), array($crits, $fcallback)), $fcallback);
                }
                else if($name == "pelnomocnik1" && $type != "parent"){
                    $form->addElement('hidden', $name, __($name), array('id' => $name ));
                }
                else if($name == "number" && $type == "parent"){
                    $form->addElement('text', $name, __($name), array('id' => $name , 'value' => $parentUmowa['number']));
                    $form->freeze(array($name));
                }
                else if($name == "number" && $type == "text"){
                  /*  $number ="";
                    $year = date("Y");
                    $records_count = $rboUmowy->get_records_count(array("~number" => "%".$year."%", 'type' => $documentName ));
                    if($year == "2019"){
                        $number = ($records_count + $this->offsets[$documentName])."/".$year;
                    }else{
                        $number = ($records_count + 1)."/".$year;
                    }*/
                    $year = date("Y");
                    $records_count = $rboUmowy->get_records_count(array("~number" => "%".$year."%" ));
                    if($year == "2019"){
                        $number = ($records_count + $this->offset)."/".$year;
                    }else{
                        $number = ($records_count + 1)."/".$year;
                    }

                    $form->addElement($type, $name, __($name), array('id' => $name , 'value' => $number ));
                }
                else if(preg_match("/date+[a-zA-Z]+/",$name) && $type == "parent"){
                        $value = $parentRecord[$name];
                        $value1 = Base_RegionalSettingsCommon::time2reg($value,false,true,true,true);
                        $form->addElement('text', $name, __($name), array('id' => $name , 'value' => $value1 ));
                        $form->freeze(array($name));
                }
                else if($type == "parent"){
                    if($name == "trader" || $name == "pelnomocnik2"){
                        $id = $parentRecord[$name];
                        $parentRecord[$name] = "[$id] ".$this->getTraderName($id);
                        $form->addElement('text', $name, __($name), array('id' => $name , 'value' => $parentRecord[$name] ));
                        $form->freeze(array($name));
                    }
                    else if($name == "farmer"){
                        $id = $parentRecord[$name];
                        $parentRecord[$name] = "[$id] ".$this->getFarmerName($id);
                        $form->addElement('text', $name, __($name), array('id' => $name , 'value' => $parentRecord[$name] ));
                        $form->freeze(array($name));
                    }
                    else if($name == "farmeraddresswork"){
                        // farmer 
                        $address = "";
                        $farmer = Utils_RecordBrowserCommon::get_record("contact", $parentRecord['farmer']);
                        if(strlen($farmer['agreementadress']) > 0){
                            $address = $farmer['agreementadress'];
                        }
                        else{
                            $address .= $parentRecord['farmeraddress'].", ";
                            $address .= $parentRecord['farmerPostalCode']." ";
                            $address .= $parentRecord['farmercity'];
                        }

                        $form->addElement('text', $name, __($name), array('id' => $name , 'value' => $parentRecord['farmeraddresswork'] ));
                        $form->freeze(array($name));
                    }
                    else if($name == "farmercitywork"){
                         $address = "";
                         $address .= $parentRecord['farmeraddress'].", ";
                         $address .= $parentRecord['farmerPostalCode']." ";
                         $address .= $parentRecord['farmercity'];       

                        $form->addElement('text', $name, __($name), array('id' => $name , 'value' => $address ));
                        $form->freeze(array($name));
                    }

                    else{
                        $form->addElement('text', $name, __($name), array('id' => $name , 'value' => $parentRecord[$name] ));
                        $form->freeze(array($name));
                    }
                }
                else{
                    if($type == "number"){
                        $type = "text";
                    }
                    if($type == 'datepicker'){
                        $form->addElement($type, $name, __($name), array('id' => $name ));
                    } else{
                        $form->addElement($type, $name, __($name), array('id' => $name ));
                    }
                }
            }
            $form->addElement("text","rzuty", "Ilość rzutów", array('value'=> '1'));
            $form->addElement("hidden","documentType",$documentName);
            $form->addElement("submit","submit","Dodaj");
            $form->toHtml();
            $form->assign_theme('my_form', $theme);
            load_js($this->get_module_dir().'js/dynamicFields.js');
            $theme->display();
        }
    }
        return $status;
    }

    public function addNewFromExist($id){
        if($this->is_back()) return false;
        require_once 'HtmlView.php';
        require_once 'Template.php';
        $status = true;
        $form = & $this->init_module('Libs/QuickForm');
        $rboUmowy = new RBO_RecordsetAccessor("umowy");
        if($form->validate_with_message("Dodano","Coś poszło nie tak, sprawdź poprawność danych")) {
            $submitedValues = $form->getSubmitValues();
            $main = $rboUmowy->get_record($id);
            $fieldsForNewUmowa = array();
            $fieldsForSubUmowa = array();
            $fieldsForNewUmowa['rzuty'] = $main['rzuty'];
            foreach ($submitedValues as $key => $item) {
                $key = strtolower($key);
                if ($key != "submited" || $key != "submit" || $key != "__action_module__") {
                    //get table
                    if ($key == "number") {
                        $fieldsForNewUmowa[$key] = $item;
                        $fieldsForNewUmowa['status'] = 2;
                        $fieldsForNewUmowa['type'] = $submitedValues['documentType'];
                    } else {
                        if (preg_match("/date+[a-zA-Z]+/", $key)) {
                            $item = Base_RegionalSettingsCommon::reg2time($item);
                            $fieldsForSubUmowa[$key] = $item;

                        } else {
                            $fieldsForSubUmowa[$key] = $item;
                        }
                    }
                }
            }
            $rboSubItems = new RBO_RecordsetAccessor("umowy_extend");
            $newUmowa = $rboUmowy->new_record($fieldsForNewUmowa);
            $newUmowa->save();
            $fieldsForSubUmowa['id_umowy'] = $newUmowa->id;
            $fieldsForSubUmowa['childType'] = $submitedValues['documentType'];
            $newSubUmoaw = $rboSubItems->new_record($fieldsForSubUmowa);
            $newSubUmoaw->save();
            $this->set_module_variable("view", 'details');
            $this->set_module_variable("forRecord", $newUmowa->id);
            $status = false;
        }
        if($status) {
            $rboUmowy = new RBO_RecordsetAccessor("umowy_extend");
            $existedUmowa = $rboUmowy->get_record($id);
            $htmlElements = new HtmlView();
            $theme = $this->init_module('Base/Theme');
            load_css($this->get_module_dir().'theme/default.css');
            $theme->assign("header", "Nowa umowa na podstawie istniejącej");
            Base_ActionBarCommon::add(
                'back',
                'Wróć',
                $this->create_back_href(),
                null,
                0
            );

            $theme->assign("documentType", $this->getTableDisplayName($existedUmowa['childType']));
            $documentName = $existedUmowa['childType'];
            $documentDOCX = new Template();
            $documentDOCX->open(__DIR__."/templates/" . $documentName . "_data.docx");
            $fieldsInDocument = $documentDOCX->getVariables();
            $fieldsAndType = array();
            foreach ($fieldsInDocument as $field) {
                $text = $field;
                $text = str_replace("{", "", $text);
                $text = str_replace("}", "", $text);
                $text = explode("_", $text);
                $type = $text[0];
                $name = $text[1];
                $fieldsAndType[$name] = $type;
            }
            $defaults = [];
            foreach ($fieldsAndType as $name => $type) {
                $name = strtolower($name);
                if ($name == "farmer") {
                    $crits = array();
                    $fcallback = array('umowyCommon', 'contact_format_company');
                    $form->addElement('autoselect', 'farmer', __('farmer'), array($existedUmowa['farmer'] => $this->getFarmerName($existedUmowa['farmer'])), array(array('umowyCommon', 'autoselect_company'), array($crits, $fcallback)), $fcallback);
                    $form->setDefaults(array('farmer' => $existedUmowa['farmer']));
                } else if ($name == "trader" || $name == "pelnomocnik2") {
                    $crits = array();
                    $fcallback = array('umowyCommon', 'contact_format_contact');
                    $form->addElement('autoselect', $name, __($name), array($existedUmowa[$name] => $this->getTraderName($existedUmowa[$name])), array(array('umowyCommon', 'autoselect_contacts'), array($crits, $fcallback)), $fcallback);
                    $form->setDefaults(array($name => $existedUmowa[$name]));
                } else if ($name == "number") {
                    $number = "";
                    $year = date("Y");
                    $records_count = Utils_RecordBrowserCommon::get_records_count("umowy", array("~number" => "%" . $year . "%", 'type' => $documentName));
                    if ($year == "2019") {
                        $number = ($records_count + $this->offset) . "/" . $year;
                    } else {
                        $number = ($records_count + 1) . "/" . $year;
                    }
                    $form->addElement($type, $name, __($name), array('id' => $name, 'value' => $number));
                } else {
                    if ($type == "number") {
                        $type = "text";
                    }
                    if ($type == 'datepicker') {
                        $form->addElement($type, $name, __($name), array('id' => $name));
                        $defaults[$name] = Base_RegionalSettingsCommon::time2reg($existedUmowa[$name], false, true, true, true);
                    } else {
                        $form->addElement('text', $name, __($name), array('id' => $name, 'value' => $existedUmowa[$name]));
                    }
                }
            }
            $form->addElement("hidden", "documentType", $documentName);
            $form->addElement("submit", "submit", "Dodaj");
            $form->toHtml();
            $form->assign_theme('my_form', $theme);
            $theme->display();
            load_js($this->get_module_dir() . 'js/dynamicFields.js');
            foreach ($defaults as $key => $value) {
                epesi::js("
            jq('#$key').val('$value');
            ");
            }
        }

        return $status;
    }

    public function recordEdit($id){
        if($this->is_back()) return false;
        require_once 'HtmlView.php';
        require_once 'Template.php';
        $rboUmowy = new RBO_RecordsetAccessor("umowy_extend");
        $existedUmowa = $rboUmowy->get_record($id);
        $rboMain = new RBO_RecordsetAccessor("umowy");
        $main =  $rboMain->get_record($existedUmowa['id_umowy']);
        $form = & $this->init_module('Libs/QuickForm');
        $htmlElements = new HtmlView();
        $theme = $this->init_module('Base/Theme');
        load_css($this->get_module_dir().'theme/default.css');
        $theme->assign("header", "Nowa umowa na podstawie istniejącej");
        Base_ActionBarCommon::add(
            'back',
            'Wróć',
            $this->create_back_href(),
            null,
            0
        );

        $form = & $this->init_module('Libs/QuickForm');
        $htmlElements = new HtmlView();
        $theme = $this->init_module('Base/Theme');
        load_css($this->get_module_dir().'theme/default.css');
        $theme->assign("header", "Edycja  ". $existedUmowa->childType. " ".$main['number']);

        $theme->assign("documentType", $this->getTableDisplayName($existedUmowa['childType']));
      //  print_r($existedUmowa);
        //test path
        $documentName = $existedUmowa['childType'];
        $documentDOCX = new Template();
        $documentDOCX->open(__DIR__."/templates/".$documentName."_data.docx");
        $fieldsInDocument = $documentDOCX->getVariables();
        $fieldsAndType = array();
        foreach ($fieldsInDocument as $field){
            $text = $field;
            $text =str_replace("{","",$text);
            $text = str_replace("}","",$text);
            $text = explode("_",$text);
            $type = $text[0];
            $name = $text[1];
            $fieldsAndType[$name] = $type;
        }
        $defaults = [];
        foreach($fieldsAndType as $name => $type) {
            $name = strtolower($name);
            if($name == "farmer"){
                $crits = array();
                $fcallback = array('umowyCommon','contact_format_company');
                $form->addElement('autoselect', 'farmer', __('farmer'), array($existedUmowa['farmer'] => $this->getFarmerName($existedUmowa['farmer'])), array(array('umowyCommon','autoselect_company'), array($crits, $fcallback)), $fcallback);
                $form->setDefaults(array('farmer' => $existedUmowa['farmer']));
            }
            else if ($name == "trader" || $name == "pelnomocnik2"){
                $crits = array();
                $fcallback = array('umowyCommon','contact_format_contact');
                $form->addElement('autoselect', $name, __($name), array($existedUmowa[$name] => $this->getTraderName($existedUmowa[$name])), array(array('umowyCommon','autoselect_contacts'), array($crits, $fcallback)), $fcallback);
                $form->setDefaults(array($name=> $existedUmowa[$name]));
            }
            else{
                if($type == "number"){
                    $type = "text";
                }
                if($type == 'datepicker'){
                    $form->addElement($type, $name, __($name), array('id' => $name ));
                    $defaults[$name] = Base_RegionalSettingsCommon::time2reg($existedUmowa[$name],false,true,true,true);
                }
                else if($name == "number"){
                    $form->addElement($type, $name, __($name), array('id' => $name , 'value' => $main[$name]));
                    $form->freeze(array($name));
                }else{
                    $form->addElement("text", $name, __($name), array('id' => $name , 'value' => $existedUmowa[$name]));
                }
            }
        }
        $form->addElement("hidden","documentType",$documentName);
        $form->addElement("submit","submit","Edytuj");
        $form->toHtml();
        $form->assign_theme('my_form', $theme);
        $theme->display();
        load_js($this->get_module_dir().'js/dynamicFields.js');
        foreach($defaults as $key => $value){
            epesi::js("
                jq('#$key').val('$value');
                ");
        }
        if($form->validate_with_message("Edytowano","Coś poszło nie tak, sprawdź poprawność danych")) {
            $submitedValues = $form->getSubmitValues();
            $fieldsForSubUmowa = array();
            foreach($submitedValues as $key => $item){
                $key = strtolower($key);
                if($key != "submited" || $key != "submit" || $key != "__action_module__"){
                    if(preg_match("/date+[a-zA-Z]+/",$key)){
                        $fieldsForSubUmowa[$key] = $item;

                    }else {
                        $fieldsForSubUmowa[$key] = $item;
                    }
                }
            }
        }
        Utils_RecordBrowserCommon::update_record('umowy_extend', $id , $fieldsForSubUmowa , $full_update=false, $date=null, $dont_notify=false);
        return true;
    }
    public function recordDelete($id,$table){
       if($table == "umowy"){
            $subUmowy = Utils_RecordBrowserCommon::get_records("umowy_extend",array("id_umowy"=>$id),array(),array());
            foreach($subUmowy as $umowa){
                Utils_RecordBrowserCommon::delete_record("umowy_extend",$umowa['id']);
            }
            Utils_RecordBrowserCommon::delete_record($table,$id);
        }else{
            Utils_RecordBrowserCommon::delete_record($table,$id);
       }
    }

    //  sudo apt-get install libreoffice
    //  pip3 install unoconv



    public function downloadWord($id){
       $href = 'href="modules/umowy/word.php?'.http_build_query(array('umowaID'=> $id , 'cid'=>CID)).'" target="_blank"';
       return $href;
    }
    public function downloadBlankWord($id,$document){
        $href = 'href="modules/umowy/word.php?'.http_build_query(array('umowaID'=> $id , 'cid'=>CID,'document' => $document)).'" target="_blank"';
        return $href;
     }

    public function getFarmerName($id){
        $farmer = Utils_RecordBrowserCommon::get_record("company",$id);
        $farmerName = "";

        $farmerName = preg_replace('/TN/', '', $farmer['company_name']);
        $farmerName = preg_replace('/[0-9]/', '', $farmer['company_name']); 
        return $farmerName;
    }

    public function  getTraderName($id){
        $trader = Utils_RecordBrowserCommon::get_record("contact",$id);
        return $trader['last_name']." ".$trader['first_name'];
    }

    public function showDetailsFor($display , $id){
        return "<a ".$this->create_href(array("view"=>"details","forRecord"=>$id)).">".$display."</a>";
    }

    public function createLink($display , $href){
        return "<a ".$href.">".$display."</a>";
    }

    public  function changeSort($sortType){
        if($sortType == "ASC"){
            return "DESC";
        }
        else{
            return "ASC";
        }
    }

    public function getTableDisplayName($table){
        $tables = $this->getAllTables();
        return $tables[$table];
    }
    public function getAllTables(){
        $tables = Utils_CommonDataCommon::get_array("Umowy/typyUmow");
        foreach ($tables as $key=>$value){
            $tables += Utils_CommonDataCommon::get_array("Umowy/typyUmow/$key");
        }
        return $tables;
    }
    public function isMainTable($find){
        $tables = $this->getTables();
        $isMain = false;
        foreach($tables as $key => $value){
            if($find == $key){
                $isMain = true;
            }
        }
        return $isMain;
    }


    public function getTables(){
        $tables = Utils_CommonDataCommon::get_array("Umowy/typyUmow");
        return $tables;
    }

    public function getChildTable($table){
        $tables = Utils_CommonDataCommon::get_array("Umowy/typyUmow/$table");
        return $tables;
    }

    public function readableDate($date){
        return Base_RegionalSettingsCommon::time2reg($date,false,true,true,true);
    }





}
