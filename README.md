# Scaffolder 
The Scaffolder package generates the boilerplate for a php package. Running the command creates a directory that includes an src directory, a tests directory, a pre-filled composer.json file, a pre-filled phpunit.xml file and optionally, a public folder with an index.php file.
* * *

## Requirements
 - PHP 5.0.0 or later
 - php5-tidy (to pre-fill the phpunit.xml file)

## Installation 
#### Globally (Composer)
`$ composer global require jncampbell/scaffolder`
    
#### Locally (Composer)
```json
    "require": {  
        "jncampbell/scaffolder": "1.0.*"  
    }
```

## Usage  
The `scaffolder new` command generates the boilerplate  

    $ scaffolder new nameOfYourPackage

The `--playground` option creates a public folder with an index.php page 

    $ scaffolder new nameOfYourPackage --playground

Once the boilerplate is generated, fill in the composer.json with your package's information, run a `composer install` and begin building your package.

##Screenshots
These are the templates that will be generated:  
![composer.json file](http://i1056.photobucket.com/albums/t367/jncampbell/Screen%20Shot%202015-12-15%20at%201.31.01%20PM_zpsayl7vhwp.png "composer.json file")
![phpunit.xml file](http://i1056.photobucket.com/albums/t367/jncampbell/Screen%20Shot%202015-12-15%20at%201.32.29%20PM_zpsw85twnmf.png "phpunit.xml file")
