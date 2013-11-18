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

When using the dynamic IP regex for authentication, at least one of these Token options should be enabled.

Basic Setup Instructions
------------------------

Relative Path :

Place xeroaccess.php in the same directory as the file you want to protect eg : /administrator/index.php

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


Logging
-------

Not included yet.



