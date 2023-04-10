<?php
return [

    'COMPANY_EMAIL' => env('COMPANY_EMAIL', 'mietmanagement@yopmail.com'),
    'EXPERT_EMAIL' => env('EXPERT_EMAIL', 'mietmanagement1@yopmail.com'),
    'API_VERSION'=>'B.2.1.3',
    'ORDER_STATUS_LISTS' => env('ORDER_STATUS_LISTS', [
        ["id" => "1", "name" => "open"], 
        ["id" => "2", "name" => "in_progress"], 
        ["id" => "3", "name" => "completed"]
    ]),
];