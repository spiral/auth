Authorization and Tokens
=========================
[![Latest Stable Version](https://poser.pugx.org/spiral/auth/v/stable)](https://packagist.org/packages/spiral/auth) 
[![License](https://poser.pugx.org/spiral/auth/license)](https://packagist.org/packages/spiral/auth)
[![Build Status](https://travis-ci.org/spiral-modules/auth.svg?branch=master)](https://travis-ci.org/spiral-modules/auth)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiral-modules/auth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spiral-modules/auth/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/spiral-modules/auth/badge.svg?branch=master)](https://coveralls.io/github/spiral-modules/auth?branch=master)


## Installation
```
composer require spiral/auth
spiral register spiral/auth
```

To enable authentication service in your application foollowing steps are reuquired.

### Add bootloder
Mount `Spiral\Auth\AuthBootloader` to your application.

```php
 protected $load = [
        //Can speed up class loading a little.
        \Spiral\Core\Loader::class,

         //...

        \Spiral\Auth\Bootloaders\AuthBootloader::class,

        //...
        
        //Application specific bindings and bootloading
        \Bootloaders\AppBootloader::class,
    ];
```

### Add AuthMiddleware
You can either add middleware to a specific route or enable authentication services and scope across application via http config:

```php
'middlewares'  => [
    Middlewares\CsrfFilter::class,
    Middlewares\ExceptionWrapper::class,
  
    //Sample middleware
    \Middlewares\LocaleDetector::class,

    Session\Http\SessionStarter::class,
    Http\Cookies\CookieManager::class,

    //Auth
    \Spiral\Auth\Middlewares\AuthMiddleware::class,
    
    /*{{middlewares}}*/
],
```

### Create user entity and source

Entity must extend PasswordAwareInterface:

```php
class Aware extends Record implements PasswordAwareInterface
{
    //...

    public function getPasswordHash()
    {
        return $this->password;
    }
}
```

Source must implement `UsernameSourceInterface` and needed methods:

```php
class UserSource extends RecordSource implements UsernameSourceInterface
{
    const RECORD = Aware::class;

    public function findByUsername($username)
    {
        return $this->findOne([
            'status'   => 'active',
            'username' => $username,
        ]);
    }
}
```

> TODO: Add usage examples and firewalls.
