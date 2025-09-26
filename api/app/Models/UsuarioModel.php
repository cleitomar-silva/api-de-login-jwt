<?php


class UsuarioModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function showAllUser($idCompany)
    {
        $this->db->query("  SELECT * FROM usuario WHERE empresa = :company ");

        $this->db->bind("company", $idCompany );

        $retorno = $this->db->resultados();

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return [];
        }
    }

    public function findUserId($id,$idCompany)
    {
        $this->db->query("  SELECT * FROM usuario WHERE id = :id AND empresa = :company ");

        $this->db->bind("id", $id );
        $this->db->bind("company", $idCompany );

        $retorno = $this->db->resultado();

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return [];
        }
    }

    public function findLogin($login)
    {
        $this->db->query("SELECT * FROM usuario WHERE login = :login ");

        $this->db->bind("login", $login );

        $retorno = $this->db->resultado();

        if (!empty($retorno)) {
            return $retorno;
        } else {
            return [];
        }
    }
}