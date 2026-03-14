<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/datelimiter.php';

$submit = $_POST['submit'] ?? 0;
$sample_name = 'numerology';

$timezone = 'Asia/Kolkata';
$tz = new DateTimeZone($timezone);

$result = [];
$errors = [];

$firstName = $_POST['firstName'] ?? '';
$middleName = $_POST['middleName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$date = $_POST['date'] ?? '';

$selectedCalculator = $_POST['calculatorName'] ?? 'life-path-number';


/* -------------------------
   NUMEROLOGY FUNCTIONS
--------------------------*/

function reduceNumber($num)
{
    while ($num > 9) {
        $num = array_sum(str_split((string)$num));
    }
    return $num;
}

function lifePathNumber($date)
{
    $digits = str_split(str_replace('-', '', $date));
    return reduceNumber(array_sum($digits));
}

function birthdayNumber($date)
{
    $day = date("d", strtotime($date));
    return reduceNumber(array_sum(str_split($day)));
}

function nameNumber($name)
{
    $name = strtoupper($name);

    $values = [
        'A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5,'F'=>6,'G'=>7,'H'=>8,'I'=>9,
        'J'=>1,'K'=>2,'L'=>3,'M'=>4,'N'=>5,'O'=>6,'P'=>7,'Q'=>8,'R'=>9,
        'S'=>1,'T'=>2,'U'=>3,'V'=>4,'W'=>5,'X'=>6,'Y'=>7,'Z'=>8
    ];

    $sum = 0;

    foreach(str_split($name) as $char){
        if(isset($values[$char])){
            $sum += $values[$char];
        }
    }

    return reduceNumber($sum);
}


/* -------------------------
   PROCESS FORM
--------------------------*/

if ($submit) {

    try {

        validateDateTime(
            $_POST['date'],
            $tz,
            new DateTimeImmutable('-1 day', $tz),
            new DateTimeImmutable('+1 day', $tz)
        );

        if ($selectedCalculator === 'life-path-number') {

            $number = lifePathNumber($date);

            $result = [
                'title' => 'Life Path Number',
                'number' => $number
            ];

        } elseif ($selectedCalculator === 'birthday-number') {

            $number = birthdayNumber($date);

            $result = [
                'title' => 'Birthday Number',
                'number' => $number
            ];

        } elseif ($selectedCalculator === 'expression-number') {

            $name = $firstName . $middleName . $lastName;

            $number = nameNumber($name);

            $result = [
                'title' => 'Expression Number',
                'number' => $number
            ];

        } else {

            $result = [
                'title' => 'Calculator',
                'number' => 'Not implemented yet'
            ];

        }

    } catch (Exception $e) {

        $errors['message'] = $e->getMessage();

    }
}


/* API removed */
$apiCreditUsed = 0;


include DEMO_BASE_DIR . '/templates/numerology.tpl.php';