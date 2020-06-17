<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');

Utils_RecordBrowserCommon::new_record_field(
    'company',
    array(
        'name' => _M('agreement_new'),
        'type' => 'select',
        'param' => 'umowy::number',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
    )
);

Utils_RecordBrowserCommon::register_processing_callback(
    'umowy', 
    array('umowyCommon', 'changesRecord'));