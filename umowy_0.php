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
            $this->set_module_variable('forRecord', $_REQUEST['forRecord']);
        }

        if(isset($_REQUEST['__jump_to_RB_table'])){
            $rs = new RBO_RecordsetAccessor($_REQUEST['__jump_to_RB_table']);
            $rb = $rs->create_rb_module ( $this );
            $this->display_module ( $rb);
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
        Base_ThemeCommon::install_default_theme($this->get_type());
        Base_ThemeCommon::load_css('umowy','default');
        Base_LangCommon::install_translations('umowy');
        if($_REQUEST['action'] == 'sortBy'){
            $crits = $this->get_module_variable("sortCrits");
            $crits[$_REQUEST['field']] = $_REQUEST['type'];
            $this->set_module_variable("sortCrits", $crits);
            $this->set_module_variable($_REQUEST['field']."Sort", $this->changeSort($_REQUEST['type']));
            print("sort".$_REQUEST['field']);
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
               /* array("select", "farmer", "Rolnik", array()),*/
                array("datepicker","datestart","Data rozpoczęcia"),
              /*  array("select",'trader', "Handlowiec", array()),*/
                array('text','farmerAddress', "Adres rolnika"));

            $htmlElements = new Browser($this->init_module('Libs/QuickForm'), $fields);
            $htmlElements->display($this->create_href(array('action'=>'clearBrowser')));
            $this->set_module_variable("parentType", 'Null');
            $umowy = array();
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
                $rboExpandUmowy = $rboUmowyExpand->get_records($search,array(),array());
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
                $umowy = $rboUmowy->get_records($mainCrits, array(), array());
                $this->set_module_variable("searchMain",$mainCrits);
            }else {
                $umowy = $rboUmowy->get_records(array(), array(), $crits);
            }
            $gb = &$this->init_module('Utils/GenericBrowser', null, '');
            $gb->set_table_columns(
                array(
                    array('name' => '', 'width' => 5),
                    array('name' => '<a ' . $this->create_href(
                        array('action' => 'sortBy', 'field' => 'type', 'type' => $this->get_module_variable("typeSort"))) . '  >Typ </a>', 'width' => 20),
                    array('name' => '<a ' . $this->create_href(
                        array('action' => 'sortBy', 'field' => 'number', 'type' => $this->get_module_variable("numberSort"))) . ' >Numer </a>', 'width' => 20),
                    array("name" => '<a ' . $this->create_href(
                            array('action' => 'sortBy', 'field' => 'farmer',
                                'type' => $this->get_module_variable("farmerSort"))) . ' >  Rolnik </a>', 'width' => 20),
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
                    $umowa->get_val("type"),
                    $umowa->get_val("number"),
                    $farmerName,
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
                           $tr1 .= "<td style='padding-left:30px;background-color:#80d9f7;border-bottom:1px solid #4db3d6;'>".__($key)."</td><td style='text-align:left;padding-left:30px;border-left:1px solid #2a7189;'>".$time."</td>";
                        }
                        else{
                            $tr1 .= "<td style='padding-left:30px;background-color:#80d9f7;border-bottom:1px solid #4db3d6;'>".__($key)."</td><td style='text-align:left;padding-left:30px;border-left:1px solid #2a7189;'>".$value."</td>";
                        }
                        $tr1 .= "</tr>";
                    }
                }
                $tableName = $this->getTableDisplayName($subUmowa["childType"]);
                if(!strlen($tableName)) $tableName = $this->getTableDisplayName($umowa->type);
                $del = "";
                $edit = "";
                if($umowa->status == 2 || $umowa->status == 4) {
                    $del = "<a " . $this->create_confirm_callback_href("Na pewno usunąć tą umowę?", array($this, "recordDelete"), array($subUmowa['id'], "umowy_extend")) . ">" . $del_btn . "</a>";
                    $edit = $this->createLink($edit_btn, $this->create_callback_href(array($this, "recordEdit"), array($subUmowa->id)));
                }
                print("<tr ><th style='width:8%;'>".
                $edit." ".
                $this->createLink($createByExist,$this->create_callback_href(array($this,"addNewFromExist"),
                        array($subUmowa->id)))." ".
                $this->createLink($word_btn,$this->create_callback_href(array($this,"downloadWord"),
                        array($subUmowa->id)))." ".
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
                break;
            case "accept":
                $umowa = $rboUmowy->get_record($id);
                $umowa->status = '1';
                $umowa->save();
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

    public function addNew(){
        if($this->is_back()) return false;
        require_once 'HtmlView.php';
        require_once 'Template.php';

        $form = & $this->init_module('Libs/QuickForm');
        $htmlElements = new HtmlView();
        $theme = $this->init_module('Base/Theme');
        load_css($this->get_module_dir().'theme/default.css');
        $theme->assign("header", "Dodaj nową umowe");
        if($this->get_module_variable("parentType") == "Null"){
            print("<h3>Wybierz typ umowy: " .$htmlElements->selectListHref($this->getTables(),'', true, "Dalej",
                                                                "getType", $this->create_href(array('docType' => 'null'))));
        }else{
            print("<h3>Wybierz typ umowy: " .$htmlElements->selectListHref($this->getChildTable($this->get_module_variable("parentType")) ,'', true, "Dalej",
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
            $documentName = $_REQUEST['docType'];
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
            foreach($fieldsAndType as $name => $type) {
                $name = strtolower($name);
               if($type != "select") {
                   if($type == "parent") {
                       if($name == "number"){
                           $records = Utils_RecordBrowserCommon::get_records("umowy",array(),array(),array());
                           $umowy = ['----'];
                           foreach ($records as $umowa){
                               $umowy[$umowa['id']] = $umowa['number']." ". $this->getTableDisplayName($umowa['type']);
                           }
                           $form->addElement('select', 'parent', 'Wybierz umowę nadrzędną', $umowy,
                               array('id' => 'parent','class'=>'dynamic'));
                       }else {
                           $form->addElement('text', $name, __($name), array('id' => $name));
                       }
                   }else{
                       if($type == "number"){
                           $type = "text";
                       }
                        $form->addElement($type, $name, __($name), array('id' => $name));
                       // $f->addElement('automulti','farmer','Automulti test', array($this->get_type().'Common', 'automulti_search'), array('ble'), array($this->get_type().'Common', 'automulti_format'));
                   }
               }
               else{
                   if($name == "farmer"){
                       $rboCompany = new RBO_RecordsetAccessor("company");
                       $farmers = $rboCompany->get_records(array("group"=>'farmer'),array(),array());
                       $farmersArray = ['----'];
                       foreach ($farmers as $farmer){
                           $farmersArray[$farmer->id] = $farmer->company_name;
                       }
                       $form->addElement($type, $name, __($name),$farmersArray , array('class'=>'dynamic' , 'id' => $name));
                   }
                   else if($name == "trader"){
                       $rboContacts = new RBO_RecordsetAccessor("contact");
                       $traders = $rboContacts->get_records(array("group"=>'trader'),array(),array());
                       $tradersArray = ['----'];
                       foreach ($traders as $trader){
                           $tradersArray[$trader->id] = $trader->last_name." ".$trader->first_name;
                       }
                       $form->addElement($type, $name, __($name),$tradersArray, array('class'=>'dynamic', 'id'=>$name));
                   }
               }
            }
            $form->addElement("hidden","documentType",$documentName);
            $form->addElement("submit","submit","Dodaj");
            $form->toHtml();
            $form->assign_theme('my_form', $theme);
            $theme->display();
            load_js($this->get_module_dir().'js/dynamicFields.js');
            
        }
        if($form->validate_with_message("Dodano","Coś poszło nie tak, sprawdź poprawność danych")) {
            $submitedValues = $form->getSubmitValues();
            $fieldsForNewUmowa = array();
            $fieldsForSubUmowa = array();
            foreach($submitedValues as $key => $item){
                $key = strtolower($key);
                if($key != "submited" || $key != "submit" || $key != "__action_module__"){
                    //get table
                    if($key == "number"){
                        $fieldsForNewUmowa[$key]  = $item;
                        $fieldsForNewUmowa['status'] = 2;
                        $fieldsForNewUmowa['type'] = $submitedValues['documentType'];
                    }
                    else if($key == "farmer"){
                        $fieldsForSubUmowa[$key] = $this->getFarmerName($item);
                    }
                    else if($key == "trader"){
                        $fieldsForSubUmowa[$key] = $this->getTraderName($item);
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
            $rboUmowy = new RBO_RecordsetAccessor("umowy");
            $rboSubItems = new RBO_RecordsetAccessor("umowy_extend");
            if(!$submitedValues['parent']) {
                 $newUmowa = $rboUmowy->new_record($fieldsForNewUmowa);
                 $newUmowa->save();
                 $fieldsForSubUmowa['id_umowy'] = $newUmowa->id;
                 $fieldsForSubUmowa['childType'] = $submitedValues['documentType'];
                 $newSubUmoaw = $rboSubItems->new_record($fieldsForSubUmowa);
                 $newSubUmoaw->save();
            }else{
                $fieldsForSubUmowa['id_umowy'] = $submitedValues['parent'];
                $fieldsForSubUmowa['childType'] = $submitedValues['documentType'];
                $newSubUmoaw = $rboSubItems->new_record($fieldsForSubUmowa);
                $newSubUmoaw->save();
            }


        }

        return true;
    }

    public function addNewFromExist($id){
        if($this->is_back()) return false;
        require_once 'HtmlView.php';
        require_once 'Template.php';

        $rboUmowy = new RBO_RecordsetAccessor("umowy_extend");
        $existedUmowa = $rboUmowy->get_record($id);
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
        $theme->assign("header", "Umowa na podstawie ");

        $theme->assign("documentType", $this->getTableDisplayName($existedUmowa['childType']));
        print_r($existedUmowa);
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
        $defaults =[];
        foreach($fieldsAndType as $name => $type) {
            $name = strtolower($name);
            if($type != "select") {
                if($type == "parent") {
                    if($name == "number"){
                        $records = Utils_RecordBrowserCommon::get_records("umowy",array(),array(),array());
                        $umowy = ['----'];
                        foreach ($records as $umowa){
                            $umowy[$umowa['id']] = $umowa['number']." ". $this->getTableDisplayName($umowa['type']);
                        }
                        $form->addElement('select', 'parent', 'Wybierz umowe nadrzędną', $umowy, array('id' => 'parent','class'=>'dynamic'));
                        $form->setDefaults(array("parent" => $existedUmowa['id_umowy']));
                    }else {
                        $form->addElement('text', $name, __($name) , array('id' => $name, 'value' => $existedUmowa[$name]));
                    }
                }else{
                    if($type == "number"){
                        $type = "text";
                    }
                    if($type == "datepicker"){
                        $form->addElement($type, $name, __($name), array('id' => $name ));
                        $defaults[$name] = Base_RegionalSettingsCommon::time2reg($existedUmowa[$name],false,true,true,true);
                    }else{
                        $form->addElement($type, $name, __($name), array('id' => $name , 'value' => $existedUmowa[$name]));
                    }
                }
            }
            else{
                if($name == "farmer"){
                    $rboCompany = new RBO_RecordsetAccessor("company");
                    $farmers = $rboCompany->get_records(array("group"=>'farmer'),array(),array());
                    $farmersArray = ['----'];
                    $defaultFarmer = 0;
                    foreach ($farmers as $farmer){
                        $farmersArray[$farmer->id] = $farmer->company_name;
                        if($farmer->company_name == $existedUmowa['farmer']){
                            $defaultFarmer = $farmer->id;
                        }
                    }
                    $form->addElement($type, $name, __($name),$farmersArray , array('class'=>'dynamic' , 'id' => $name));
                    $form->setDefaults(array($name => $defaultFarmer));
                }
                else if($name == "trader"){
                    $rboContacts = new RBO_RecordsetAccessor("contact");
                    $traders = $rboContacts->get_records(array("group"=>'trader'),array(),array());
                    $tradersArray = ['----'];
                    $defaultTrader = 0;
                    foreach ($traders as $trader){
                        $tradersArray[$trader->id] = $trader->last_name." ".$trader->first_name;
                        if($trader->last_name." ".$trader->first_name == $existedUmowa['trader']){
                            $defaultTrader= $trader->id;
                        }
                    }
                    $form->addElement($type, $name, __($name),$tradersArray, array('class'=>'dynamic', 'id'=>$name));
                    $form->setDefaults(array($name=> $defaultTrader));
                }
            }
        }
        $form->addElement("hidden","documentType",$documentName);
        $form->addElement("submit","submit","Dodaj");
        $form->toHtml();
        $form->assign_theme('my_form', $theme);
        $theme->display();
        load_js($this->get_module_dir().'js/dynamicFields.js');
        foreach($defaults as $key => $value){
        epesi::js("
            jq('#$key').val('$value');
            ");
        }

        if($form->validate_with_message("Dodano","Coś poszło nie tak, sprawdź poprawność danych")) {
            $submitedValues = $form->getSubmitValues();
            $fieldsForNewUmowa = array();
            $fieldsForSubUmowa = array();
            foreach($submitedValues as $key => $item){
                $key = strtolower($key);
                if($key != "submited" || $key != "submit" || $key != "__action_module__"){
                    //get table
                    if($key == "number"){
                        $fieldsForNewUmowa[$key]  = $item;
                        $fieldsForNewUmowa['status'] = 2;
                        $fieldsForNewUmowa['type'] = $submitedValues['documentType'];
                    }
                    else if($key == "farmer"){
                        $fieldsForSubUmowa[$key] = $this->getFarmerName($item);
                    }
                    else if($key == "trader"){
                        $fieldsForSubUmowa[$key] = $this->getTraderName($item);
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
            $rboUmowy = new RBO_RecordsetAccessor("umowy");
            $rboSubItems = new RBO_RecordsetAccessor("umowy_extend");
            if(!$submitedValues['parent']) {
                 $newUmowa = $rboUmowy->new_record($fieldsForNewUmowa);
                 $newUmowa->save();
                 $fieldsForSubUmowa['id_umowy'] = $newUmowa->id;
                 $fieldsForSubUmowa['childType'] = $submitedValues['documentType'];
                 $newSubUmoaw = $rboSubItems->new_record($fieldsForSubUmowa);
                 $newSubUmoaw->save();
            }else{
                $fieldsForSubUmowa['id_umowy'] = $submitedValues['parent'];
                $fieldsForSubUmowa['childType'] = $submitedValues['documentType'];
                $newSubUmoaw = $rboSubItems->new_record($fieldsForSubUmowa);
                $newSubUmoaw->save();
            }


        }
        return true;
    }

    public function recordEdit($id){
        if($this->is_back()) return false;
        require_once 'HtmlView.php';
        require_once 'Template.php';

        $rboUmowy = new RBO_RecordsetAccessor("umowy_extend");
        $existedUmowa = $rboUmowy->get_record($id);
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
        $theme->assign("header", "Edycja  ". $existedUmowa->childType);

        $theme->assign("documentType", $this->getTableDisplayName($existedUmowa['childType']));
        print_r($existedUmowa);
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
            if($type != "select") {
                if($type == "parent") {
                    if($name == "number"){
                      /*  $records = Utils_RecordBrowserCommon::get_records("umowy",array(),array(),array());
                        $umowy = ['----'];
                        foreach ($records as $umowa){
                            $umowy[$umowa['id']] = $umowa['number']." ". $this->getTableDisplayName($umowa['type']);
                        }
                        $form->addElement('select', 'parent', 'Wybierz umowe nadrzędną', $umowy, array('id' => 'parent','class'=>'dynamic'));
                        $form->setDefaults(array("parent" => $existedUmowa['id_umowy']));*/
                    }else {
                      //  $form->addElement('text', $name, __($name) . "_parent", array('id' => $name, 'value' => $existedUmowa[$name]));
                    }
                }else{
                    if($type == "number"){
                        $type = "text";
                    }
                    if($type == 'datepicker'){
                        $form->addElement($type, $name, __($name), array('id' => $name ));
                        $defaults[$name] = Base_RegionalSettingsCommon::time2reg($existedUmowa[$name],false,true,true,true);
                    }else{
                        $form->addElement($type, $name, __($name), array('id' => $name , 'value' => $existedUmowa[$name]));
                    }
                }
            }
            else{
                if($name == "farmer"){
                    $rboCompany = new RBO_RecordsetAccessor("company");
                    $farmers = $rboCompany->get_records(array("group"=>'farmer'),array(),array());
                    $farmersArray = ['----'];
                    $defaultFarmer = 0;
                    foreach ($farmers as $farmer){
                        $farmersArray[$farmer->id] = $farmer->company_name;
                        if($farmer->company_name == $existedUmowa['farmer']){
                            $defaultFarmer = $farmer->id;
                        }
                    }
                    $form->addElement($type, $name, __($name),$farmersArray , array('class'=>'dynamic' , 'id' => $name));
                    $form->setDefaults(array($name => $defaultFarmer));
                }
                else if($name == "trader"){
                    $rboContacts = new RBO_RecordsetAccessor("contact");
                    $traders = $rboContacts->get_records(array("group"=>'trader'),array(),array());
                    $tradersArray = ['----'];
                    $defaultTrader = 0;
                    foreach ($traders as $trader){
                        $tradersArray[$trader->id] = $trader->last_name." ".$trader->first_name;
                        if($trader->last_name." ".$trader->first_name == $existedUmowa['trader']){
                            $defaultTrader= $trader->id;
                        }
                    }
                    $form->addElement($type, $name, __($name),$tradersArray, array('class'=>'dynamic', 'id'=>$name));
                    $form->setDefaults(array($name=> $defaultTrader));
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
                    //get table
                    if($key == "farmer"){
                        $fieldsForSubUmowa[$key] = $this->getFarmerName($item);
                    }
                    else if($key == "trader"){
                        $fieldsForSubUmowa[$key] = $this->getTraderName($item);
                    }
                    else{
                        if(preg_match("/date+[a-zA-Z]+/",$key)){
                            $date = __($item);
                            $date = date("Y-m-d",strtotime($date));
                            $fieldsForSubUmowa[$key] = $date;

                        }else {
                            $fieldsForSubUmowa[$key] = $item;
                        }
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

    public function downloadWord($id){
        require_once 'Template.php';
        $record = Utils_RecordBrowserCommon::get_record("umowy_extend",$id);
        $umowa = Utils_RecordBrowserCommon::get_record("umowy",$record['id_umowy']);
        $word = new Template();
        if(file_exists(__DIR__."/createdDocs/".$record['id']."_".$record['childtype'].".docx") && $umowa['status'] != 2 ){
            Epesi::redirect($_SERVER['document_root'].'/modules/umowy/createdDocs/'.$record['id']."_".$record['childtype'].".docx");
        }
        else{
            $record['number'] = $umowa['number'];
            $documentName = $record['childtype'];
            $word->open(__DIR__."/templates/".$documentName.".docx");
            foreach($record as $key => $item){
                $word->replace($key,$item);
            }
            $word->save(__DIR__.'/createdDocs/'.$record['id']."_".$record['childtype'].'.docx');
            Epesi::redirect($_SERVER['document_root'].'/modules/umowy/createdDocs/'.$record['id']."_".$record['childtype'].".docx");
        }
    }

    public function getFarmerName($id){
        $farmer = Utils_RecordBrowserCommon::get_record("company",$id);
        return $farmer['company_name'];
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
