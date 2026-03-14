<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Free Astrology Tools</title>

<?php include 'common/style.tpl.php'; ?>

<link rel="stylesheet" href="<?=DEMO_BASE_URL?>/build/style.css">
<link rel="stylesheet" href="<?=DEMO_BASE_URL?>/build/reports.css">

</head>

<body>

<?php include 'common/header.tpl.php'; ?>

<div class="main-content">

<!-- HERO SECTION -->

<div class="container text-center mt-5 mb-5">

<h1>Free Online Astrology Tools</h1>

<p>
Generate Kundli, Panchang, Ashtakavarga, Horoscope and Compatibility Reports instantly.
</p>

</div>


<!-- CALCULATORS -->

<div class="container">

<?php foreach($arGroupCalculators as $group_name => $calculators): ?>

<h3 class="mt-5 mb-4"><?=$group_name?></h3>

<div class="row text-center">

<?php foreach ($calculators as $calculator): ?>

<div class="col-6 col-md-3 mb-4">

<a href="<?=$samples[$calculator]['url']?>">

<img
src="<?=DEMO_BASE_URL?><?=$samples[$calculator]['image']?>"
style="width:70px"
>

<h5 class="mt-2"><?=$samples[$calculator]['title']?></h5>

</a>

</div>

<?php endforeach; ?>

</div>

<?php endforeach; ?>

</div>

</div>

<?php include 'common/footer.tpl.php'; ?>

</body>
</html>