<?php

function lang($phrase)
{
    static $lang = array(
        "masseg"                    => "welcome",
        "admin"                     => "adminstration",
                //navbar phrase Linkes
        "HOME_ADMIN"                => "Admin",
        "Catacties"                 => "Catacties",
            "Edite_Profile"             => "Edite_Profile",
            "Stinge"                    => "Stinge",
            "LogOut"                    => "LogOut",
        "Items"                     => "Items",
        "Members"                   => "Members",
        "Statices"                  => "Statices",
        "Logs"                      => "Logs",
    );
    return $lang[$phrase];
}


//
//$lang = array(
//    'ayman' => 'gawad'
//);
//echo $lang['ayman'];
?>