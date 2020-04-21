<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 13.04.2019
 * Time: 19:37
 */

defined("_VALID_ACCESS") || die('Direct access forbidden');

class umowy_umowy extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $status = new RBO_Field_CommonData(_M('status'));
        $status->from('Umowy/status')->set_required()->set_visible();
        $type = new RBO_Field_CommonData(_M("type"));
        $type->from('Umowy/typyUmow')->set_required();
        $number = new RBO_Field_Text(_M("number"));
        $number->set_visible()->set_required()->set_length(40);
        $rzuty = new RBO_Field_Integer(_M("rzuty"));
        $rzuty->set_visible();

        return array($status,$type,$number,$rzuty); // - remember to return all defined fields


    }

}

class umowy_nowa_formula extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_nowa_formula';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_required()->set_length(10);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerFarm = new RBO_Field_Text(_M("farmerFarm"));
        $farmerFarm->set_visible()->set_required()->set_length(40);

        $farmerEmail = new RBO_Field_Text(_M("farmerEmail"));
        $farmerEmail->set_visible()->set_required()->set_length(40);

        $farmerNumbersOfPlace = new RBO_Field_Integer(_M("farmerNumbersOfPlace"));
        $farmerNumbersOfPlace->set_visible()->set_required();

        $trader = new RBO_Field_Text(_M("trader"));
        $trader->set_visible()->set_required()->set_length(40);

        $traderPhone = new RBO_Field_Text(_M("traderPhone"));
        $traderPhone->set_visible()->set_required()->set_length(18);

        $traderEmail = new RBO_Field_Text(_M("traderEmail"));
        $traderEmail->set_visible()->set_required()->set_length(40);

        $poreczyciel = new RBO_Field_Text(_M("poreczyciel"));
        $poreczyciel->set_visible()->set_required()->set_length(40);

        $kara = new RBO_Field_Text(_M("kara"));
        $kara->set_visible()->set_required()->set_length(30);

        $pelnomocnik1 = new RBO_Field_Text(_M("pelnomocnik1"));
        $pelnomocnik1->set_visible()->set_required()->set_length(40);

        $pelnomocnik2 = new RBO_Field_Text(_M("pelnomocnik2"));
        $pelnomocnik2->set_visible()->set_required()->set_length(40);

        $pelnomocnik2Pesel = new RBO_Field_Text(_M("pelnomocnik2Pesel"));
        $pelnomocnik2Pesel->set_visible()->set_required()->set_length(11);


        return array($umowa,$farmer,$dateStart,$farmerPesel,$farmerCity,$farmerPostalCode,$farmerAddress,$farmerFarm,$farmerEmail,$farmerNumbersOfPlace,
            $trader,$traderPhone,$traderEmail,$poreczyciel,$kara,$pelnomocnik1,$pelnomocnik2,$pelnomocnik2Pesel); // - remember to return all defined fields


    }

}


class umowy_nowa_formula_zalacznik extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_nowa_formula_zalacznik';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $pigAmount = new RBO_Field_Integer(_M("pigAmount"));
        $pigAmount->set_visible()->set_required();

        $pigWeight = new RBO_Field_Text(_M("pigWeight"));
        $pigWeight->set_visible()->set_required()->set_length(30);

        $pigPrice = new RBO_Field_Text(_M("pigPrice"));
        $pigPrice->set_visible()->set_required()->set_length(30);

        $dateEnd = new RBO_Field_Date(_M("dateEnd"));
        $dateEnd->set_required()->set_visible();

        return array($umowa,$dateStart,$farmerAddress,$pigAmount,$pigPrice,$pigWeight,$dateEnd); // - remember to return all defined fields


    }

}

class umowy_nowa_formula_poreczenie extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_nowa_formula_poreczenie';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $dateSigning = new RBO_Field_Date(_M("dateSigning"));
        $dateSigning->set_required()->set_visible();

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $pelnomocnik2 = new RBO_Field_Text(_M("pelnomocnik2"));
        $pelnomocnik2->set_visible()->set_required()->set_length(40);

        $pelnomocnik2Pesel = new RBO_Field_Text(_M("pelnomocnik2Pesel"));
        $pelnomocnik2Pesel->set_visible()->set_required()->set_length(11);

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $kara = new RBO_Field_Text(_M("kara"));
        $kara->set_visible()->set_required()->set_length(30);


        return array($umowa,$dateSigning,$farmerAddress,$farmerCity,$pelnomocnik2,$pelnomocnik2Pesel,$farmerPesel,$farmer,$dateStart,$kara); // - remember to return all defined fields


    }

}

class umowy_doradztwo_hodowlano_zywieniowe extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_doradztwo_hodowlano_zywieniowe';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_required()->set_length(10);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerFarm = new RBO_Field_Text(_M("farmerFarm"));
        $farmerFarm->set_visible()->set_required()->set_length(40);

        $farmerEmail = new RBO_Field_Text(_M("farmerEmail"));
        $farmerEmail->set_visible()->set_required()->set_length(40);

        $farmerBankNumber = new RBO_Field_Integer(_M("farmerBankNumber"));
        $farmerBankNumber->set_visible()->set_required();

        $farmerNrGosp = new RBO_Field_Text(_M("farmerNrGosp"));
        $farmerNrGosp->set_visible()->set_required()->set_length(40);

        $farmerNrGosp = new RBO_Field_Text(_M("farmerNrGosp"));
        $farmerNrGosp->set_visible()->set_required()->set_length(40);

        $farmerPigsCapacity = new RBO_Field_Integer(_M("farmerPigsCapacity"));
        $farmerPigsCapacity->set_visible()->set_required();

        $trader = new RBO_Field_Text(_M("trader"));
        $trader->set_visible()->set_required()->set_length(40);

        $traderPhone = new RBO_Field_Text(_M("traderPhone"));
        $traderPhone->set_visible()->set_required()->set_length(18);

        $traderEmail = new RBO_Field_Text(_M("traderEmail"));
        $traderEmail->set_visible()->set_required()->set_length(40);

        $kara = new RBO_Field_Text(_M("kara"));
        $kara->set_visible()->set_required()->set_length(30);

        $pelnomocnik1 = new RBO_Field_Text(_M("pelnomocnik1"));
        $pelnomocnik1->set_visible()->set_required()->set_length(40);

        $pelnomocnik2 = new RBO_Field_Text(_M("pelnomocnik2"));
        $pelnomocnik2->set_visible()->set_required()->set_length(40);

        $pelnomocnik2Pesel = new RBO_Field_Text(_M("pelnomocnik2Pesel"));
        $pelnomocnik2Pesel->set_visible()->set_required()->set_length(11);




        return array($umowa,$farmer,$dateStart,$farmerPesel,$farmerCity,$farmerPostalCode,$farmerAddress,$farmerFarm,$farmerEmail,
            $farmerBankNumber,$farmerNrGosp,$farmerPigsCapacity,$trader,$traderPhone,$traderEmail,$kara,$pelnomocnik1,$pelnomocnik2,$pelnomocnik2Pesel); // - remember to return all defined fields


    }

}

class umowy_poreczenie_doradztwo extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_poreczenie_doradztwo';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_required()->set_length(10);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerEmail = new RBO_Field_Text(_M("farmerEmail"));
        $farmerEmail->set_visible()->set_required()->set_length(40);

        $pelnomocnik2 = new RBO_Field_Text(_M("pelnomocnik2"));
        $pelnomocnik2->set_visible()->set_required()->set_length(40);

        $pelnomocnik2Pesel = new RBO_Field_Text(_M("pelnomocnik2Pesel"));
        $pelnomocnik2Pesel->set_visible()->set_required()->set_length(11);

        $kara = new RBO_Field_Text(_M("kara"));
        $kara->set_visible()->set_required()->set_length(30);


        return array($umowa,$farmer,$dateStart,$farmerPesel,$farmerCity,$farmerPostalCode,$farmerAddress,$farmerEmail,$pelnomocnik2,$pelnomocnik2Pesel,$kara); // - remember to return all defined fields


    }

}

class umowy_doradztwo_przewlaszczenie extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_doradztwo_przewlaszczenie';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_required()->set_length(10);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerCityWork = new RBO_Field_Text(_M("farmerCityWork"));
        $farmerCityWork->set_visible()->set_required()->set_length(30);

        $farmerAddressWork = new RBO_Field_Text(_M("farmerAddressWork"));
        $farmerAddressWork->set_visible()->set_required()->set_length(30);

        $pelnomocnik1 = new RBO_Field_Text(_M("pelnomocnik1"));
        $pelnomocnik1->set_visible()->set_required()->set_length(40);

        $pelnomocnik2 = new RBO_Field_Text(_M("pelnomocnik2"));
        $pelnomocnik2->set_visible()->set_required()->set_length(40);




        return array($umowa,$farmer,$dateStart,$farmerPesel,$farmerCity,$farmerPostalCode,$farmerAddress,$farmerCityWork,$farmerAddressWork,$pelnomocnik1,$pelnomocnik2); // - remember to return all defined fields


    }

}

class umowy_umowa_warchlak_gruzja extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_umowa_warchlak_gruzja';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_required()->set_length(10);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerCityWork = new RBO_Field_Text(_M("farmerCityWork"));
        $farmerCityWork->set_visible()->set_required()->set_length(30);

        $farmerAddressWork = new RBO_Field_Text(_M("farmerAddressWork"));
        $farmerAddressWork->set_visible()->set_required()->set_length(30);

        $farmerNip = new RBO_Field_Text(_M("farmerNip"));
        $farmerNip->set_visible()->set_required()->set_length(30);

        $pigsAmount = new RBO_Field_Integer(_M("pigsAmount"));
        $pigsAmount->set_required()->set_visible();

        $dateFrom = new RBO_Field_Date(_M("dateFrom"));
        $dateFrom->set_required()->set_visible();


        return array($umowa,$farmer,$dateStart,$farmerPesel,$farmerCity,$farmerPostalCode,$farmerAddress,$farmerCityWork,$farmerAddressWork,$farmerAddressWork,$farmerNip,$pigsAmount,$dateFrom); // - remember to return all defined fields
    }

}

class umowy_kupno_sprzedaz_trzoda extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_kupno_sprzedaz_trzoda';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerIdCard = new RBO_Field_Text(_M("farmerIdCard"));
        $farmerIdCard->set_visible()->set_required()->set_length(15);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_required()->set_length(10);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerEmail = new RBO_Field_Text(_M("farmerEmail"));
        $farmerEmail->set_visible()->set_required()->set_length(40);


        return array($umowa,$farmer,$dateStart,$farmerPesel,$farmerIdCard,$farmerCity,$farmerPostalCode,$farmerAddress,$farmerEmail); // - remember to return all defined fields


    }

}

class umowy_zalacznik_3_do_umowy_ramowej extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_zalacznik_3_do_umowy_ramowej';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $dateSigning = new RBO_Field_Date(_M("dateSigning"));
        $dateSigning->set_required()->set_visible();

        $dateEnd = new RBO_Field_Date(_M("dateEnd"));
        $dateEnd->set_required()->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_required()->set_length(10);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerEmail = new RBO_Field_Text(_M("farmerEmail"));
        $farmerEmail->set_visible()->set_required()->set_length(40);

        $pigsAmount = new RBO_Field_Integer(_M("pigsAmount"));
        $pigsAmount->set_required()->set_visible();

        $pigPrice = new RBO_Field_Text(_M("pigPrice"));
        $pigPrice->set_visible()->set_required()->set_length(20);

        $potracenie = new RBO_Field_Text(_M("potracenie"));
        $potracenie->set_visible()->set_required()->set_length(20);

        return array($umowa,$farmer,$dateStart,$dateSigning,$dateEnd,$farmerPesel,$farmerCity,
            $farmerPostalCode,$farmerAddress,$farmerEmail,$pigsAmount,$pigPrice,$potracenie); // - remember to return all defined fields


    }

}

class umowy_zalacznik_kupnosprzedaz_trzoda extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_zalacznik_kupnosprzedaz_trzoda';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        return array($umowa); // - remember to return all defined fields


    }

}

class umowy_zalacznik_do_przewlaszczenia extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_zalacznik_do_przewlaszczenia';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $pigsAmount = new RBO_Field_Integer(_M("pigsAmount"));
        $pigsAmount->set_required()->set_visible();


        return array($umowa,$farmer,$dateStart,$farmerPesel,$farmerCity,$pigsAmount); // - remember to return all defined fields


    }

}

class umowy_zalacznik2_wbc extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_zalacznik2_wbc';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_required()->set_length(10);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerEmail = new RBO_Field_Text(_M("farmerEmail"));
        $farmerEmail->set_visible()->set_required()->set_length(40);

        $farmerNip = new RBO_Field_Text(_M("farmerNip"));
        $farmerNip->set_visible()->set_required()->set_length(30);

        $cenaWBC = new RBO_Field_Text(_M("cenaWBC"));
        $cenaWBC->set_visible()->set_required()->set_length(30);

        $dateEnd = new RBO_Field_Date(_M("dateEnd"));
        $dateEnd->set_required()->set_visible();

        return array($umowa,$farmer,$dateStart,$farmerCity,$farmerPostalCode,$farmerAddress,$farmerEmail,$farmerNip,$cenaWBC,$dateEnd); // - remember to return all defined fields
    }

}

class umowy_zakup_warchlakow extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_zakup_warchlakow';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_required()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_required()->set_visible();

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_required()->set_length(30);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_required()->set_length(30);

        $farmerEmail = new RBO_Field_Text(_M("farmerEmail"));
        $farmerEmail->set_visible()->set_required()->set_length(40);

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_required()->set_length(11);

        $farmerCityWork = new RBO_Field_Text(_M("farmerCityWork"));
        $farmerCityWork->set_visible()->set_required()->set_length(30);

        $farmerAddressWork = new RBO_Field_Text(_M("farmerAddressWork"));
        $farmerAddressWork->set_visible()->set_required()->set_length(30);





        return array(); // - remember to return all defined fields


    }

}


class umowy_comments extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_comments';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required()->set_visible();

        $comment = new RBO_Field_LongText(_M("comment"));
        $comment->set_visible();

        return array($umowa,$comment); // - remember to return all defined fields


    }

}


class umowy_extend extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'umowy_extend';

    }
    function fields() { // - here you choose the fields to add to the record browser

        $umowa = new RBO_Field_Integer(_M("id_umowy"));
        $umowa->set_required();

        $umowaChildType = new RBO_Field_Text(_M("childType"));
        $umowaChildType->set_length(50)->set_visible();

        $farmer = new RBO_Field_Text(_M("farmer"));
        $farmer->set_visible()->set_length(40);

        $dateStart = new RBO_Field_Date(_M("dateStart"));
        $dateStart->set_visible();

        $dateSigning = new RBO_Field_Date(_M("dateSigning"));
        $dateSigning->set_visible();

        $dateFrom = new RBO_Field_Date(_M("dateFrom"));
        $dateFrom->set_visible();

        $dateEnd = new RBO_Field_Date(_M("dateEnd"));
        $dateEnd->set_visible();

        $farmerCity = new RBO_Field_Text(_M("farmerCity"));
        $farmerCity->set_visible()->set_length(30);

        $farmerNip = new RBO_Field_Text(_M("farmerNip"));
        $farmerNip->set_visible()->set_length(30);

        $farmerAddress = new RBO_Field_Text(_M("farmerAddress"));
        $farmerAddress->set_visible()->set_length(80);

        $farmerPostalCode = new RBO_Field_Text(_M("farmerPostalCode"));
        $farmerPostalCode->set_visible()->set_length(10);

        $farmerEmail = new RBO_Field_Text(_M("farmerEmail"));
        $farmerEmail->set_visible()->set_length(40);

        $farmerBankNumber = new RBO_Field_Text(_M("farmerBankNumber"));
        $farmerBankNumber->set_visible()->set_length(26);

        $farmerNrGosp = new RBO_Field_Text(_M("farmerNrGosp"));
        $farmerNrGosp->set_visible()->set_length(40);

        $farmerPigsCapacity = new RBO_Field_Integer(_M("farmerPigsCapacity"));
        $farmerPigsCapacity->set_visible();

        $farmerPesel = new RBO_Field_Text(_M("farmerPesel"));
        $farmerPesel->set_visible()->set_length(11);

        $farmerIdCard = new RBO_Field_Text(_M("farmerIdCard"));
        $farmerIdCard->set_visible()->set_length(15);

        $farmerCityWork = new RBO_Field_Text(_M("farmerCityWork"));
        $farmerCityWork->set_visible()->set_length(30);

        $farmerAddressWork = new RBO_Field_Text(_M("farmerAddressWork"));
        $farmerAddressWork->set_visible()->set_length(80);

        $pigAmount = new RBO_Field_Integer(_M("pigAmount"));
        $pigAmount->set_visible();

        $pigWeight = new RBO_Field_Text(_M("pigWeight"));
        $pigWeight->set_visible()->set_length(30);

        $pigPrice = new RBO_Field_Text(_M("pigPrice"));
        $pigPrice->set_visible()->set_length(30);

        $dateEnd = new RBO_Field_Date(_M("dateEnd"));
        $dateEnd->set_visible();

        $trader = new RBO_Field_Text(_M("trader"));
        $trader->set_visible()->set_length(40);

        $traderPhone = new RBO_Field_Text(_M("traderPhone"));
        $traderPhone->set_visible()->set_length(18);

        $traderEmail = new RBO_Field_Text(_M("traderEmail"));
        $traderEmail->set_visible()->set_length(40);

        $kara = new RBO_Field_Text(_M("kara"));
        $kara->set_visible()->set_length(30);

        $pelnomocnik1 = new RBO_Field_Text(_M("pelnomocnik1"));
        $pelnomocnik1->set_visible()->set_length(40);

        $pelnomocnik2 = new RBO_Field_Text(_M("pelnomocnik2"));
        $pelnomocnik2->set_visible()->set_length(40);

        $pelnomocnik2Pesel = new RBO_Field_Text(_M("pelnomocnik2Pesel"));
        $pelnomocnik2Pesel->set_visible()->set_length(11);

        $potracenie = new RBO_Field_Text(_M("potracenie"));
        $potracenie->set_visible()->set_length(20);

        $cenaWBC = new RBO_Field_Text(_M("cenaWBC"));
        $cenaWBC->set_visible()->set_length(30);



        return array($umowa,$umowaChildType, $farmer,$dateStart,$dateSigning,$dateFrom,$farmerCity,$farmerNip,$farmerAddress,$farmerPostalCode,$farmerEmail,
            $farmerBankNumber,$farmerNrGosp, $farmerPigsCapacity,$farmerPesel,$farmerIdCard,$farmerCityWork,$farmerAddressWork,
            $pigAmount,$pigWeight,$pigPrice,$dateEnd,$trader,$traderPhone,$traderEmail,$kara,$pelnomocnik1,$pelnomocnik2,$pelnomocnik2Pesel
            ,$potracenie,$cenaWBC); // - remember to return all defined fields


    }

}


?>