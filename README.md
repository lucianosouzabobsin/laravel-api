# CONTROLE DE ACESSO

Disponibiliza uma API REST que permite o cadastro de controle de acesso com grupos de usuários, módulos, ações e abilidades, somente com autorização token,
com exceção do Login.

## Métodos
Requisições para a API devem seguir os verbos:
| Método | Descrição |
|---|---|
| `GET` | Retorna informações de um ou mais registros. |
| `POST` | Utilizado para criar um novo registro. |

## Respostas

| Código | Descrição |
|---|---|
| `200` | Requisição executada com sucesso (success).|
| `201` | Requisição executada com sucesso, inserção de dados (success).|
| `401` | Dados de acesso inválidos (Unauthenticated).|
| `404` | Registro pesquisado não encontrado (Resource not found).|


## Tecnologias

* Laravel Framework 10.20.0
* Mysql-8
* PHP 8.2.9
* Docker

## Serviços Usados

* Github
* Postman

## Getting started

* Para subir o ambiente:
>   $ cd api/
>

Renomear o arquivo .env.example para .env

Alterar as variveis:

DB_HOST=mysql

DB_DATABASE={NOME DO BANCO}

DB_USERNAME={USUARIO}

DB_PASSWORD={SENHA}

SUPER_EMAIL={EMAIL SUPERADMIN}

SUPER_PASSWORD={SENHA SUPERADMIN}

Executar os comandos:

>	$ sail up -d (para construir o ambiente docker)
>
>   $ sail composer dumpautoload
>

* Ajuste de acesso ao usuário para o banco a ser criado

> $ docker exec -it  api-mysql-1 bash (Abrir o bash do container do mysql)
>
> mysql -u root -proot
>
> GRANT ALL PRIVILEGES ON DB_SYSTEM.* TO 'NOME DO USUARIO DO DB_USERNAME'@'%';
>
> FLUSH PRIVILEGES;
>
> EXIT;
>
> $ exit
>

* Rodar as migrations

>	 $ sail artisan migrate
>

The database 'DB_SYSTEM' does not exist on the 'mysql' connection. (Esta mensagem aparece caso o banco não exista, pois esta criando)

selecione Yes

* Rodar os seeds
>
>	 $ sail artisan db:seed
>
* Checar os testes (se quiser)
>    $ sail tests

## Como usar

Você pode usar o Postman para utilizar esta API.


## Recursos

Inicialmente deve fazer o login como super admin, para ter acesso ao token.

Rodar o endpoint "Run Abilities", para fazer o vínculo dos módulos, as ações dos módulos e as habilidades das ações dentro dos módulos.

Para criar um primeiro usuário que não seja superadmin (ID = 1), deve:

1. Criar o grupo de usuário (Exemplo Admin), usando o endpoint "User Group Create".
2. Fazer o vínculo das habilidades de módulos a este grupo, usando o endpoint "User Ability Create". Caso queira ver as habilidades, pode ser verificado utilizando o endpoint "Ability List", para consultar o ID.
3. Registrar o usuário, usando o endpoint "Register";

> Lembrar de setar o Header para ter todos os retornos corretos:
>
> Content-Type: application/json
>
> Accept: application/json

### Login

Login

#### Request

`POST /api/login`

    curl --location --request POST 'http://localhost/api/login?email=superadmin@test.com&password=12345678' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json'

### Run Abilities

Faz o vinculo das habilidades de módulos aos grupos existentes

#### Request

`GET /api/ability-run`

    curl --location 'http://localhost/api/ability-run' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'


### Ability List

Lista as habilidades

#### Request

`POST /api/ability-list`

    curl --location 'http://localhost/api/ability-list' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'

### Ability Create

Cria habilidade

#### Request

`POST /api/ability-create`

    curl --location --request POST 'http://localhost/api/ability-create?module_id=1&module_action_id=1&description=Teste&link=%2Fapi%2Fteste' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 226|NfcrhXvTP9Vd4xBKPojW0mY0LPWzc5ZGTuFVZpyK01e6fa33'


### Ability Active

Atualiza habilidade, somente ativo/inativo

#### Request

`POST /api/ability-active`


    curl --location --request POST 'http://localhost/api/ability-active?id=1' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 226|NfcrhXvTP9Vd4xBKPojW0mY0LPWzc5ZGTuFVZpyK01e6fa33'


### User Ability Create

Pode passar uma habilidade ou uma lista separada por virgula no campo "abilities_ids"

#### Request

`POST /api/user-group-ability-create`

    curl --location --request POST 'http://localhost/api/user-group-ability-create?user_group_id=2&abilities_ids=2,3,4,5' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'


### User

Pega as informações do usuário dono do token

#### Request

`POST /api/user`

    curl --location 'http://localhost/api/user' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'


### User Group List

Lista os grupos de usuários

#### Request

`POST /api/user-group-list`

    curl --location --request POST 'http://localhost/api/user-group-list' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'

### User Group Create

Cria grupo de usuários

#### Request

`POST /api/user-group-create`

    curl --location --request POST 'http://localhost/api/user-group-create?name=admin&description=administrator' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'


### User Group Update

Atualiza o grupo de usuário, somente descrição e ativo

#### Request

`POST /api/user-group-update`

    curl --location --request POST 'http://localhost/api/user-group-update?name=admin&description=Admin&id=2&active=1' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'

### User Group Active

Atualiza o grupo de usuário, somente ativo/inativo

#### Request

`POST /api/user-group-active`

    curl --location --request POST 'http://localhost/api/user-group-active?id=1' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 224|F8qn88WNINvZwggDd87WyYqLqfSg3ZlpNWGQyHFb55df59ce'

### Module List

Lista os módulos

#### Request

`POST /api/module-list`

    curl --location 'http://localhost/api/module-list' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 226|NfcrhXvTP9Vd4xBKPojW0mY0LPWzc5ZGTuFVZpyK01e6fa33'

### Module Create

Cria módulo

#### Request

`POST /api/module-create`

    curl --location --request POST 'http://localhost/api/module-create?name=usergroupability&description=usergroupability&nickname=user-group-ability' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 2|dFx6xFDHO2YGPIYPHK9762PXKlMaZkzwxtOVoKKw486aae33'

### Module Update

Atualiza módulo

#### Request

`POST /api/module-update`

    curl --location --request POST 'http://localhost/api/module-update?name=Teste&description=Teste%20de%20cadastro%20Alterado&id=1&active=0' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 198|yGxurNKciZr65zOeRBQdAl7QESo3hEVoYxRgk0pof951c2b1'

### Module Update

Atualiza o módulo, somente ativo/inativo

#### Request

`POST /api/module-active`

    curl --location --request POST 'http://localhost/api/module-active?id=1' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 198|yGxurNKciZr65zOeRBQdAl7QESo3hEVoYxRgk0pof951c2b1'

### Module Action List

Lista as actions

#### Request

`POST /api/module-action-list`

    curl --location 'http://localhost/api/module-action-list' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'

### Module Action Create

Cria action

#### Request

`POST /api/module-action-create`

    curl --location --request POST 'http://localhost/api/module-action-create?action=active' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 2|dFx6xFDHO2YGPIYPHK9762PXKlMaZkzwxtOVoKKw486aae33'

### Module Action Update

Atualiza action

#### Request

`POST /api/module-action-update`

    curl --location --request POST 'http://localhost/api/module-action-update?action=primeactionalterada&id=1&active=0' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 224|F8qn88WNINvZwggDd87WyYqLqfSg3ZlpNWGQyHFb55df59ce'

### Module Action Active

Atualiza a action, somente ativo/inativo

#### Request

`POST /api/module-action-active`

    curl --location --request POST 'http://localhost/api/module-action-active?id=1' \
    --header 'Cache-Control: no-cache' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 224|F8qn88WNINvZwggDd87WyYqLqfSg3ZlpNWGQyHFb55df59ce'


### Is logged in

Retorna se o usuário do token esta autenticado "Authenticated" ou não "Unauthenticated" na mensagem.

#### Request

`POST /api/is-logged-in`

    curl --location 'http://localhost/api/is-logged-in' \
    --header 'Accept: application/json' \
    --header 'Accept-Encoding: application/json' \
    --header 'Authorization: Bearer 1|CKrus0Z6eAL6SuxB2wwm0Mt4gsC1kbv4kNcBDI4r132bd4fa'


## Versionamento

1.0.0.0

## Autor

* **Luciano Bobsin**: @lucianosouzabobsin (https://github.com/lucianosouzabobsin)