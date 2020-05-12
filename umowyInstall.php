<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class umowyInstall extends ModuleInstall {

    public function install() {
        // Here you can place installation process for the module

        Base_ThemeCommon::install_default_theme($this->get_type());

        Utils_CommonDataCommon::new_array("Umowy/status",
            array(0 => 'Odrzucony' , 1 => 'Zatwierdzony', 2 => 'Otwarty' , 3=> 'Czeka na akceptacje', 4=> "Ponownie otwarty"));

            $types = array(
                "UMOWA_doradztwo_hodowlano_zywieniowe_LEKARZ_ATH" => "UMOWA doradztwo hodowlano żywieniowe LEKARZ ATH",
                "UMOWA_doradztwo_hodowlano_zywieniowe_LEKARZ_ROLNIK" => "UMOWA doradztwo hodowlano żywieniowe LEKARZ ROLNIK",
                "Umowa_GRUZJA" => "Umowa GRUZJA",
                "Umowa_KREDYTOWA_BEZTERMINOWA" => "Umowa KREDYTOWA BEZTERMINOWA",
                "Umowa_NOWA_FORMULA_LEKARZ_ATH" => "Umowa NOWA FORMUŁA LEKARZ ATH",
                "Umowa_NOWA_FORMULA_LEKARZ_ROLNIK" => "Umowa NOWA FORMUŁA LEKARZ ROLNIK",
            );

        Utils_CommonDataCommon::new_array("Umowy/typyUmow", $types);

        $childs = array(
            "" => "---",
            "Umowa_PORECZENIA_dla_nowa_formula" => "Umowa PORĘCZENIA dla nowa formuła" ,
            "ZALACZNIK_dla_nowa_formula" => "ZAŁĄCZNIK dla nowa formuła",
        );
        Utils_CommonDataCommon::new_array("Umowy/typyUmow/Umowa_NOWA_FORMULA_LEKARZ_ATH", $childs);
        Utils_CommonDataCommon::new_array("Umowy/typyUmow/Umowa_NOWA_FORMULA_LEKARZ_ROLNIK", $childs);

        $childs = array(
            "" => "---",
            "Umowa_PORECZENIA_dla_umowy_o_doradztwo" => "Umowa PORĘCZENIA dla umowy o doradztwo",
            "Umowa_PRZEWLASZCZENIA_dla_umowy_o_doradztwo" => "Umowa PRZEWŁASZCZENIA dla umowy o doradztwo",
            "ZALACZNIK_dla_umowy_o_doradztwo" => "ZAŁĄCZNIK dla umowy o doradztwo"
        );
        Utils_CommonDataCommon::new_array("Umowy/typyUmow/UMOWA_doradztwo_hodowlano_zywieniowe_LEKARZ_ATH", $childs);
        Utils_CommonDataCommon::new_array("Umowy/typyUmow/UMOWA_doradztwo_hodowlano_zywieniowe_LEKARZ_ROLNIK", $childs);
        $table = new umowy_umowy();
        $table->install();
        $table->add_default_access();

       /* $table =  new umowy_nowa_formula();
        $table->install();
        $table->add_default_access();
        $table = new umowy_nowa_formula_poreczenie();
        $table->install();
        $table->add_default_access();
        $table = new umowy_nowa_formula_zalacznik();
        $table->install();
        $table->add_default_access();
        $table = new umowy_doradztwo_hodowlano_zywieniowe();
        $table->install();
        $table->add_default_access();
        $table = new umowy_doradztwo_przewlaszczenie();
        $table->install();
        $table->add_default_access();
        $table = new umowy_kupno_sprzedaz_trzoda();
        $table->install();
        $table->add_default_access();
        $table = new umowy_poreczenie_doradztwo();
        $table->install();
        $table->add_default_access();
        $table = new umowy_umowa_warchlak_gruzja();
        $table->install();
        $table->add_default_access();
        $table = new umowy_zakup_warchlakow();
        $table->install();
        $table->add_default_access();
        $table = new umowy_zalacznik2_wbc();
        $table->install();
        $table->add_default_access();
        $table = new umowy_zalacznik_3_do_umowy_ramowej();
        $table->install();
        $table->add_default_access();
        $table = new umowy_zalacznik_kupnosprzedaz_trzoda();
        $table->install();
        $table->add_default_access();
        $table = new umowy_zalacznik_do_przewlaszczenia();
        $table->install();
        $table->add_default_access();*/
       $table = new umowy_extend();
       $table->install();
       $table->add_default_access();
       $table = new umowy_comments();
       $table->install();
       $table->add_default_access();

        $ret = true;
        return $ret; // Return false on success and false on failure
    }

    public function uninstall() {
        // Here you can place uninstallation process for the module

        $table = new umowy_umowy();
        $table->uninstall();
        $table = new umowy_comments();
        $table->uninstall();
        /*$table = new umowy_nowa_formula();
        $table->uninstall();
        $table = new umowy_nowa_formula_poreczenie();
        $table->uninstall();
        $table = new umowy_nowa_formula_zalacznik();
        $table->uninstall();
        $table = new umowy_doradztwo_hodowlano_zywieniowe();
        $table->uninstall();
        $table = new umowy_doradztwo_przewlaszczenie();
        $table->uninstall();
        $table = new umowy_kupno_sprzedaz_trzoda();
        $table->uninstall();
        $table = new umowy_poreczenie_doradztwo();
        $table->uninstall();
        $table = new umowy_umowa_warchlak_gruzja();
        $table->uninstall();
        $table = new umowy_zakup_warchlakow();
        $table->uninstall();
        $table = new umowy_zalacznik2_wbc();
        $table->uninstall();
        $table = new umowy_zalacznik_3_do_umowy_ramowej();
        $table->uninstall();
        $table = new umowy_zalacznik_kupnosprzedaz_trzoda();
        $table->uninstall();
        $table = new umowy_zalacznik_do_przewlaszczenia();
        $table->uninstall();*/
        $table = new umowy_extend();
        $table->uninstall();
        Utils_CommonDataCommon::remove("Umowy/typyUmow");


        $ret = true;
        return $ret; // Return false on success and false on failure
    }

    public function requires($v) {
        // Returns list of modules and their versions, that are required to run this module
        return array(); 
    }
    public function info() { // Returns basic information about the module which will be available in the epesi Main Setup
		return array (
				'Author' => 'Mateusz Kostrzewski',
				'License' => 'MIT 1.0',
				'Description' => '' 
		);
	}
    public function version() {

        return array('1.0'); 
    }
    public function simple_setup() { // Indicates if this module should be visible on the module list in Main Setup's simple view
		return array (
				'package' => __ ( 'Umowy' ),
				'version' => '1.0' 
		); // - now the module will be visible as "HelloWorld" in simple_view
	}

}

?>