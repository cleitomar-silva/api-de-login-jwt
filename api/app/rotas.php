<?php

// Usuário
Route::post('usuario/login', [UsuarioController::class, 'login']);
Route::get('usuario/listar-todos', [UsuarioController::class, 'showAll'])->middleware('AuthMiddleware');
Route::get('usuario/encontrar/:id', [UsuarioController::class, 'findUserId'])->middleware('AuthMiddleware');
