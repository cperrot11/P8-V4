# OC-P8-ToDo&Co
[![Maintainability](https://api.codeclimate.com/v1/badges/ca3a4b5dce0ceac5abf8/maintainability)](https://codeclimate.com/github/cperrot11/OC-P6-SnowTrick/maintainability)

## Introduction
This project goal is to upgrade an existing application. Implementation of new features, correct some bugs and at the end implement automated tests.
It's the 8th OpenClassRooms [PHP/Symfony Developer](https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony) project. 

## Installation
To run this project and load all its dependencies on your local machine, you need to have [Composer](https://getcomposer.org/).
1. Clone this repository on your computer by using this command line :
`git clone https://github.com/cperrot11/P8-V4.git`.
2. Change your Database configuration in .env file.
`DATABASE_URL=mysql://login:passwor@server:3306/databaseName`.

## Use "MakeFile" to have a more fun dev :relaxed:
1. Run `make` in a terminal and discover how to use it in few seconds. 
2. Use `make begin` to configure all the application in one time.
 That run the following action :     
  | Make command |  Resume                                                    |
  |------------- | ---------------------------------------------------------- |
  | coin | Install vendors according to the current composer.lock file        |
  |------------- | ---------------------------------------------------------- |
  | coup | Update vendors according to the composer.json file                 |
  |--------------|------------------------------------------------------------|
  | indb | Create database, apply the migration and upload the data fixtures. |
  
    Execute command line `php bin/console composer update`.
First Header | Second Header
------------ | -------------
Content from cell 1 | Content from cell 2
Content in the first column | Content in the second column

4. Apply your database configuration in  `snowtricks/config/ini.php`.
5. Dont forget to configure your e-mail setting with this line in the ".env" file
MAILER_URL=smtp://yourprovider:587?username=yourlogin&password=yourPasswor

##### Your project is ready to be run!
##### I can hear you saying: "Wait... I don't want to create families and tricks one by one...". Don't worry!

6. Run `php bin/console doctrine:fixture:load` and wait until it's done. Now you have a website full of tricks, comments and users!
7. Enjoy!

