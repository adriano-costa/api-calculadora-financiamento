# Deploy da aplicação no Azure usando GitHub Actions

Segue abaixo descrição de como configurar um fluxo de trabalho de integração e entrega contínua (CI/CD) usando GitHub Actions e hospedagem da Azure.

## Pré-requisitos

-   Faça fork desta aplicação no Github ou suba uma cópia dela para o seu repositório
-   Crie uma conta na Azure

## Passos

1. Crie um Web App Service no Azure Portal.
2. Configure as variáveis de ambiente do Web App Service, instruções [aqui](https://learn.microsoft.com/pt-br/azure/app-service/tutorial-php-mysql-app#2---set-up-database-connectivity). As variáveis de ambiente são as mesmas descritas no arquivo .env.
3. O arquivo deploy_master.yml contém um fluxo de trabalho de integração continua executado através de Github Actions. O fluxo é executado a push para a branch master. Detalhes de como configurar o fluxo de trabalho para apontar para um novo app podem ser encontrados [aqui](https://learn.microsoft.com/pt-br/azure/app-service/app-service-sql-asp-github-actions). O caminho mais simples é aceitar o arquivo de deploy que o Wizard do Azure irá criar e depois portar para ele as configurações de testes do arquivo deploy_master.yml.
4. Caso esteja usando um banco novo (sem a tabela de produtos), conecte-se a uma instancia da sua aplicação via SSH, navegue até a pasta /home/site/wwwroot e execute o comando `php artisan migrate --seed` para criar a tabela de produtos.
5. Certifique-se de ajustar o valor da variavel APP_RATE_LIMIT_PER_MINUTE com um valor adequado ao volume de requisições que você espera que a aplicação receba da sua aplicação.
