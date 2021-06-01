<?php


namespace App\Helpers;


class helpers
{

    function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }

}
