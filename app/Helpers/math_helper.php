<?php 
    require_once __DIR__ . '/math-php-master/vendor/autoload.php';
    use MathPHP\Finance;

    function ppmt(float $rate, int $period, int $periods, float $present_value, float $future_value = 0.0, bool $beginning = false): float
    {
        $amount  =  Finance::ppmt($rate, $period,$periods,$present_value,$future_value,$beginning);
        return $amount;
    }
   
    function ipmt(float $rate, int $period, int $periods, float $present_value, float $future_value = 0.0, bool $beginning = false): float
    {
        $amount  =  Finance::ipmt($rate, $period,$periods,$present_value,$future_value,$beginning);
        return $amount;
    }
?>