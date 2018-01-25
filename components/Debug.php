<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 23.09.2017
 * Time: 20:40
 */

namespace app\components;


class Debug
{
    /*
     * класс для отладки
     *
     */

    // universal debuggin function
    public static function d($value, $string = 'отладка', $type=1)
    {
        $style = <<<st
width: 80%; margin: 0 auto; width: 80%;
margin: 0 auto;
background: #fff;
padding: 10px;
border: 1px solid #444;
border-radius: 10px;
margin-bottom: 10px;
margin-top: 10px; 
st;

    // выходная строка
    $style1 = '';
    $str = "\n\n<div class='rs_debug' style='{$style1}'>";
    switch ($type){
        case 1: $debug_funct_type = 'print_r'; break;
        case 2: $debug_funct_type = 'var_dump'; break;
        case 3: $debug_funct_type = 'var_export'; break;
        default : $debug_funct_type = 'print_r';
    }

    // сохрение выходной строки с нужной отладочной функцией
    $str .= "\nDebug: <span style='color: red; '>{$string}</span><br/>\n\n<pre>\n";
    if ($type === 1 ){$str .= $debug_funct_type($value, true);}
    else if($type === 2 ){
        $str .= $debug_funct_type($value);
        //ob_start();
        //ob_end_flush();
        //return $str;
    }
    $str .= "\n</pre>\n\n";
    $str .= "</div>\n\n";

    if ( defined('DEBUG_MODE') && DEBUG_MODE === 0 ){
        return '';
    }

    return $str;
    }

}
