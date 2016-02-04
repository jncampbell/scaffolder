# Scaffolder 
The Scaffolder package generates the boilerplate for a php package. Running the command creates the following directory structure:
```
  packageName/    
   |–composer.json (w/ boilerplate)
   |–phpunit.xml   (w/ boilerplate)
   |–src/ 
   |–tests/
   |–public/ (optional w/ --playground option)
      |–index.php
```
## Requirements
 - PHP

## Installation 
#### Globally (Composer)
`$ composer global require jncampbell/scaffolder`

#### Locally (Composer)
`$ composer require jncampbell/scaffolder`

## Usage
The command will be stored in your vendor/bin directory

The `scaffolder new` command generates the boilerplate. A name is required.  

    $ vendor/bin/scaffolder new nameOfYourPackage

The `--playground` option creates a public folder with an index.php page 

    $ vendor/bin/scaffolder new nameOfYourPackage --playground
    
Once the boilerplate is generated, fill in the composer.json with your package's information, `cd` into your package directory and run a `composer install`. Then begin building your package.


##Screenshots
These are the templates that will be generated:  
![composer.json file](http://i1056.photobucket.com/albums/t367/jncampbell/Screen%20Shot%202015-12-15%20at%201.31.01%20PM_zpsayl7vhwp.png "composer.json file")
![phpunit.xml file](http://i1056.photobucket.com/albums/t367/jncampbell/Screen%20Shot%202015-12-15%20at%201.32.29%20PM_zpsw85twnmf.png "phpunit.xml file")


##License  
MIT