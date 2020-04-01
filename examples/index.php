<?php

use Pressmind\Search;

require_once dirname(__DIR__) . '/bootstrap.php';

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
foreach ($tours as $tour) {
    echo '<a href="detail.php?id=' . $tour->id . '">' . $tour->name . '</a><br>';
}
