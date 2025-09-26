<?php

class Rota
{
    private $controlador = 'PaginasController';
    private $metodo = 'index';
    private $parametros = [];

    public function __construct()
    {
        $url = $this->url() ? $this->url() : [];
        $caminho = implode('/', $url);

        // inclui rotas e carrega todas
        include_once __DIR__ . '/../rotas.php';
        $rotas = Route::all();

        $rota = $this->destino($caminho, $rotas);

        $metodoRequisicao = $_SERVER['REQUEST_METHOD'];
        if (isset($rota['metodoHttp']) && $rota['metodoHttp'] !== $metodoRequisicao) {
            http_response_code(405);
            die("Método {$metodoRequisicao} não permitido para esta rota.");
        }

        // executa middlewares
        if (!empty($rota['middleware'])) {
            foreach ($rota['middleware'] as $middleware) {
                include_once __DIR__ . '/../Middlewares/' . $middleware . '.php';
                $middleware::handle();
            }
        }

        // inclui controller
        $controllerFile = '../app/Controllers/' . $rota['controller'] . '.php';
        if (!file_exists($controllerFile)) {
            die("Controller {$rota['controller']} não encontrado!");
        }
        include_once $controllerFile;

        $this->controlador = new $rota['controller'];
        $this->metodo = $rota['metodo'];
        $this->parametros = $rota['parametros'] ?? [];

        call_user_func_array([$this->controlador, $this->metodo], $this->parametros);
    }

    private function url()
    {
        $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
        if ($url) {
            $url = trim(rtrim($url, '/'));
            return explode('/', $url);
        }
        return [];
    }

    private function destino($caminho, $rotas)
    {
        foreach ($rotas as $dados) {
            $rotaRegex = preg_replace('/:\w+/', '(\w+)', $dados['uri']);
            $rotaRegex = "#^" . $rotaRegex . "$#";

            if (preg_match($rotaRegex, $caminho, $matches)) {
                array_shift($matches);
                $dados['parametros'] = $matches;
                return $dados;
            }
        }

        return [
            'controller' => 'PaginasController',
            'metodo' => 'index',
            'middleware' => [],
            'metodoHttp' => 'GET',
            'parametros' => []
        ];
    }
}
