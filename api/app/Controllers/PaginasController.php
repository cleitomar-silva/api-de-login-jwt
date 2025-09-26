<?php


class PaginasController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
      //  $this->view('home/inicio');
      $this->erro();
    }

    public function erro()
    {
        $this->view('home/erro');
    }
}