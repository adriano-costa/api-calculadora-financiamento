## API Calculadora Financiamento

Projeto simples que implementa uma api para uma calculadora de financiamentos.
Esse projeto foi desenvolvido como atividade avaliativa para participação no processo seletivo para o Hackathon Caixa VITEC 2023.

## 🚀 Começando

Essas instruções permitirão que você obtenha uma cópia do projeto em operação na sua máquina local para fins de desenvolvimento e teste.

Consulte **[Implantação](#-implanta%C3%A7%C3%A3o)** para saber como implantar o projeto.

### 📋 Pré-requisitos

Para execução deste projeto é necessário ter instalado em sua máquina o runtime do [PHP](https://www.php.net/) em versão 8.2.6 ou superior. Para gerenciamento das dependências do projeto é necessário ter instalado o [Composer](https://getcomposer.org/).
Sendo um projeto desenvolvido a partir do framework [Laravel](https://laravel.com/), é necessário ter instaladas as extenções necessárias ao laravel instaladas conforme descrito na [documentação](https://laravel.com/docs/10.x/deployment#server-requirements).
Adicionamente serão necessárias as extenções PHP [sqlsrv](https://www.php.net/manual/pt_BR/book.sqlsrv.php), [pdo_sqlsrv](https://www.php.net/manual/pt_BR/ref.pdo-sqlsrv.php), [bcmath](https://www.php.net/manual/en/book.bc.php) e [GMP](https://www.php.net/manual/en/book.gmp.php).

### 🔧 Instalação

Uma vez baixado ou código ou clona o repositório, você pode instalar as dependências do projeto executando o comando:

```bash
composer install
```

Após a instalação das dependências, é necessário configurar o arquivo .env com as informações de conexão com o banco de dados. Para isso, copie o arquivo .env.example para .env e edite as informações de conexão com o banco de dados e com o [EventHub](https://learn.microsoft.com/pt-br/azure/event-hubs/event-hubs-about).
Caso opte por usar o banco [sqlite](https://www.sqlite.org/index.html), o arquivo de banco de dados deve ser criado manualmente (database.sqlite) dentro da pasta database.
Após configuração do arquivo .env, o banco de dados pode ser inicializado com o comando

```bash
php artisan migrate:fresh --seed
```

Para subir um servidor local para testes, execute o comando

```bash
php artisan serve
```

Com o servidor rodando, você pode usar um cliente REST como o [Insomnia](https://insomnia.rest/) ou [Postman](https://www.postman.com/) para testar a API.
A api responde na raiz do endereço do servidor que foi iniciado no passo anterior. Os parametros de entrada devem ser passados via POST com os dados no formato JSON. O formato de resposta também é JSON.
Exemplo de formato do corpo da requisição:

```json
{
    "valorDesejado": 900,
    "prazo": 5
}
```

## ⚙️ Executando os testes

Para executar a suite de testes, execute o comando

```bash
php artisan test
```

## 📦 Implantação

No arquivo dockerfile estão as instruções para criação de uma imagem [docker](https://www.docker.com/) para execução do projeto. Para criar a imagem, execute o comando

```bash
docker build -t nomeDaImagem .
```

## 🛠️ Construído com

-   [PHP](https://www.php.net/) - Linguagem de programação
-   [Laravel](https://laravel.com/) - O framework web usado
-   [Composer](https://getcomposer.org/) - Gerenciador de dependências
-   [Brick/Math](https://github.com/brick/math) - Biblioteca para cálculos matemáticos
-   [PHPUnit](https://phpunit.de/) - Framework para testes automatizados
-   [Docker](https://www.docker.com/) - Plataforma para execução de aplicações em containers
-   [VsCode](https://code.visualstudio.com/) - Editor de código

## 📄 Licença

Este projeto está sob a licença [MIT](https://www.mit.edu/~amini/LICENSE.md).

## Descrição Geral do Projeto

O projeto segue a estrutura de projeto padrão do framework Laravel, mas com uma mudança em relação ao agrupamento de classes relacionadas as regras de negócio sendo agrupadas no namespace 'App\Domain'.

### Principais Arquivos e Pastas

-   **app\Http\Requests\SimulacaoRequest**: Classe que valida se a requisição possui os parametros obrigatórios.
-   **app\Http\Controllers\SimulacaoController**: Classe que recebe a requisição validada e chama os services que vão produzir a resposta.
-   **app\Domain\Produtos\MontaRespostaSimulacaoService**: Classe que recebe os parametros da requisição e produz a resposta.
-   **app\Domain\Produtos\IdentificacaoProdutoService**: Classe que consulta qual produto se enquadra nos parametros fornecidos.
-   **app\Domain\Produtos\SimulacaoProdutoService**: Classe que efetua as simulações de financiamento. Conforme classes de calculo de simulação passadas como parametro.
-   **app\Domain\Financiamento**: Pasta que contém os services que efetuam os cálculos de financiamento para cada sistema de amortização.
-   **app\Domain\EventHub\NotificarEventHubService**: Classe que efetua o envio das simulações realizadas para o EventHub conforme especificado.
