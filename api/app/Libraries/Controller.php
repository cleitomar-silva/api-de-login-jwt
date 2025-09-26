<?php


class Controller
{

    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function model($model)
    {
        include_once "../app/Models/".$model.".php";
        return new $model;
    }

    public function helpers($helper)
    {
        include_once "../app/Helpers/".$helper.".php";
        return new $helper;
    }

    public function view($view, $dados = [])
    {
        $arquivo = ("../app/Views/".$view.".php");
        if(file_exists($arquivo))
        {
            include_once $arquivo;
        }else{
            die("O arquivo de visualização não existe!");
        }

    }

}