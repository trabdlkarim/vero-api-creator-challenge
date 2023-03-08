<?php

return [
    'get constructionStages' => [
        'class' => 'ConstructionStages',
        'method' => 'getAll',
        'apidoc' => [
            'description' => "Get all the construction stages",
        ],
    ],
    'get constructionStages/(:num)' => [
        'class' => 'ConstructionStages',
        'method' => 'getSingle',
        'apidoc' => [
            'description' => "Get the specified construction stage",
            'param' => 'integer id - the resource id'
        ],
    ],
    'post constructionStages' => [
        'class' => 'ConstructionStages',
        'method' => 'post',
        'bodyType' => 'ConstructionStagesCreate',
        'apidoc' => [
            'description' => "Create a new construction stage",
        ],
    ],
    'patch constructionStages/(:num)' => [
        'class' => 'ConstructionStages',
        'method' => 'patch',
        'bodyType' => 'ConstructionStagesUpdate',
        'apidoc' => [
            'description' => "Update the specified construction stage",
            'param' => 'integer id - the resource id'
        ],
    ],
    'delete constructionStages/(:num)' => [
        'class' => 'ConstructionStages',
        'method' => 'delete',
        'apidoc' => [
            'description' => "delete the specified construction stage",
            'param' => 'integer id - the resource id'
        ],
    ],
];
