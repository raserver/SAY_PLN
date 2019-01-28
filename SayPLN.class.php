<?php

/*
 * Klasa SayPLN zwraca słowną wartość kwoty w PLN do 999 999 999 999,99 zł
 * lub błąd, jeśli dane wejściowe są błędne
 * autor: ra-server.pl
 * licencja: free
 */

class SayPLN {

    const GR_BASE = 'grosz';
    const SEPARATOR = ' ';

    private function SlownieGroszy($grosze) {

        $array_gr_endings       =   ['y','y','e','e','e','y','y','y','y','y'];  // tablica końcówek groszy
        $array_say_units        =   ['','jeden','dwa','trzy','cztery','pięć','sześć','siedem','osiem','dziewięć'];  // tablica jednostek groszy
        $array_say_overunits    =   ['dziesięć','jedenaście','dwanaście','trzynaście','czternaście','piętnaście','szesnaście','siedemnaście','osiemnaście','dziewiętnaście'];  // tablica 'naście' groszy
        $array_say_decs         =   ['','','dwadzieścia','trzydzieści','czterdzieści','pięćdziesiąt','sześćdziesiąt','siedemdziesiąt','osiemdziesiąt','dziewięćdziesiąt'];  // tablica dziesiątek groszy

        $div_10 = intval($grosze / 10);  // ilość dziesiątek
        $mod_10 = $grosze % 10;  //ilość jednostek

        if ($grosze == 0) { return 'zero groszy'; }  // zwraca zero groszy
        if ($grosze == 1) { return 'jeden grosz'; }  // zwraca jeden grosz
        if (($grosze > 1) && ($grosze < 10)) { return $array_say_units[$grosze] . self::SEPARATOR . self::GR_BASE . $array_gr_endings[$grosze]; }  // zwraca grosze od 2 do 9
        if (($grosze >= 10) && ($grosze < 20)) { return $array_say_overunits[$mod_10] . self::SEPARATOR . self::GR_BASE . $array_gr_endings[0]; }  // zwraca grosze od 10 do 19
        if (($grosze >= 20) && ($grosze < 100)) { return $array_say_decs[$div_10] . self::SEPARATOR . $array_say_units[$mod_10] . self::SEPARATOR . self::GR_BASE . $array_gr_endings[$mod_10]; }  // zwraca grosze od 20 do 99

    }


    private function SlownieSetek($setek) {

        if (($setek < 0) || ($setek > 999)) { return '<br>Liczba spoza zakresu!<br>'; }  // liczba setek spoza zakresu

        $array_say_units        =   ['','jeden','dwa','trzy','cztery','pięć','sześć','siedem','osiem','dziewięć'];  // tablica jednostek
        $array_say_overunits    =   ['dziesięć','jedenaście','dwanaście','trzynaście','czternaście','piętnaście','szesnaście','siedemnaście','osiemnaście','dziewiętnaście'];  // tablica 'naście'
        $array_say_decs         =   ['','','dwadzieścia','trzydzieści','czterdzieści','pięćdziesiąt','sześćdziesiąt','siedemdziesiąt','osiemdziesiąt','dziewięćdziesiąt'];  // tablica dziesiątek
        $array_say_hundreds     =   ['','sto','dwieście','trzysta','czterysta','pięćset','sześćset','siedemset','osiemset','dziewięćset'];  // tablica setek

        $hundreds_numb = intval($setek / 100) * 100;  // ilość setek
        $decs_numb = intval(($setek - ($hundreds_numb)) / 10) * 10;  // ilość dziesiątek
        $units_numb = $setek - $hundreds_numb - $decs_numb;  // ilość jednostek
        $rest_of_hundreds = $setek - $hundreds_numb;  // reszta z setek

        if ($hundreds_numb > 0) {
            $say_hundreds = $array_say_hundreds[($hundreds_numb / 100)] . self::SEPARATOR;
        } else {
            $say_hundreds = '';
        }

        if (($rest_of_hundreds >= 0) && ($rest_of_hundreds < 10)) { return $say_hundreds . $array_say_units[$units_numb]; }
        if (($rest_of_hundreds >= 10) && ($rest_of_hundreds < 20)) { return $say_hundreds . $array_say_overunits[($rest_of_hundreds - 10)]; }
        if (($rest_of_hundreds >= 20) && ($rest_of_hundreds < 100)) { return $say_hundreds . $array_say_decs[($decs_numb / 10)] . self::SEPARATOR . $array_say_units[$units_numb]; }
        
    }


    private function SlownieZlotych($kwota) {

        if (!is_numeric($kwota)) { return '<br>To nie jest liczba!<br>'; }  // wprowadzony argument nie jest liczbą!
        if (($kwota < 0) || ($kwota > 999999999999.99)) { return '<br>Kwota spoza zakresu!<br>'; }  // wprowadzony argument jest liczbą spoza zakresu!
        if ($kwota == 0) { return 'zero złotych'; }  // zwraca zero złotych

        $kwota = number_format((float)$kwota, 2, '.', '');
        $number = number_format($kwota,0,'','');  // złotówki
        $float = number_format($kwota - $number, 2) * 100;  // grosze
        $SAY = '';

        $array_big_numb = [0, 1000000000, 1000000, 1000];
        $array_say = [ [],['','miliard','miliardów','miliardy'],['','milion','milionów','miliony'],['','tysiąc','tysięcy','tysiące'] ];

        // miliardy, miliony, tysiące
        for ($i = 1; $i < 4; $i++) {

            $type = 0;
            $big_number = intval($number / $array_big_numb[$i]);
            $hundreds_numb = intval($big_number / 100) * 100;
            $decs_numb = intval(($big_number - ($hundreds_numb)) / 10) * 10;
            $units_numb = $big_number - $hundreds_numb - $decs_numb;
            $rest = $decs_numb + $units_numb;

            if ($big_number > 0) {

                if ($i == 1) {
                    $SAY = $this -> SlownieSetek($big_number) . self::SEPARATOR;
                } else {
                    $SAY .= $this -> SlownieSetek($big_number) . self::SEPARATOR;
                }

                if ($big_number == 1) { $type = 1; }

                if (($rest > 10) && ($rest < 20)) { $type = 2; }

                switch($type) {
                    case 1:
                        $SAY .= $array_say[$i][1];
                        break;

                    case 2:
                        $SAY .= $array_say[$i][2];
                        break;

                    default:
                        if (($units_numb >= 2) && ($units_numb <=4)) {
                            $SAY .= $array_say[$i][3];
                        } else {
                            $SAY .= $array_say[$i][2];
                        }
                        break;
                }

                $SAY .= self::SEPARATOR;
                $number -= $big_number * $array_big_numb[$i];
            }
        }

        // setki
        $type = 0;
        $SAY .= $this -> SlownieSetek($number) . self::SEPARATOR;
        $hundreds_numb = intval($number / 100) * 100;
        $decs_numb = intval(($number - ($hundreds_numb)) / 10) * 10;
        $units_numb = $number - $hundreds_numb - $decs_numb;
        $rest = $decs_numb + $units_numb;
            if ($rest == 1) {
                $type = 1;
            }
            if (($rest > 10) && ($rest < 20)) {
                $type = 2;
            }
            switch($type) {
                case 1:
                    $SAY .= 'złoty';
                    break;

                case 2:
                    $SAY .= 'złotych';
                    break;

                default:
                    if (($units_numb >= 2) && ($units_numb <=4)) {
                        $SAY .= 'złote';
                    } else {
                        $SAY .= 'złotych';
                    }
                    break;

            }

    return $SAY . ' i ' . $this -> SlownieGroszy($float);

    }

}



?>