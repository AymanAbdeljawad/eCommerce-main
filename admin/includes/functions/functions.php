<?php

//function spcjal title pages
function getTitle()
{
    global $pagetitle;
    if (isset($pagetitle)) {
        return $pagetitle;
    } else {
        $pagetitle = "defualttitle";
    }
    return $pagetitle;
}





?>