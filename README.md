# HSA Notes
Simple Notes API

To execute the API Project is necessary the use of composer<br>
You can download and install composer in this link: https://getcomposer.org/

Run this command in the API folder to download all project dependencies:

````
php composer.phar install
````

It's necessary pre-install three php extensions before run the composer command:

php-xml (For phpunit)<br>
php-intl (For phpdocs)<br>
php-mbstring (For phpmailer)<br>

# Consuming the API

Check this link to view how to consume the HSA Notes API: http://www.hsanotes.esy.es/


# Class Documentation

Check this link: http://www.hsanotes.esy.es/docs/

# Testing the API

To test the unit tests of API. Run the following commands in the api folder:

./vendor/bin/phpunit tests/UserBeanTest.php --colors

./vendor/bin/phpunit tests/AuthBeanTest.php --colors

./vendor/bin/phpunit tests/NoteBeanTest.php --colors
