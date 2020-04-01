<?php

use Pressmind\Search;

require_once dirname(__DIR__) . '/bootstrap.php';

//Here we list all MediaObjects within a price-range of 1 to 5000, limited to 100 results and sorted by rand()
$tour_search = new Search(
    [
        Search\Condition\PriceRange::create(1, 5000),
    ],
    [
        'start' => 0,
        'length' => 100
    ],
    [
        '' => 'RAND()'
    ]
);
$tours = $tour_search->getResults();
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        pre {
            max-height: 400px;
            overflow: auto;
        }
    </style>
    <title>Example for displaying a list of tours</title>
</head>
<body>
<div class="container">
    <?php foreach ($tours as $tour) {
        echo '<a href="detail.php?id=' . $tour->id . '">' . $tour->name . '</a><br>';
    }
    ?></div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
