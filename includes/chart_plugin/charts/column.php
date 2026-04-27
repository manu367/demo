<?php
// categories
// data dega
function column_count_Chart($link,$data=[]){
    $data= [
        [
            'name'=> 'Corn',
            "data"=> [387749, 280000, 129000, 64300, 54000, 34300]
        ],
        [
            "name"=>'Wheat',
            "data"=> [45321, 140000, 10000, 140500, 19500, 113500]
        ]
    ];
    return json_encode($data);
}

function column_sum_Chart($link,$data=[]){
    $data= [
        [
            'name'=> 'Corn',
            "data"=> [387749, 280000, 129000, 64300, 54000, 34300]
        ],
        [
            "name"=>'Wheat',
            "data"=> [45321, 140000, 10000, 140500, 19500, 113500]
        ]
    ];
    return json_encode($data);
}

function column_average_Chart($link,$data=[]){
    $data= [
        [
            'name'=> 'Corn',
            "data"=> [387749, 280000, 129000, 64300, 54000, 34300]
        ],
        [
            "name"=>'Wheat',
            "data"=> [45321, 140000, 10000, 140500, 19500, 113500]
        ]
    ];
    return json_encode($data);
}

function column_week_Chart($link,$data=[]){
    $data= [
        [
            'name'=> 'Corn',
            "data"=> [387749, 280000, 129000, 64300, 54000, 34300]
        ],
        [
            "name"=>'Wheat',
            "data"=> [45321, 140000, 10000, 140500, 19500, 113500]
        ]
    ];
    return json_encode($data);
}

function column_month_Chart($link,$data=[]){
    $data= [
        [
            'name'=> 'Corn',
            "data"=> [387749, 280000, 129000, 64300, 54000, 34300]
        ],
        [
            "name"=>'Wheat',
            "data"=> [45321, 140000, 10000, 140500, 19500, 113500]
        ]
    ];
    return json_encode($data);
}

function column_yearly_Chart($link,$data){
    $data= [
        [
            'name'=> 'Corn',
            "data"=> [387749, 280000, 129000, 64300, 54000, 34300]
        ],
        [
            "name"=>'Wheat',
            "data"=> [45321, 140000, 10000, 140500, 19500, 113500]
        ]
    ];
    return json_encode($data);
}
