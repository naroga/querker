Querker
=======

![Travis](https://travis-ci.org/naroga/querker.svg?branch=master)

Querker is both a simple queue manager for tasks and a worker manager. It aggregates `naroga/querker-queue` and
`naroga/querker-worker` in the same place, so you can manage both your queue and your workers from the
same place.

Project requisites
------------------

This project is now supported in all major OS': *nix, OSX and Windows.

It requires PHP 7.0+, php-curl, php-json, php-xml.

See the [Symfony Requirements](http://symfony.com/doc/current/reference/requirements.html)
for more specific and in-depth requirements.

You can check if your system is ready to use by running `php app/check.php`.

Installation
------------

To install querker, use [Composer](https://getcomposer.org):

    $ composer create-project naroga/querker dev-master
    
This will download the querker's code with all its dependencies.

Configuration
-------------

TBD.

License
-------

This project is released under the MIT license. See [LICENSE](LICENSE) file for more information.