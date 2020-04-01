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
* Insert the information for pressmind media object types under development.data.media_types (this information is also provided by pressmind)
```json
{
    "development": {
        "data": {
            "media_types": {
                "id": "Name",
                "123": "Trip",
                "456": "Day Trip",
                "...": "..."
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
