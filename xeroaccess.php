<?php

/**
 * XeroAccess Kontroller
 * ---------------------
 * Author : Moore @ blocklistpro.com
 * A simple access control authorisation system to help protect the Administrator login page
 * against unauthorised access - with extra authentication options
 * http://admin.login.com/administrator/index.php?xscode=supersecretcode
 */

/**
 * Report all errors, dont show errors
 */
// error_reporting(E_ALL);
// ini_set('display_errors',0);

function eResponse()
{
    if( empty( $_SERVER['SERVER_PROTOCOL'] )){
        header('HTTP/1.0 404 Not Found');
    } elseif( preg_match( '#^(HTTP/(0\.9|1\.[01]))\z#' , $_SERVER['SERVER_PROTOCOL'] , $matches )){
        header($matches[0] . ' 404 Not Found');
    }
    // * Add no cache headers
    echo'<h1>404 Page Not Found</h1>';
    echo '<p>The page you requested does not exist on this server</p>';
    // * log errors + count attempts
    // log( 'Unauthorised Access' , $profile );
    exit;
}


/**
 * Auth Master Key
 */

$admin_auth = false;

/**
 * Access Code + Authentication Options
 * ------------------------------------
 * Uncomment a line to use an extra auth option
 * Comment out a line to turn an option off
 * Authenticate a user by STATIC IP or DYNAMIC IP [Regex]
 * The Referer / Useragent token check only works with the dynamic whitelist check
 *
 */

$axs = array(
    'request_code'          => 'supersecretcode'
    //, 'static_ip_whitelist'   => array( '121.1.1.2' , '121.1.1.3' ) // full ip's
    //, 'dynamic_ip_whitelist'  => '#^121\.1\.1\.1[23]#' // regex only! - partial ip
    //, 'referer_token'         => 'ref token'
    //, 'useragent_token'       => 'ua token'
);

// Visitor Profile
// Only allow IP access from IPv4 - Auto deny IPv6
$profile = array(

    'ip'  => !empty( $_SERVER['REMOTE_ADDR'] ) && filter_var( $_SERVER['REMOTE_ADDR'] , FILTER_VALIDATE_IP , FILTER_FLAG_IPV4 )
             ? $_SERVER['REMOTE_ADDR']
             : '',

    'ua'  => !empty( $_SERVER['HTTP_USER_AGENT'] )
             ? strtolower( $_SERVER['HTTP_USER_AGENT'] )
             : '',

    'ref' => !empty( $_SERVER['HTTP_REFERER'] )
             ? strtolower( $_SERVER['HTTP_REFERER'] )
             : '',
);

/**
 * If IP Address and UserAgent is not empty, check for an access code in $_GET request
 */
if( !empty( $profile['ip'] ) && !empty( $profile['ua'] ))
{
    /**
     * If current request code matches our secret access code - set auth to true
     */
    if( isset( $axs['request_code'] ))
    {
        if( !empty( $axs['request_code'] ) && !empty( $_GET['xscode'] ))
        {
            if( preg_match( '#^' . $axs['request_code'] . '\z#' , trim( $_GET['xscode'] ))){
                $admin_auth = true;
            }
        }
    }
}

/**
 * Static IP WhiteList
 * ------------------------------------------------------
 * If auth code was correct but IP is not whitelisted -> reset auth to false
 */
if( $admin_auth && isset( $axs['static_ip_whitelist'] ))
{
    if( !empty( $axs['static_ip_whitelist'] ))
    {
        if( !in_array( $profile['ip'] , $axs['static_ip_whitelist'] )){
            $admin_auth = false;
        }
    }
}

/**
 * Dynamic IP Whitelist + Referer | Useragent Token Check
 * ------------------------------------------------------
 * If auth code was correct but IP is not whitelisted -> reset auth to false
 * If Referer or Useragent token check is enabled and
 * tokens are not found in $profile -> reset auth to false
 *
 */
if( $admin_auth && isset( $axs['dynamic_ip_whitelist'] ))
{
    if( !empty( $axs['dynamic_ip_whitelist'] ))
    {
        if( !preg_match( $axs['dynamic_ip_whitelist'] , $profile['ip'] ))
        {
            // IP is not white listed - reset auth
            $admin_auth = false;

        } else {

            if( isset( $axs['useragent_token'] ))
            {
                if( !empty( $axs['useragent_token'] ))
                {
                    if( !stripos( $profile['ua'] , $axs['useragent_token'] )){
                    // Useragent token match not found - reset auth
                    $admin_auth = false;
                }
                }
            }

            if( $admin_auth && isset( $axs['referer_token'] ))
            {
                if( !empty( $axs['referer_token'] ))
                {
                    if( !stripos( $profile['ref'] , $axs['referer_token'] )){
                        // Referer token match not found - reset auth
                        $admin_auth = false;
                    }
                }
            }
        }
    }
}

/**
 * if( ! Valid Authentication Key ) return 404 response
 */
if( false === $admin_auth ){
    eResponse();
}

/**
 * Protected Admin Login Form Example
 */

?>
<h1>Admin Login Test</h1>
<form action="" method="post">
    <label for="Login">Login : </label>
    <input type="text" name="login" id="Login" value="" />
    <button type="submit">Login</button>
</form>
