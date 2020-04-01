# Pressmind Web Core

## v 0.0.1alpha

## Quickstart

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
