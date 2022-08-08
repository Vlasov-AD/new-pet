<?php

namespace yii\helpers;

class Inflector extends BaseInflector
{
    // public static $transliterator = 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;';

    public static function transliterate($string, $transliterator = null)
    {
        $converter = array(
            'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
            'Е' => 'E',    'Ё' => 'Yo',   'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
            'Й' => 'J',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
            'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
            'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
            'Ш' => 'Sh',   'Щ' => 'Shch', 'Ъ' => '',     'Ы' => 'Y',    'Ь' => '',
            'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
     
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'yo',   'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'j',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'shch', 'ъ' => '',     'ы' => 'y',    'ь' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya'
        );
     
        return strtr($string, $converter);
    }
}
