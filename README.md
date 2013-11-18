XeroAccess
==========

PHP based Access Control System

Admin Login Protection
======================

XeroAccess was designed to make life difficult for unauthorised users who want access to your admin control panel 
by brute forcing or exploiting the administrator login form / login system.

I needed this since the default login dir/page for the most common CMS and forums ( Joomla, IPB, Vbulletin, Wordpress etc ) 
are very well known and by default do not even try to stop hackers from attempting to brute force the login as much as they like. 

Default Response
----------------

A default 404 page is given to anyone who requests the admin login page without the correct url parameters.

With the extra authentication options enabled, a 404 error is also returned if the requesting users IP address is not in the static or dynamic IP whitelist
or if there are no matching auth tokens in the users referer or useragent. 

Logging all failed access attempts can also help to detect persistent automated hacking attempts.

Request Key Auth
----------------

Private access key in url

example: http://admin.login/administrator/index.php?xscode=supersecretcode

IP Auth
-------

Static & Dynamic IP regex whitelist

Token Auth
----------

Referer & Useragent Token check

Using Firefox extensions RefControl and UAControl you can set your own tokens for extra authentication.

When using the dynamic IP regex for authentication, at least one of these Token options should be enabled.

Basic Setup Instructions
------------------------

Relative Path :

Place xeroaccess.php in the same directory as the file you want to protect eg : /administrator/index.php

```php
if( is_readable('xeroaccess.php')){ include 'xeroaccess.php'; }
```

Absolute Path :

```php
define( 'XA_BASEPATH' , substr( dirname( $_SERVER['SCRIPT_NAME'] ) , -1 ) !== '/'  
?  dirname( $_SERVER['SCRIPT_NAME'] ) . '/' 
:  dirname( $_SERVER['SCRIPT_NAME'] ));

if( is_readable( XA_BASEPATH . 'xeroaccess.php' )){
include XA_BASEPATH . 'xeroaccess.php';
}
```

