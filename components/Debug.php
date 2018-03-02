<?php

namespace app\components;

class Debug
{
    /*
     * класс для отладки
     *
     */
    public static function d($value=null,$text='Отладка',$type=1,$die=0,$class='',$style='')
    {
        $style = <<<STYLE
    	width: 100%;
    	margin: 0 auto;
    	background-color: #d0d0d0;
    	padding: 10px;
    	border: 1px solid #444;
    	border-radius: 5px;
    	border-color: #ccc;
    	font-family: sans-serif, arial;
STYLE;

        // выходная строка
        $str = "<div class='rs_debug' style='{$style}'>";
        switch ($type){
            case 1: $debug_funct_type = 'print_r'; break;
            case 2: $debug_funct_type = 'var_export'; break;
            default : $debug_funct_type = 'print_r';
        }

        ?>
        <?php
        // сохрение выходной строки с нужной отладочной функцией
        $str1 = <<<STR1
    	<p style='margin: 0;'>Debug text: <span style='color: red; '>$text</span></p>
    	<p style='margin: 0;' >Debug function: <span  style='color: red; '>{$debug_funct_type}</span></p>
STR1;

        if ($type === 1 ){ $pre = $debug_funct_type($value, true);}
        else if($type === 2 ){
            $pre = $debug_funct_type($value, true);
        }
        $pre = "\n<pre>$pre</pre>\n\n";
        $str .= $str1 . $pre . "</div>\n";

        return $str;
    }

}