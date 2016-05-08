Advanced Weather Forecast
=========================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nfqakademija/visumeistrugalas/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nfqakademija/visumeistrugalas/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/nfqakademija/visumeistrugalas/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nfqakademija/visumeistrugalas/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/nfqakademija/visumeistrugalas/badges/build.png?b=master)](https://scrutinizer-ci.com/g/nfqakademija/visumeistrugalas/build-status/master)
[![Build Status](https://travis-ci.org/nfqakademija/visumeistrugalas.svg?branch=master)](https://travis-ci.org/nfqakademija/visumeistrugalas)

Requirements
------------

  * PHP 7 or higher;    
  * MySql or MariaDB
  * gulp
  * and the [usual Symfony application requirements](http://symfony.com/doc/current/reference/requirements.html).

Installation
------------

Download and install this application usig Git and Composer:

```bash
$ git clone https://github.com/nfqakademija/visumeistrugalas
$ cd visumeistrugalas/
$ composer install --no-interaction
$ npm install
$ gulp
```

Usage
-----

If you have PHP 5.4 or higher, there is no need to configure a virtual host
in your web server to access the application. Just use the built-in web server:

```bash
$ cd visumeistrugalas/
$ php app/console server:run
```

This command will start a web server for the Symfony application. Now you can
access the application in your browser at <http://localhost:8000>. You can
stop the built-in web server by pressing `Ctrl + C` while you're in the
terminal.

Various internal commands
-------------------------

Useful commands to check code quality:

> bin/phpcs -p --standard=PSR2 --extensions=php ./src
>
> bin/phpcs -p --standard=PSR2 --extensions=php --report=diff ./src

Code quality automatic fix:

> bin/phpcbf --no-patch --standard=PSR2 ./src/filename.php

- - - - - - -  
#### 2016 &copy; Visų Meistrų Galas