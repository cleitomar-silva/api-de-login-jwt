# API de Login JWT

Este projeto é uma API em PHP que implementa autenticação utilizando **Tokens (JWT)**.  
Ele serve como base para sistemas que precisam de login seguro, controle de sessão e gerenciamento de usuários.

## 🚀 Requisitos

- PHP >= 7.4
- Servidor Apache com `mod_rewrite` habilitado
- MySQL (ou outro banco configurado no projeto)
---


##  Instalação

git clone https://github.com/cleitomar-silva/api-de-login-jwt.git

# Configuração
  1. Verifique o arquivo public/.htaccess 
  2. Importante: o nome da pasta onde o projeto está deve corresponder ao caminho configurado no .htaccess 
  3. Configurações do banco de dados podem ser encontradas em `app/Libraries/Database.php`.


##  Exemplos de Rotas

###  Login

**Endpoint:**

POST http://localhost/api-de-login-jwt/api/usuario/login

**Body (JSON):**
```json
{
  "login": "cleitomar",
  "password": "senha123"
}
```

Retorno esperado:

```
{
  "user": {
    "nome": "Cleitomar",
    "id": 160
  },
  "type": "sucesso",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjE2MCwiZW1wcmVzYUlkIjozLCJpYXQiOjE3NTg4OTEyNDEsImV4cCI6MTc1ODkyMDA0MX0.txv8YspG6IoHJRXJ7r1KeJV7VqzlW9nWbIG6Y111ixo",
  "permissoes": ""
}
```

## Listar Usuários
Endpoint:
    
GET http://localhost/api-de-login-jwt/api/usuario/listar-todos

**Headers necessários:**
- Key: x-access-token
- Value: 'token retornado no login'

---


## Estrutura do Projeto

```
app/
  Controllers/
  Helpers/
  Libraries/
  Middlewares/
  Models/
  Views/

public/
  css/
  files/
  img/
  js/
```

   
