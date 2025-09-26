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

        // Usa toda a URL como caminho
        $caminho = implode('/', $url);

        // Busca a rota na tabela (incluindo parâmetros)
        $rota = $this->destino($caminho);

        // Valida o método HTTP
        $metodoRequisicao = $_SERVER['REQUEST_METHOD']; // GET, POST, PUT, DELETE
        if (isset($rota['metodoHttp']) && $rota['metodoHttp'] !== $metodoRequisicao) {
            http_response_code(405);
            die("Método {$metodoRequisicao} não permitido para esta rota.");
        }

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

        // Usa os parâmetros extraídos da rota dinâmica
        $this->parametros = $rota['parametros'] ?? [];

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

    // Define tabela de rotas com suporte a parâmetros dinâmicos
    private function destino($caminho = '')
    {
        // Inclui o arquivo de rotas e obtém o array
        $rotas = include __DIR__ . '/../rotas.php';

        // Busca a rota correspondente, incluindo parâmetros
        foreach ($rotas as $rotaPadrao => $dados) {
            $rotaRegex = preg_replace('/:\w+/', '(\w+)', $rotaPadrao);
            $rotaRegex = "#^" . $rotaRegex . "$#";

            if (preg_match($rotaRegex, $caminho, $matches)) {
                array_shift($matches); // remove a string completa
                $dados['parametros'] = $matches;
                return $dados;
            }
        }

        // rota padrão
        return [
            'controller' => 'PaginasController',
            'metodo' => 'index',
            'middleware' => [],
            'metodoHttp' => 'GET',
            'parametros' => []
        ];
    }

}
