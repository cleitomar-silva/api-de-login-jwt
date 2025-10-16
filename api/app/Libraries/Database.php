<?php



class Database
{
    private $host = 'localhost';
    private $usuario = 'root';
    private $senha = 'root';
    private $banco = 'protocolo';
    private $stmt;
    private $dbh;


    public function __construct()
    {
        $opcoes = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ];

        try {
            $this->dbh = new PDO('mysql:host='.$this->host.';dbname='.$this->banco, $this->usuario, $this->senha, $opcoes);

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($parametro, $valor, $tipo = null)
    {
        if(is_null($tipo))
        {
            switch (true)
            {
                case is_int($valor):
                    $tipo = PDO::PARAM_INT;
                break;
                case is_bool($valor):
                    $tipo = PDO::PARAM_BOOL;
                break;
                case is_null($valor):
                    $tipo = PDO::PARAM_NULL;
                break;
                default:
                    $tipo = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($parametro, $valor, $tipo);
    }

    public function executa()
    {
       return $this->stmt->execute();
    }

    public function resultado()
    {
        $this->executa();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function resultados()
    {
        $this->executa();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function totalResultados()
    {
        return $this->stmt->rowCount();
    }

    public function ultimoIdInserido()
    {
        return $this->dbh->lastInsertId();
    }




}