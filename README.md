# API de Login JWT

Este projeto é uma API em PHP que implementa autenticação utilizando **Tokens (JWT)**.  
Ele serve como base para sistemas que precisam de login seguro, controle de sessão e gerenciamento de usuários.

## Requisitos

- PHP >= 7.4
- Servidor Apache com `mod_rewrite` habilitado
- MySQL (ou outro banco configurado no projeto)
---


##  Instalação

git clone https://github.com/cleitomar-silva/api-de-login-jwt.git

# Configuração
1. Verifique o arquivo `public/.htaccess`
2. Importante: o nome da pasta onde o projeto está deve corresponder ao caminho configurado no .htaccess
3. Configurações do banco de dados podem ser encontradas em `app/Libraries/Database.php`.

## ROTAS

Rotas são encontradas em `app/rotas.php`

### Estrutura de rota

Cada rota é composta por:

**Caminho na web:** `{descricao}/{descricao}/{:param}/{:param}`

- Exemplo 1: `usuario/login`

- Exemplo 2: `usuario/encontrar/:id`

#### Exemplo de Requisição
`GET http://localhost/api-de-login-jwt/api/usuario/encontrar/160`

#### Headers:
 - **x-access-token: SEU_TOKEN_AQUI**


**configuração da rota:**
 
```
Route::get('usuario/encontrar/:id', [UsuarioController::class, 'findUserId'])->middleware('AuthMiddleware');
```
- `usuario/encontrar/:id` → O `:id` é um parâmetro dinâmico que será passado para o método do controller.

- `GET` → Esse endpoint só aceita requisições do tipo GET.

- `UsuarioController` → O controller responsável por tratar a rota.

- `findUserId` → Método dentro do controller que será chamado

- `middleware('AuthMiddleware')` → Define que o usuário precisa estar autenticado para acessar essa rota.





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

**Retorno esperado:**

```
{
  "user": {
    "nome": "Cleitomar",
    "id": 160
  },
  "type": "sucesso",
  "token": "eyJ0eXAiRXJ7r1KeJV7VqzlW9nWbIG6Y111ixo",
  "permissoes": ""
}
```

## Listar Usuários
**Endpoint:**

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

   
