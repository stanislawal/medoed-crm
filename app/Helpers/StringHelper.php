<?php

namespace App\Helpers;

class StringHelper
{
    public static function insertBreaks($str, $length = 10)
    {
        $result = '';
        $textLength = strlen($str);

        for ($i = 0; $i < $textLength; $i += $length) {
            // Получаем подстроку длиной $length
            $result .= substr($str, $i, $length);
            // Добавляем HTML элемент (например, <br>) если это не конец строки
            if ($i + $length < $textLength) {
                $result .= '<wbr>';
            }
        }

        return $result;
    }
}
