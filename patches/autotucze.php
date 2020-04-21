<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');

Utils_RecordBrowserCommon::new_record_field('umowy',
    array(
        'name' => _M('rzuty'),
        'type' => 'integer',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
    )
);

