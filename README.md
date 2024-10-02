# FIAP Tech Challenge App - Automação com GitHub Actions

Este repositório contém o código do aplicativo (regras de negócio) e os arquivos docker e Kubernetes para aplicação no cluster EKS usando GitHub Actions.

## Link do Vídeo com a explicação e execução do projeto

- Kubernetes: [https://youtu.be/pG4ELz6mip8](https://youtu.be/pG4ELz6mip8) .
- Terraform|Cloud|CICD: Placeholder

<a href="https://youtu.be/pG4ELz6mip8">
    <img width="100%" alt="01 Captura de tela" src="https://github.com/user-attachments/assets/dc89700a-d777-4754-ac7f-d2c4b73764de">
</a>

## Intergrantes - GRUPO 47

- RM354121 - Lucas
- RM354259 - Thiago
- RM353824 - Raphael
- RM355935 - Lucas
- RM354852 - Mauro

## Repositórios Relacionados

- Original
https://github.com/LucasGarbuyo/fiap-tech-challenge

- App
https://github.com/LucasMinikel/fiap-tech-challenge-app

- Cognito
https://github.com/LucasMinikel/fiap-tech-challenge-cognito

- RDS
https://github.com/LucasMinikel/fiap-tech-challenge-rds

- EKS
https://github.com/LucasMinikel/fiap-tech-challenge-eks

## Visão Geral

Este projeto utiliza GitHub Actions para automatizar o processo de build, push e deploy da aplicação em um cluster EKS na AWS. O pipeline inclui a construção de imagens Docker, push para o Docker Hub e deploy no Kubernetes.

## Pré-requisitos

1. Uma conta AWS com acesso ao EKS.
2. Uma conta no Docker Hub.
3. Um cluster EKS já configurado.
4. Secrets configurados no GitHub.

## Configuração do Repositório

### 1. Fork do Repositório

Faça um fork deste repositório para sua conta do GitHub.

### 2. Adicionar Política ao Usuário que Criou o Cluster
> **Importante:** Não crie um novo usuário, utilize o mesmo que foi usado para a criação do cluster.
Adicione as permissões necessárias para acessar e aplicar a configuração do cluster utilizando a política abaixo. Lembre-se de gerar uma nova.

Política IAM (JSON)
```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "eks:DescribeCluster",
                "eks:ListClusters"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "ec2:DescribeInstances",
                "ec2:DescribeSecurityGroups",
                "ec2:DescribeSubnets",
                "ec2:DescribeVpcs",
                "ec2:CreateSecurityGroup",
                "ec2:AuthorizeSecurityGroupIngress",
                "ec2:CreateTags"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "elasticloadbalancing:CreateLoadBalancer",
                "elasticloadbalancing:DeleteLoadBalancer",
                "elasticloadbalancing:DescribeLoadBalancers",
                "elasticloadbalancing:ModifyLoadBalancerAttributes",
                "elasticloadbalancing:ConfigureHealthCheck",
                "elasticloadbalancing:RegisterInstancesWithLoadBalancer",
                "elasticloadbalancing:DeregisterInstancesFromLoadBalancer",
                "elasticloadbalancing:CreateTargetGroup",
                "elasticloadbalancing:DeleteTargetGroup",
                "elasticloadbalancing:DescribeTargetGroups",
                "elasticloadbalancing:ModifyTargetGroup",
                "elasticloadbalancing:RegisterTargets",
                "elasticloadbalancing:DeregisterTargets"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "iam:GetRole",
                "iam:ListRoles",
                "iam:PassRole"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "ecr:GetAuthorizationToken",
                "ecr:BatchCheckLayerAvailability",
                "ecr:GetDownloadUrlForLayer",
                "ecr:GetRepositoryPolicy",
                "ecr:DescribeRepositories",
                "ecr:ListImages",
                "ecr:BatchGetImage",
                "ecr:InitiateLayerUpload",
                "ecr:UploadLayerPart",
                "ecr:CompleteLayerUpload",
                "ecr:PutImage"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": "secretsmanager:GetSecretValue",
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "cloudformation:DescribeStacks",
                "cloudformation:ListStacks"
            ],
            "Resource": "*"
        }
    ]
}
```

### 3. Configurar Secrets no GitHub

Para que o workflow funcione corretamente, você precisa configurar os seguintes secrets no seu repositório GitHub:

1. No repositório GitHub, vá para **Settings** > **Secrets and variables** > **Actions** > **New repository secret**.
2. Adicione os seguintes secrets:

   - `AWS_ACCESS_KEY_ID`: Sua AWS Access Key ID.
   - `AWS_SECRET_ACCESS_KEY`: Sua AWS Secret Access Key.
   - `DOCKER_USERNAME`: Seu nome de usuário do Docker Hub.
   - `DOCKER_PASSWORD`: Sua senha do Docker Hub.

### 4. Configurar Variáveis de Ambiente

O workflow usa algumas variáveis de ambiente que você pode precisar ajustar:

```yaml
env:
  AWS_REGION: sa-east-1
  CLUSTER_NAME: tech-challenge-cluster
  FPM_IMAGE_NAME: fpm_server
  WEBSERVER_IMAGE_NAME: webserver
```

## Estrutura do Projeto

- `.infra/docker/`: Contém os Dockerfiles para o FPM server e o webserver.
- `.infra/k8s/`: Contém os arquivos YAML de configuração do Kubernetes.
    - `common/`: Configurações comuns do Kubernetes.
    - `fpm/`: Configurações específicas para o FPM server.
    - `webserver/`: Configurações específicas para o webserver.

## Workflows do GitHub Actions

### 1. Build, Push e Deploy

Este workflow é acionado em cada push para a branch `main`. Ele realiza as seguintes ações:

- Faz checkout do código.
- Configura as credenciais da AWS.
- Faz login no Docker Hub.
- Constrói e faz push das imagens Docker para o FPM server e webserver.
- Recupera secrets do AWS Secrets Manager.
- Atualiza a configuração do Kubernetes.
- Aplica as configurações comuns do Kubernetes.
- Cria ou atualiza secrets no Kubernetes.
- Aplica as configurações específicas do FPM e webserver.
- Verifica o status do rollout.
- Exibe o IP externo dos serviços.

### 2. Destroy Infrastructure

Este workflow é acionado manualmente e é responsável por destruir a infraestrutura. Ele:

- Remove os recursos Kubernetes.
- Remove os secrets.
- Verifica a remoção dos recursos.

Para acionar este workflow, vá para a aba "Actions" no GitHub, selecione "Destroy Infrastructure" e siga as instruções.

## Uso

1. Crie uma branch.
2. Faça suas alterações no código na branch.
3. Abra um pull request para a branch `main`.
4. De merge no pull request.
4. O GitHub Actions irá automaticamente construir, fazer push e implantar sua aplicação.
5. Você pode acompanhar o progresso na aba "Actions" do GitHub.

## Notas Importantes

- Certifique-se de que seu cluster EKS está configurado corretamente e acessível com as credenciais fornecidas.
- Revise cuidadosamente as configurações do Kubernetes antes de aplicá-las.
- O workflow de destruição da infraestrutura deve ser usado com cautela, pois removerá todos os recursos implantados.

## Suporte

Para questões ou problemas, por favor, abra uma issue neste repositório.

## Documentação DDD - Domain Driven Design

Link para a documentação do DDD no site do Miro:

[https://miro.com/app/board/uXjVKQcty_w=/](https://miro.com/app/board/uXjVKQcty_w=/)

Documentação do sistema (DDD) com Event Storming, incluindo todos os passos/tipos de diagrama mostrados na aula 6 do
módulo de DDD, e utilizando a linguagem ubíqua, dos seguintes fluxos:

 - Realização do pedido e pagamento; 
 - Preparação e entrega do pedido.


## Passo a passo para inicialização da aplicação local

### Se tiver o Make instalado

Use os commandos: 

    `make start`

Para fazer a limpeza da aplicação, use o comando:

    `make clean`


### Se não tiver o Make instalado

1. Clone o repositório  
   `git clone https://github.com/LucasGarbuyo/fiap-tech-challenge.git`

2. Acesse a pasta do projeto com o terminal 

3. Copie o arquivo `.env.example` para `.env`    
   `cp .env.example .env`

4. Iniciando os containers do Docker.  
   Esse processo pode demorar um pouco na primeira vez que for executado, pois o docker irá baixar as imagens necessárias para a execução dos containers.  
   Execute o comando:    
   `docker-compose up -d`

5. Acesse o container da aplicação com o comando:  
   `docker exec -it php bash`

6. Para instalar as dependências do projeto, execute o comando dentro do container:  
   `composer install`

7. Crie uma chave para a aplicação com o comando:  
   `php artisan key:generate`

8. Para criar as tabelas no banco de dados, execute o comando:  
   `php artisan migrate:fresh`

9. Para popular o banco de dados, execute o comando:  
   `php artisan db:seed`

10. Acesse a aplicação com o endereço  
    [http://localhost:8100](http://localhost:8100)

11. Acesse o Swagger com o endereço  
    [http://localhost:8100/api/documentation](http://localhost:8100/api/documentation)

## Para remover a aplicação

### Se tiver o Make instalado, use o comando:

    `make clean`

### Se não tiver o Make instalado, siga os passos abaixo:

1. Execute o comando  
   `docker-compose down`
2. **(Recomendado)** excluir a pasta do mysql dentro de ./docker/database/volumes/mysql. Vai poupar espaço.  
   `rm -Rf ./docker/database/volumes/mysql`


## Kubernetes Localmente

### Requisitos
1. Docker 
2. Minikube

### Passo a passo para inicialização da aplicação

1. Inicie o Minikube 
   `minikube start`
2. Habilite o addon de Ingress no Minikube 
   `minikube addons enable ingress`
3. Habilite o addon de Metrics no Minikube 
   `minikube addons enable metrics-server`
4. Aplique os arquivos de deploy do Kubernetes
   `make kubectl-deploy-apply`
5. Para ver o endereço da aplicação, obtenha o IP do Minikube 
   `minikube ip`      
6. Para ver o endereço da aplicação, obtenha o IP do Minikube 
   `minikube dashboard`      

### Passo a passo para remover da aplicação

1. Remover os arquivos de deploy do Kubernetes
   `make kubectl-deploy-delete`
2. Pare o minikube
   `minikube stop`
3. Delete o minikube
   `minikube delete`
