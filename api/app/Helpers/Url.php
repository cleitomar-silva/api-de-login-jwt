<?php


class Url
{
    public static function redirecionar($url)
    {
        header("location: ".URL."/".$url);

        die();
    }
}