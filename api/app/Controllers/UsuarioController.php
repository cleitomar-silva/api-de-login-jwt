<?php

require_once __DIR__ . '/../Libraries/php-jwt-main/src/JWT.php';
require_once __DIR__ . '/../Libraries/php-jwt-main/src/Key.php';

use Firebase\JWT\JWT;

class UsuarioController extends Controller
{

    /**
     * @var UsuarioModel
     */
    protected $model;

    /**
     * @var Utils
     */
    protected $helper;

    public function __construct()
    {
        parent::__construct();
        $this->model = $this->model("UsuarioModel");
        $this->helper = $this->helpers("Utils");

        // ex: $this->helper->inverteData("2025-09-26")
    }

    public function index()
    {
        // $this->view('home/erro');
        echo json_encode("Rota não encontrada!");
    }

    public function showAll()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

        $idUser    = $_SESSION['user']->userId;
        $idCompany = $_SESSION['user']->empresaId;

        $usuarios = $this->model->showAllUser($idCompany);

        if (!empty($usuarios)) {
            echo json_encode([
                "type" => "sucesso",
                "data" => $usuarios
            ]);
        } else {
            echo json_encode([
                "type" => "erro",
                "message" => "Nenhum usuário encontrado"
            ]);
        }
    }

    public function findUserId($id = null, $status = null)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

        $dado = (object)[];
        $dado->id = isset($id) && !empty(trim($id)) ? trim($id) : '';

        $idUser    = $_SESSION['user']->userId;
        $idCompany = $_SESSION['user']->empresaId;

        if ( empty($dado->id) ) {

            echo json_encode(["type" => "erro","msg"=>"ID obrigatório"]);
            return;
        }

        $usuario = $this->model->findUserId($dado->id,$idCompany);

        if (!empty($usuario)) {
            echo json_encode([
                "type" => "sucesso",
                "data" => $usuario
            ]);
        } else {
            echo json_encode([
                "type" => "erro",
                "message" => "Nenhum usuário encontrado"
            ]);
        }
    }

    public function login()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

        $_POST = json_decode(file_get_contents("php://input"),true);

        $dado = (object)[];
        $dado->login    = isset($_POST['login']) && !empty(trim($_POST['login'])) ? trim($_POST['login']) : '';
        $dado->password = isset($_POST['password']) && !empty(trim($_POST['password'])) ? trim($_POST['password']) : '';
        $dado->dateTime = date("Y-m-d H:i:s" );

        if(!$dado->login || !$dado->password)
        {
            echo json_encode(["type" => "erro","msg"=>"Informe os campos obrigátorios"]);
            return;
        }

        $findLogin = $this->model->findLogin($dado->login);

        if ( empty($findLogin) ) {

            echo json_encode(["type" => "erro","msg"=>"Login ou Senha inválidos."]);
            return;
        }

        // Caso usuário precise redefinir senha
        if ( $findLogin->senha === null && $findLogin->status === 1 )
        {
            echo json_encode(
                [
                    "id"=>$findLogin->id,
                    "login"=>$findLogin->login,
                    "msg"=> "Redefinir Senha",
                    "type"=> "redefinir"
                ]);
            return;
        }

        $passwordMatch = password_verify($dado->password, $findLogin->senha);

        if(empty($passwordMatch))
        {
            echo json_encode(
                [
                    "msg"=> "Login ou Senha inválidos.",
                    "type"=> "erro"
                ]);
            return;
        }

        if ($passwordMatch && $findLogin->status === 1)
        {
            $payload = [
                'userId'    => $findLogin->id,
                'empresaId' => $findLogin->empresa,
                'iat'       => time(),
                'exp'       => time() + 28800
            ]; // 8 horas

           $jwtToken = JWT::encode($payload, SECRET, 'HS256');

            echo json_encode(
                [
                    "user"   => ["nome"=>$findLogin->nome, "id"=>$findLogin->id],
                    "type" => "sucesso",
                    "token"=> $jwtToken,
                    "permissoes"=> "" //  TODO preencher aqui depois

                ]);
            return;
        }

        echo json_encode(
            [
                "msg"=> "Login ou Senha inválidos.",
                "type"=> "erro"
            ]);
    }
}