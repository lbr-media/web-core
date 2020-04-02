# Pressmind Web Core

## v 0.0.1alpha

## Quickstart

### Installation
* clone the repository
* edit the configuration file config.json
* Insert your database information under development.database
```json
{
    "development": {
        "database": {
            "username": "yourusername",
            "password": "yourpassword",
            "host": "localhost",
            "dbname": "yourdatabasename",
            "engine": "Mysql"
        }
    }
}
```
* Insert your pressmind API credentials under development.rest.client (credentials are provided by pressmind)
```json
{
    "development": {
        "rest": {
            "client": {
                "api_endpoint": "https://api.pm-t2.com/rest/",
                "api_key": "yourapikey",
                "api_user": "yourapisuername",
                "api_password": "yourapipassword"
            }
        }
    }
}
```
* save the config.json file
* on a console move to folder cli and execute install.php
```shell script
your-project-folder/cli$ php install.php
```
This will install the necessary database tables and generate the needed model-definitions for the media object types.  
Additionally some basic example php files that show the use of Views are generated in the folder examples/views as well as some html files with information on the installed media object types. You can find these under docs/objecttypes 
### Data Import
To import data from pressmind into the database use the file cli/import.php  
To do a fullimport (which is recommended after a fresh install add the argument fullimport)
```shell script
your-project-folder/cli$ php import.php fullimport
```
Depending on the amount of data that is stored in pressmind, the fullimport can last while.  
For each media object all descriptive and touristic data will be imported into the database. Additionally all related files and images will be downloaded to the folder /assets.
### Search and Display Data
After the install.php script has been executed, some example files can be found in the examples folder:
The index.php file demonstrates a simple search and will display a list of found data-sets with a link to the detail.php which demonstrates how a media_object can be rendered.  
The detail.php will render the information based on the view scripts that can be found in the examples/views folder.

### Quick Examples
#### Search for media objects
searchMediaObjects.php
```php
<?php
require_once dirname(__DIR__) . '/bootstrap.php';
use Pressmind\Search;

$search = new Search(
    [
        Search\Condition\PriceRange::create(1, 5000),
        Search\Condition\ObjectType::create(169),
        Search\Condition\Category::create('land_default', ['B9063101-0F6A-2322-83A6-FAF7A0D82827']),
        Search\Condition\Text::create(169, 'Riesengebirge', ['headline_default' => 'LIKE']),
        Search\Condition\DateRange::create(new DateTime('2020-06-01'), new DateTime('2020-07-31'))
    ],
    [
        'start' => 0,
        'length' => 100
    ],
    [
        '' => 'RAND()'
    ]
);
$mediaObjects = $search->getResults();

foreach ($mediaObjects as $mediaObject) {
    echo $mediaObject->render('test'); //will use Reise_Test.php as view file (code is shown below)
}
```
#### View script for a media objects
Reise_Test.php (see also the *_Example.php scripts in /examples/views for reference)
```php
<?php
    /**
     * @var array $data
     */
     
    /**
     * @var Custom\MediaType\Reise $reise
     */
    $reise = $data['data'];
    
    /**
     * @var Pressmind\ORM\Object\Touristic\Booking\Package[] $booking_packages
     */
    $booking_packages = $data['booking_packages'];


    /**
     * @var Pressmind\ORM\Object\MediaObject $media_object
     */
    $media_object = $data['media_object'];

    echo "-\r\n";
    echo $reise->id_media_object."\r\n";
    echo $media_object->name."\r\n";
    foreach($reise->land_default as $land_default_item) {
        echo $land_default_item->item->name."\r\n";
    }

    foreach ($booking_packages as $booking_package){
        echo $booking_package->duration." Tage \r\n";
        echo "id_booking_package: ".$booking_package->id."\r\n";
        foreach($booking_package->dates as $date){
            echo $date->departure->format('d.m.Y') .' - '.$date->arrival->format('d.m.Y')."\r\n";
        }

        foreach ($booking_package->housing_packages as $housing_package){
            echo 'HousingPackage: '.$housing_package->name."\r\n";
            echo 'Nights: '.$housing_package->nights."\r\n";

            foreach ($housing_package->options as $option){
                echo $option->code.' '.$option->name.' '.$option->price."\r\n";
            }
        }


    }
```
#### Get a media object by ID
getById.php
```php
<?php
require_once dirname(__DIR__) . '/bootstrap.php';

use Pressmind\ORM\Object\MediaObject;

// get a specified MediaObject by ID
$mediaObject = new MediaObject(938117);
echo $mediaObject->name;
```
