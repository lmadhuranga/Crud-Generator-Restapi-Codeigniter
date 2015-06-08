<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


function p($a)
{
    echo '<pre>';
    print_r($a);
    echo '</pre>';

}
function v($a)
{
    echo '<pre>';
    var_dump($a);
    echo '</pre>';

}


function clean_header($array){
    $CI = get_instance();
    $CI->load->helper('inflector');
    foreach($array as $a){
        $arr[] = humanize($a);
    }
    return $arr;
}


function rm_tbl($tbl_name){
    $CI = get_instance();
    $CI->load->helper('codegen');
    $tbl_prefix = $CI->config->item('tbl_prefix');
    if($tbl_prefix!=FALSE){
        $length = strlen($tbl_prefix); 
        return substr($tbl_name,$length);    
    }
    
    return $tbl_name;
    
    
}