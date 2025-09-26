<?php

class Rota
{
    /**
     * @var AuthMiddleware
     */

    private $controlador = 'PaginasController';
    private $metodo = 'index';
    private $parametros = [];

    public function __construct()
    {
        $url = $this->url() ? $this->url() : [];

        // Monta a chave da rota usando os dois primeiros segmentos
        $caminho = isset($url[0]) ? $url[0] : '';
        if (isset($url[1])) {
            $caminho .= '/' . $url[1];
        }

        // Busca a rota na tabela
        $rota = $this->destino($caminho);

        // Executa middlewares, se houver
        if (!empty($rota['middleware'])) {
            foreach ($rota['middleware'] as $middleware) {
                include_once __DIR__ . '/../Middlewares/' . $middleware . '.php';
                $middleware::handle(); // cada middleware deve ter método estático handle()
            }
        }

        // Inclui o controller
        $controllerFile = '../app/Controllers/' . $rota['controller'] . '.php';
        if (!file_exists($controllerFile)) {
            die("Controller {$rota['controller']} não encontrado!");
        }
        include_once $controllerFile;

        // Instancia controller e define método
        $this->controlador = new $rota['controller'];
        $this->metodo = $rota['metodo'];

        // Remove segmentos usados e mantém parâmetros extras
        unset($url[0], $url[1]);
        $this->parametros = $url ? array_values($url) : [];

        // Chama o método do controller com parâmetros
        call_user_func_array([$this->controlador, $this->metodo], $this->parametros);
    }

    // Pega URL
    private function url()
    {
        $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
        if ($url) {
            $url = trim(rtrim($url, '/'));
            return explode('/', $url);
        }
        return [];
    }

    // Define tabela de rotas
    private function destino($caminho = '')
    {
        $rotas = [
            // Usuario
            'usuario/login'       => ['controller' => 'UsuarioController', 'metodo' => 'login', 'middleware' => []],
            'usuario/listar-todos'=> ['controller' => 'UsuarioController', 'metodo' => 'showAll', 'middleware' => ['AuthMiddleware']],
            'usuario/encontrar'   => ['controller' => 'UsuarioController', 'metodo' => 'findUserId', 'middleware' => ['AuthMiddleware']],


        ];

        $key = trim($caminho, '/');

        if (isset($rotas[$key])) {
            return $rotas[$key];
        }

        // rota padrão
        return ['controller' => 'PaginasController', 'metodo' => 'index', 'middleware' => []];
    }
}
