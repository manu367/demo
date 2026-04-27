<?php
// categories
// data dega
function bar_count_Chart($link,$data=[]){
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
function bar_sum_Chart($link,$data=[]){
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
function bar_average_Chart($link,$data=[]){
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
function bar_weeek_Chart($link,$data=[]){
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
function bar_month_Chart($link,$data=[]){
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
function bar_yearly_Chart($link,$data=[]){
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
