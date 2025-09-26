<?php


class Checa
{
    public static function checarEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }
    }


}