<?php
$config = [
    'imageResizeValidation' => [
        [
            'field' => 'height',
            'label' => 'height',
            'rules' => 'required|numeric|trim|max_length[4]'
        ],
        [
            'field' => 'width',
            'label' => 'width',
            'rules' => 'required|numeric|trim|max_length[4]'  
        ],
        [
            'field' => 'img_url',
            'label' => 'image url',
            'rules' => 'trim|prep_url'  
        ]
    ]
    
    
];