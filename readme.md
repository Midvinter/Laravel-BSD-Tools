## Installation

Install the package with composer

    composer require midvinter/bsdtools

Then add

    Midvinter\BSDTools\BSDToolsServiceProvider::class

to your app.php config. When this is done you should also publish the config file

    php artisan vendor:publish
