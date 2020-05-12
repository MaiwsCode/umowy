<?php
    defined("_VALID_ACCESS") || die('Direct access forbidden');

    Utils_CommonDataCommon::new_array("Umowy/typyUmow", array('cenastala' => 'Umowa cena stałą'));
    Utils_CommonDataCommon::new_array("Umowy/typyUmow", array('cenaminimalna' => 'Umowa cena minimalna'));
    Utils_CommonDataCommon::new_array("Umowy/typyUmow", array('generalna_umowa' => 'Generalna Umowa Sprzedaży Trzody Chlewnej'));
    Utils_CommonDataCommon::new_array("Umowy", array('zakonczenie_umowy' => '95'));
    Utils_CommonDataCommon::new_array("Umowy", array('notify_email' => ''));
