# OC-P8-ToDo&Co
[![Maintainability](https://api.codeclimate.com/v1/badges/ca3a4b5dce0ceac5abf8/maintainability)](https://codeclimate.com/github/cperrot11/OC-P6-SnowTrick/maintainability)

## Introduction
This project goal is to upgrade an existing application. Implementation of new features, correct some bugs and at the end implement automated tests.
It's the 8th OpenClassRooms [PHP/Symfony Developer](https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony) project. 

## Installation
To run this project and load all its dependencies on your local machine, you need to have [Composer](https://getcomposer.org/).
1. Clone this repository on your computer by using this command line :
`git clone https://github.com/cperrot11/P8-V4.git`.
2. Change your Database configuration in .env file :
`DATABASE_URL=mysql://login:passwor@server:3306/databaseName`.

## Use "MakeFile" to have a more fun dev :relaxed:
1. Run `make` in a terminal and discover how to use it in few seconds. 
2. Use `make begin` to configure all the application in one time.
 That run the following action :     
    * `make coin` -> Install vendors according to the current composer.lock file
    * `make coup` -> Update vendors according to the composer.json file
    * `make indb` -> Create database, apply the migrations and upload the data fixtures
  
*Nb : Under Windows install cygdrive to be able to enjoy MakeFile benefit*     
    
## Your project is ready to be run!
##### I can hear you saying: "Wait... I don't want to create task and user one by one...". Don't worry!
##### login : **admin@gmail.com**
##### pass : **123456**

## Technicals documentations
    * Authentification (doc/1-Authentification.pdf) -> Describe how authentication works 
    * quality audit (doc/2-Quality.pdf) -> Measures the technical performance of the application
    * Test coverage (doc/CodeCoverage/index.html) -> Result over than 40 tests on the code
    * Contribution (doc/3-Contribution.md) -> Help us to improve the program :two_men_holding_hands: