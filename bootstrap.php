<?php
/*
*   Plugin Name: Instagram Judo
*   Plugin URI: http://www.iansvoboda.com
*   Description: A simple plugin for pulling instagram feeds.
*   Author: Ian Svoboda
*   Version: 1.0.0
*   Author URI: http://www.iansvoboda.com
*   Contributing Author: Ian Svoboda
*/
require_once('inc/instagram-judo.php');
 
function instagram_judo_required_classes_exist()
{
    $class_names = array(
        'Instagram_Judo'
    );
 
    foreach($class_names as $class_name)
    {
        if(! class_exists($class_name))
        {
            return false;
        }
    }
 
    return true;
}
 
if( instagram_judo_required_classes_exist() )
{
    $instagram_judo = new Instagram_Judo;
}

// https://www.instagram.com/iansvo/?__a=1
