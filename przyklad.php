<?php

    include 'SayPLN.class.php';

    $amount = 9123.45;  // kwota złotych - liczbowo

    $slownie = new SayPLN();

    echo 'Kwota: ' . str_replace('.', ',', $amount) . ' zł<br>';

    echo 'Słownie: ' . $slownie -> SlownieZlotych($amount);

?>