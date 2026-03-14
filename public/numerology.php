<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/datelimiter.php';

$submit = $_POST['submit'] ?? 0;
$sample_name = 'numerology';

$result = [];
$errors = [];

$firstName = $_POST['firstName'] ?? '';
$middleName = $_POST['middleName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$date = $_POST['date'] ?? '';

$timezone = 'Asia/Kolkata';
$tz = new DateTimeZone($timezone);


/* -------------------------
UTILITY FUNCTIONS
--------------------------*/

function reduceNumber($num)
{
    while ($num > 9 && $num != 11 && $num != 22 && $num != 33) {
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

function soulUrgeNumber($name)
{
    $name = strtoupper($name);
    $vowels = ['A','E','I','O','U'];

    $values = [
        'A'=>1,'E'=>5,'I'=>9,'O'=>6,'U'=>3
    ];

    $sum = 0;

    foreach(str_split($name) as $char){
        if(in_array($char,$vowels)){
            $sum += $values[$char];
        }
    }

    return reduceNumber($sum);
}

function personalityNumber($name)
{
    $name = strtoupper($name);
    $vowels = ['A','E','I','O','U'];

    $values = [
        'B'=>2,'C'=>3,'D'=>4,'F'=>6,'G'=>7,'H'=>8,
        'J'=>1,'K'=>2,'L'=>3,'M'=>4,'N'=>5,'P'=>7,
        'Q'=>8,'R'=>9,'S'=>1,'T'=>2,'V'=>4,'W'=>5,
        'X'=>6,'Y'=>7,'Z'=>8
    ];

    $sum = 0;

    foreach(str_split($name) as $char){
        if(!in_array($char,$vowels) && isset($values[$char])){
            $sum += $values[$char];
        }
    }

    return reduceNumber($sum);
}

function maturityNumber($lifePath,$destiny)
{
    return reduceNumber($lifePath + $destiny);
}

function personalYear($date)
{
    $year = date("Y");
    $birthMonthDay = date("md",strtotime($date));

    $sum = array_sum(str_split($birthMonthDay.$year));

    return reduceNumber($sum);
}

function personalMonth($date)
{
    $month = date("m");
    $py = personalYear($date);

    return reduceNumber($month + $py);
}

function personalDay($date)
{
    $day = date("d");
    $pm = personalMonth($date);

    return reduceNumber($day + $pm);
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

        $fullName = $firstName . $middleName . $lastName;

        $lifePath = lifePathNumber($date);
        $birthday = birthdayNumber($date);
        $destiny = nameNumber($fullName);
        $soul = soulUrgeNumber($fullName);
        $personality = personalityNumber($fullName);
        $maturity = maturityNumber($lifePath,$destiny);

        $py = personalYear($date);
        $pm = personalMonth($date);
        $pd = personalDay($date);

        $result = [
            "lifePath"=>$lifePath,
            "birthday"=>$birthday,
            "destiny"=>$destiny,
            "soulUrge"=>$soul,
            "personality"=>$personality,
            "maturity"=>$maturity,
            "personalYear"=>$py,
            "personalMonth"=>$pm,
            "personalDay"=>$pd
        ];

    } catch (Exception $e) {

        $errors['message'] = $e->getMessage();

    }
}

$apiCreditUsed = 0;

include DEMO_BASE_DIR . '/templates/numerology.tpl.php';