<?php

return [
    // Length of the codes
    'length' => 5,

    // characters used to generate psudo-random codes
    'character_pool' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_',

    // if it is null, we use our own package included model
    // make sure your custom model extends our package model
    'custom_model_name' => null,

    // the table name to store our codes
    'table_name' => 'codes',

    'generatedFor_returns_soft_deleted_models' => false,

];
