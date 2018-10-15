<?php

return [
    'default_code_name' => 'default',

    // Length of the codes
    'length' => 5,

    // Characters used to generate psudo-random codes
    'character_pool' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_',

    // If it is null, we use "Code::class" make sure your
    // custom model extends our package model. Use fully
    // qualified namespace here: App\ModelName
    'custom_model' => null,

    // The table name to store our codes
    'table_name' => 'codes',

    // fetch soft-deleted rows?
    'generatedFor_returns_soft_deleted_models' => false,

];
