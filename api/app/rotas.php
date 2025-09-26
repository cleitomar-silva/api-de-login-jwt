<?php
return [
    // Usuario
    'usuario/login'         => ['metodoHttp' => 'POST', 'controller' => 'UsuarioController', 'metodo' => 'login', 'middleware' => []],
    'usuario/listar-todos'  => ['metodoHttp' => 'GET', 'controller' => 'UsuarioController', 'metodo' => 'showAll', 'middleware' => ['AuthMiddleware']],
    'usuario/encontrar/:id' => ['metodoHttp' => 'GET', 'controller' => 'UsuarioController', 'metodo' => 'findUserId', 'middleware' => ['AuthMiddleware']],

    //
];
