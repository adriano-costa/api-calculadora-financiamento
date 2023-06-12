## API Calculadora Financiamento

Projeto simples que implementa uma API para uma calculadora de financiamentos.
Esse projeto foi desenvolvido como atividade avaliativa para participação em um processo seletivo.

## 🚀 Começando

Essas instruções permitirão que você obtenha uma cópia do projeto em operação na sua máquina local para fins de desenvolvimento e teste.

Consulte **[Descrição Geral do Projeto](#descrição-geral-do-projeto)** para saber mais sobre a organização do código.

### 📋 Pré-requisitos

Para execução deste projeto é necessário ter instalado em sua máquina o runtime do [PHP](https://www.php.net/) em versão 8.2.6 ou superior. Para gerenciamento das dependências do projeto é necessário ter instalado o [Composer](https://getcomposer.org/).
Sendo um projeto desenvolvido a partir do framework [Laravel](https://laravel.com/), é necessário ter instaladas as extensões necessárias ao Laravel instaladas conforme descrito na [documentação](https://laravel.com/docs/10.x/deployment#server-requirements).
Adicionalmente, serão necessárias as extensões PHP [SQLSRV](https://www.php.net/manual/pt_BR/book.sqlsrv.php), [PDO_SQLSRV](https://www.php.net/manual/pt_BR/ref.pdo-sqlsrv.php), [BCMATH](https://www.php.net/manual/en/book.bc.php) e [GMP](https://www.php.net/manual/en/book.gmp.php).

### 🔧 Instalação

Uma vez baixado ou código ou clona o repositório, você pode instalar as dependências do projeto executando o comando:

```bash
composer install
```

Após a instalação das dependências, é necessário configurar o arquivo .env com as informações de conexão com o banco de dados. Para isso, copie o arquivo .env.example para .env e edite as informações de conexão com o banco de dados e com o [EventHub](https://learn.microsoft.com/pt-br/azure/event-hubs/event-hubs-about).
Caso opte por usar o banco [sqlite](https://www.sqlite.org/index.html), o arquivo de banco de dados deve ser criado manualmente (database.sqlite) dentro da pasta 'database'.
Após configuração do arquivo .env, o banco de dados pode ser inicializado com o comando

```bash
php artisan migrate:fresh --seed
```

Para subir um servidor local para testes, execute o comando

```bash
php artisan serve
```

Com o servidor em execução, a documentação da API pode ser consultada através do endereço http://localhost:8000/api/documentation. A documentação foi gerada com uso do [Swagger](https://swagger.io/).
Você pode executar testes na API através da página da documentação ou usando um cliente REST como o [Insomnia](https://insomnia.rest/) ou [Postman](https://www.postman.com/).
Os parâmetros de entrada devem ser passados via POST com os dados no formato JSON. O formato de resposta também é JSON.
Exemplo de formato do corpo da requisição:

```bash
POST http://127.0.0.1:8000/v1/simulacao HTTP/1.1
content-type: application/json

{
    "valorDesejado": 1000,
    "prazo": 5
}
```

## ⚙️ Executando os testes

Para executar a suíte de testes, execute o comando

```bash
php artisan test
```

## 📦 Implantação

A explicação de como configurar um fluxo de CI/CD no Azure com uso de Github Actions pode ser encontrada no arquivo [deploy.md](docs/deploy.md).

## 🛠️ Construído com

-   [PHP](https://www.php.net/) - Linguagem de programação
-   [Laravel](https://laravel.com/) - O framework web usado
-   [Composer](https://getcomposer.org/) - Gerenciador de dependências
-   [Brick/Math](https://github.com/brick/math) - Biblioteca para cálculos matemáticos
-   [PHPUnit](https://phpunit.de/) - Framework para testes automatizados
-   [VsCode](https://code.visualstudio.com/) - Editor de código

## Descrição Geral do Projeto

O projeto segue a estrutura de projeto padrão do framework Laravel, mas com uma mudança em relação ao agrupamento de classes relacionadas as regras de negócio dentro do namespace 'App\Domain'.

A API para simulações é disponibilizada na URL '/v1/simulacao'. A API recebe os parâmetros de entrada via POST no formato JSON e retorna um JSON com a resposta da simulação.

Um evento é disparado para o EventHub com as simulações realizadas conforme especificado nos requisitos. Esse envio é feito através de um job Laravel executado após o envio da resposta ao cliente. A comunicação com o EventHub é realizada através da API REST do Azure. Conforme esta [análise](https://vincentlauzon.com/2018/06/05/event-hubs-ingestion-performance-and-throughput/) a opção pela API REST oferece menor latência, encurtando o tempo de processamento da requisição. Porém para volumes de milhares de requisições por segundo, a opção mais eficiente é usar um stream de eventos através do protocolo AMQP. Nesta segunda abordagem, cada requisição deve salvar os dados do evento num serviço de fila (ou banco de dados) e um worker rodando em outra instância faria o envio para o EventHub. Essa opção pode ser implementada com uso da extensão [Rdkafka](https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/book.rdkafka.html).

A API não possui restrição de acesso via autenticação, esse recurso não estava na especificação. Apesar de não haver identificação de usuários, a API conta com rate limit para evitar abusos de uso. O limite de requisições por IP por minuto é configurado nas variáveis de ambiente.

Visando evitar erros de arredondamento resultantes da aritmética de ponto flutuante, os cálculos são feitos com a biblioteca [Brick/Math](https://github.com/brick/math). Os valores retornados na resposta da API são arredondados para duas casas decimais e representados como float conforme apresentado na especificação.

### Principais Arquivos e Pastas

-   **app\Http\Requests\SimulacaoRequest**: Classe que valida se a requisição possui os parâmetros obrigatórios.
-   **app\Http\Controllers\SimulacaoController**: Classe que recebe a requisição validada e chama os services que vão produzir a resposta.
-   **app\Domain\Produtos\MontaRespostaSimulacaoService**: Classe que recebe os parâmetros da requisição e produz a resposta.
-   **app\Domain\Produtos\IdentificacaoProdutoService**: Classe que consulta qual produto se enquadra nos parâmetros fornecidos.
-   **app\Domain\Produtos\SimulacaoProdutoService**: Classe que efetua as simulações de financiamento. Conforme classes de cálculo de simulação passadas como parâmetro.
-   **app\Domain\Financiamento**: Pasta que contém os services que efetuam os cálculos de financiamento para cada sistema de amortização.
-   **app\Domain\EventHub\EventHubProducerService**: Classe que efetua o envio das simulações realizadas para o EventHub conforme especificado nos requisitos.
-   **.env**: Arquivo de configuração do Laravel. Contém as variáveis de ambiente que devem ser configuradas para o funcionamento da aplicação.
-   **.github\workflows\deploy_master.yml**: Arquivo de configuração do GitHub Actions para execução dos testes automatizados [GITHUB](https://github.com/) e deploy da aplicação para o serviço da Azure.
-   **dockerfile**: Arquivo de configuração do [DOCKER](https://www.docker.com/) para execução em ambiente de desenvolvimento.
-   **default-nginx-config**: Arquivo de configuração do servidor web [NGINX](https://www.nginx.com/) para execução em ambiente do Azure. Ele altera o diretório raiz do servidor para a pasta 'public' do projeto.

## 📄 Licença

Este projeto está sob a licença [MIT](https://www.mit.edu/~amini/LICENSE.md).
