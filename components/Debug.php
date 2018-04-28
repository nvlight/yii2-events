<?php

namespace app\components;

use yii\helpers\Html;

class Debug
{
    /**
     * Универсальная функция отладки
     *
     * @param mixed   $value - входной параметр
     * @param string  $text  - сообщение отладки
     * @param integer $type  - тип отладки
     * @param integer $die   - останавливать ли скрипт после завершения
     * @param string  $class - класс родительского <div>, содержащая тег <pre>
     * @param string  $style -
     *
     * @return string - возвращает строку, которую можно вывести
     */
    public static function d($value=null,$text='Отладка',$type=1,$die=0,$class='',$style='')
    {
        if ($style === '')
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
        $str = "<div class='$class' style='{$style}'>";
        switch ($type){
            case 1: $debug_funct_type = 'print_r'; break;
            case 2: $debug_funct_type = 'var_dump'; break;
            case 3: $debug_funct_type = 'var_export'; break;
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
        elseif($type === 2 ){
            ob_start();
            $debug_funct_type($value);
            $pre = ob_get_contents();
            ob_end_clean();
        }
        elseif($type === 3){
            $pre = $debug_funct_type($value, true);
        }
        $pre = "\n<pre>$pre</pre>\n\n";
        $str .= $str1 . $pre . "</div>\n";





        //
        if ($type === 1 ){ $debug_result = $debug_funct_type($value, true);}
        elseif($type === 2 ){
            ob_start();
            $debug_funct_type($value);
            $debug_result = ob_get_contents();
            ob_end_clean();
        }
        elseif($type === 3){
            $debug_result = $debug_funct_type($value, true);
        }
        // <p style='margin: 0; font-size: 15px;'>Debug function: <span style='color: #805a74;'>$debug_funct_type</span></p>
        // <p style="margin: 0; font-size: 17px;">Debug text: <span style="color: #e31d2a;">{$text}</span></p>
        $p01 = <<<PO1
margin: 0; 
font-size: 17px;
color: #e31d2a;
PO1;

        $html = <<<PRE
<div class="" style="$style">
<pre>
{$debug_result}
</pre>
</div>
PRE;

        //return $html;
        return $str;
    }

}