<?php
use Pressmind\ORM\Object\MediaObject;
use Pressmind\HelperFunctions;

require_once dirname(__DIR__) . '/bootstrap.php';
$mediaObject = new MediaObject(intval($_GET['id']));
$cheapest_price = $mediaObject->getCheapestPrice();
$booking_packages = $mediaObject->booking_packages;
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
    <title>Example for displaying a list of bookable dates for a media object</title>
</head>
<body>
<div class="container">
    <?php if(!is_null($cheapest_price)) {?>
        <section>
            <div class="row">
                <div class="col-12">
                    <h2>Dates &amp; Booking</h2>
                    <div>
                        <div class="row">
                            <div class="col-5">
                                <strong>Date</strong>
                            </div>
                            <div class="col-1">
                                <strong>Status</strong>
                            </div>
                            <div class="col-2">
                                <strong>Info</strong>
                            </div>
                            <div class="col-2">
                                <strong>Price p.P.</strong>
                            </div>
                            <div class="col-2"></div>
                        </div>
                        <?php foreach ($booking_packages as $booking_package) {?>
                            <div class="row">
                                <div class="col-12">
                                    Duration <?php echo $booking_package->duration;?> Day<?php echo($booking_package->duration > 1 ? 's' : '');?>
                                </div>
                            </div>
                            <?php
                            foreach ($booking_package->dates as $date) {;?>
                                <?php
                                foreach ($date->getHousingOptions() as $housing_option) {
                                    $housing_package = $housing_option->getHousingPackage();
                                    ?>
                                    <div class="booking-row no-gutters row booking-row-date">
                                        <div class="col-3">
                                            <?php echo HelperFunctions::dayNumberToLocalDayName($date->departure->format('N'), 'short')?> <?php echo $date->departure->format('d.m.');?> - <?php echo HelperFunctions::dayNumberToLocalDayName($date->arrival->format('N'), 'short')?> <?php echo $date->arrival->format('d.m.Y');?>
                                        </div>
                                        <div class="col-2">
                                            <?php echo $housing_package->name;?>
                                        </div>
                                        <div class="col-1">
                                            <span class="badge badge-success">Buchbar</span>
                                        </div>
                                        <div class="col-2">
                                            <?php echo $housing_option->name;?> <?php echo $housing_option->board_type;?>
                                        </div>
                                        <div class="col-2">
                                            <strong class="price">€ <?php echo HelperFunctions::number_format($housing_option->price)?></strong>
                                        </div>
                                        <div class="col-2">
                                            <a href="http://my_ibe_url.pressmind-ibe.net/?imo=<?php echo $booking_package->id_media_object;?>&idbp=<?php echo $booking_package->id;?>&idhp=<?php echo $housing_package->id;?>&idd=<?php echo $date->id;?>&iho[<?php echo $housing_option->id;?>]=1" target="_blank" class="btn btn-outline-primary btn-block">
                                                Book now
                                            </a>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                                <?php
                            }
                        }?>
                    </div>
                </div>
            </div>
        </section>
    <?php } else {?>
        <section>
            <div class="row">
                <div class="col-12">
                    <h2>Es konnten keine gültigen Termine gefunden werden </h2>
                </div>
            </div>
        </section>
    <?php }?>
    <h2>Raw data</h2>
    <?php foreach ($booking_packages as $booking_package) {?>
        <h3>$cheapest_price</h3>
        <pre>
        <?php print_r($cheapest_price->toStdClass());?>
    </pre>
        <h3>Booking Package ID: <?php echo $booking_package->id;?></h3>
        <pre>
        <?php print_r($booking_package->toStdClass());?>
    </pre>
        <h3>$bookingPackage->dates</h3>
        <?php foreach ($booking_package->dates as $date) {?>
            <pre>
            <?php print_r($date->toStdClass());?>
        </pre>
            <h3>$date->getHousingOptions</h3>
            <?php foreach ($date->getHousingOptions() as $housing_option) {?>
                <pre>
                <?php print_r($housing_option->toStdClass());?>
            </pre>
            <?php }?>
            <h3>$date->getExtras()</h3>
            <pre>
            <?php print_r($date->getExtras());?>
        </pre>
            <h3>$date->getTickets()</h3>
            <pre>
            <?php print_r($date->getTickets());?>
        </pre>
            <h3>$date->getSightseeings()</h3>
            <pre>
            <?php print_r($date->getSightseeings());?>
        </pre>
        <?php }?>
    <?php }?>
</div>
</body>
</html>
