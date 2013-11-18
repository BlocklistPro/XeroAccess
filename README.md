XeroAccess
==========

PHP based Access Control System

Admin Login Protection
======================

XeroAccess was designed to make life difficult for unauthorised users who want access to your admin control panel 
by brute forcing or exploiting the administrator login form / login system.

I needed this for extra login protection, since the default login dir/page for the most common CMS and forums ( Joomla, IPB, Vbulletin, Wordpress etc ) 
are very well known and by default do not even try to stop hackers from attempting to brute force the login as much / for as long as they like. 

Banning IP's 'after' someone attacks the login page is useless since dynamic IP's allow someone to have a new IP just by turning their modem/router off and on. 

XeroAccess offers simple proactive protection, which is the only sane method of dealing with attacks. Reacting to attacks on your website after they've happened means you're always one step behind.

Default Response
----------------

By default, a 404 error is given to anyone who requests the admin login page without the correct url parameters.

This can easily be changed, but a 404 is the ideal response, since it helps to create confusion and doubt about the existence of the login page, 
compared to a 403 which could invite further attempts to gain access. 

With the extra authentication options enabled, a 404 error is also returned if the requesting users IP address is not in the static or dynamic IP whitelist
or if there are no matching auth tokens in the users referer or useragent, even when the access code is correct. 

Logging all failed access attempts can also help to detect persistent automated hacking attempts.


Options
---------

Use the `$axs` options array to configure your settings. Uncomment the extra options to enable them.

```php
$axs = array(
    'request_code'          => 'supersecretcode'
    //, 'static_ip_whitelist'   => array( '121.1.1.2' , '121.1.1.3' ) // full ip's
    //, 'dynamic_ip_whitelist'  => '#^121\.1\.1\.1[23]|#' // regex only! - partial ip
    //, 'referer_token'         => 'ref token'
    //, 'useragent_token'       => 'ua token'
);
```

To allow multiple dynamic IP's, just separate each partial IP with an or `|` 

```php
'dynamic_ip_whitelist'  => '#^121\.1\.1\.1[23]|66\.5\.4\.3[1-9]|24\.1\.2\.#' // regex only! - partial ip
```

User Profile
------------

The `$profile` array contains all the required auth information from the user. 

IP Address Conditions : 

If `remote_addr` is empty the script will exit silently. Any access attempts with x_forwarded / proxy headers etc are automatically denied.

By default IPv6 connections are also denied. 

UserAgent Conditions : 

If a browser useragent is empty the script will exit silently. 

```php
// Visitor Profile
// Only allow IP access from IPv4 - Auto deny IPv6
$profile = array(
    
    'ip'   => !empty( $_SERVER['REMOTE_ADDR'] ) 
              && filter_var( $_SERVER['REMOTE_ADDR'] , FILTER_VALIDATE_IP, FILTER_VALIDATE_IPV4 ) 
              ? $_SERVER['REMOTE_ADDR'] 
              : exit; 
                 
    'ua'   => !empty( $_SERVER['HTTP_USER_AGENT'] ) 
              ? strtolower( $_SERVER['HTTP_USER_AGENT'] )
              : exit;
                 
    'ref'  => !empty( $_SERVER['HTTP_REFERER'] ) 
              ? strtolower( $_SERVER['HTTP_REFERER'] )
              : '';
);
```

By default no error message is returned but you can easily add one if you like to the exit code.

```php
exit("Access Denied. We don't like you anymore.");
```

Request Key Auth Code
---------------------

Access key in url:

example: http://admin.login/administrator/index.php?xscode=supersecretcode

IP Auth
-------

Static & Dynamic IP regex whitelist

Token Auth
----------

Referer & Useragent Token check

Using Firefox extensions RefControl and UAControl you can set your own tokens for extra authentication.

UAControl:
https://addons.mozilla.org/en-us/firefox/addon/uacontrol/

RefControl:
https://addons.mozilla.org/en-us/firefox/addon/refcontrol/

If using the dynamic IP regex option for authentication, at least one of these Tokens should be enabled for 'better' security..


Basic Setup Instructions
------------------------

Most CMS / forums use an index.php front controller, so you should include `xeroaccess.php` at the top of the root index.php file. 

Relative Path :

Place `xeroaccess.php` in the same directory as the file you want to protect eg : /administrator/index.php

```php
if( is_readable('xeroaccess.php')) include 'xeroaccess.php';
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

Errors : 
--------

If you want to prevent access to the login page altogther if xeroaccess.php cannot be loaded, change include to require.( probably not a good idea though :) )

Display Errors should usually be set to none on production servers.

http://www.php.net/manual/en/errorfunc.configuration.php#ini.display-errors

```php
error_reporting(E_ALL);
ini_set('display_errors',0);
```

Logging
-------

Not included yet.

