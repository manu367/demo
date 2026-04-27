<?php


function line_count_Chart($link,$data=[]){
    $data=[
        [
            "name"=>"Installation & Developer",
            "data"=>[43934, 48656, 65165, 81827, 112143, 142383, 171533, 165174, 155157, 161454, 154610, 168960, 171558]
        ],
        [
            "name"=>"Manufacturing",
            "data"=>[24916, 37941, 29742, 29851, 32490, 30282, 38121, 36885, 33726, 34243, 31050, 33099, 33473]
        ],
        [
            "name"=>"Marketing",
            "data"=>[11744, 30000, 16005, 19771, 20185, 24377, 32147, 30912, 29243, 29213, 25663, 28978, 30618]
        ],
        [
            "name"=>"Operations",
            "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ]
    ];
    return json_encode($data);
}

function line_sum_Chart($link,$data=[]){
    $data=[
        [
            "name"=>"Installation & Developer",
            "data"=>[43934, 48656, 65165, 81827, 112143, 142383, 171533, 165174, 155157, 161454, 154610, 168960, 171558]
        ],
        [
            "name"=>"Manufacturing",
            "data"=>[24916, 37941, 29742, 29851, 32490, 30282, 38121, 36885, 33726, 34243, 31050, 33099, 33473]
        ],
        [
            "name"=>"Marketing",
            "data"=>[11744, 30000, 16005, 19771, 20185, 24377, 32147, 30912, 29243, 29213, 25663, 28978, 30618]
        ],
        [
            "name"=>"Operations",
            "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ]
    ];
    return json_encode($data);
}

function line_average_Chart($link,$data=[]){
    $data=[
        [
            "name"=>"Installation & Developer",
            "data"=>[43934, 48656, 65165, 81827, 112143, 142383, 171533, 165174, 155157, 161454, 154610, 168960, 171558]
        ],
        [
            "name"=>"Manufacturing",
            "data"=>[24916, 37941, 29742, 29851, 32490, 30282, 38121, 36885, 33726, 34243, 31050, 33099, 33473]
        ],
        [
            "name"=>"Marketing",
            "data"=>[11744, 30000, 16005, 19771, 20185, 24377, 32147, 30912, 29243, 29213, 25663, 28978, 30618]
        ],
        [
            "name"=>"Operations",
            "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ]
    ];
    return json_encode($data);
}

function line_weeek_Chart($link,$data=[]){
    $data=[
        [
            "name"=>"Monday",
            "data"=>[43934, 48656, 65165, 81827, 112143, 142383, 171533, 165174, 155157, 161454, 154610, 168960, 171558]
        ],
        [
            "name"=>"Tuesday",
            "data"=>[24916, 37941, 29742, 29851, 32490, 30282, 38121, 36885, 33726, 34243, 31050, 33099, 33473]
        ],
        [
            "name"=>"Wednesday",
            "data"=>[11744, 30000, 16005, 19771, 20185, 24377, 32147, 30912, 29243, 29213, 25663, 28978, 30618]
        ],
        [
            "name"=>"Thursday",
            "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ],
        [
            "name"=>"Friday",
            "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ],
        [
            "name"=>"Saturday",
            "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ],
        [
        "name"=>"Sunday",
        "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ]
    ];
    return json_encode($data);
}

function line_month_Chart($link,$data=[]){
    $data=[
        [
            "name"=>"April",
            "data"=>[43934, 48656, 65165, 81827, 112143, 142383, 171533, 165174, 155157, 161454, 154610, 168960, 171558]
        ],
        [
            "name"=>"May",
            "data"=>[24916, 37941, 29742, 29851, 32490, 30282, 38121, 36885, 33726, 34243, 31050, 33099, 33473]
        ],
        [
            "name"=>"jun",
            "data"=>[11744, 30000, 16005, 19771, 20185, 24377, 32147, 30912, 29243, 29213, 25663, 28978, 30618]
        ],
        [
            "name"=>"July",
            "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ]
    ];
    return json_encode($data);
}

function line_yearly_Chart($link,$data=[]){
    $data=[
        [
            "name"=>"2026",
            "data"=>[43934, 48656, 65165, 81827, 112143, 142383, 171533, 165174, 155157, 161454, 154610, 168960, 171558]
        ],
        [
            "name"=>"2025",
            "data"=>[24916, 37941, 29742, 29851, 32490, 30282, 38121, 36885, 33726, 34243, 31050, 33099, 33473]
        ],
        [
            "name"=>"2024",
            "data"=>[11744, 30000, 16005, 19771, 20185, 24377, 32147, 30912, 29243, 29213, 25663, 28978, 30618]
        ],
        [
            "name"=>"2023",
            "data"=>[null, null, null, null, null, null, null, null, 11164, 11218, 10077, 12530, 16585]
        ]
    ];
    return json_encode($data);
}