# LabSIS - Laboratório de Sistemas Inovadores

<div align="center">
  <img src="public/images/LabSIS.png" alt="LabSIS Logo" width="700" />
  <br>
  <em>Transformando desafios reais em soluções inteligentes</em>
</div>

<br>
<p align="center">
    <a href="https://filamentphp.com"><img alt="Filament v3" src="https://img.shields.io/badge/Filament-v4-eab308?style=for-the-badge"></a>
    <a href="https://laravel.com"><img alt="Laravel v12+" src="https://img.shields.io/badge/Laravel-v12+-FF2D20?style=for-the-badge&logo=laravel"></a>
    <a href="https://livewire.laravel.com"><img alt="Livewire v3" src="https://img.shields.io/badge/Livewire-v3-FB70A9?style=for-the-badge"></a>
    <a href="https://php.net"><img alt="PHP 8.3+" src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php"></a>
</p>

## Sobre o labSIS SaaS KIT

Este repositório é um Kit de Iniciação (Starter Kit) para o desenvolvimento de aplicações SaaS (Software as a Service) utilizando a stack TALL (Tailwind, Alpine.js, Laravel, Livewire) e Filament.

O objetivo deste projeto é fornecer uma base sólida e rica em recursos para acelerar o desenvolvimento de novas aplicações, seguindo as melhores práticas e convenções do ecossistema Laravel.

## Documentação do Kit

Toda a documentação sobre como utilizar os recursos, padrões e arquitetura deste kit está disponível na pasta [`/docs`](/docs). Recomendamos a leitura para todos os desenvolvedores que irão atuar neste projeto.

- [**Utilizando Enumerações (Enums) com Filament**](/docs/enums.md)

## Como realizar a instalação

Siga os passos abaixo para configurar o ambiente de desenvolvimento localmente.

**1. Clonar o Repositório**

Primeiro, clone este repositório para a sua máquina local utilizando Git:

```bash
git clone git@github.com:iurygdeoliveira/labSIS-SaaS-KIT-V4.git
cd labSIS-SaaS-KIT-V4
```

**2. Instalar Dependências (PHP e JS)**

Execute os comandos abaixo para instalar as dependências do Composer (backend) e do NPM (frontend).

```bash
composer install
npm install
```

**3. Configurar o Ambiente**

Copie o arquivo de exemplo `.env.example` para criar seu próprio arquivo de configuração `.env`. Em seguida, gere a chave da aplicação, que é essencial para a segurança da sua instância Laravel.

```bash
cp .env.example .env
php artisan key:generate
```

**4. Configurar o Banco de Dados**

Este projeto está configurado para utilizar sqlite. Execute as migrations para criar as tabelas no banco de dados. Para popular o banco com dados de exemplo, execute as seeders.

```bash
php artisan migrate --seed
```

**5. Compilar os Assets**

Compile os arquivos de frontend (CSS e JavaScript) utilizando o Vite.

```bash
npm run build
```

**6. Iniciar o Servidor de Desenvolvimento**

Finalmente, inicie o servidor de desenvolvimento local do Laravel.

```bash
php artisan serve
```

Sua aplicação estará disponível em `http://127.0.0.1:8000`. Para o painel administrativo, acesse `http://127.0.0.1:8000/admin`.

## Agradecimentos

Gostaríamos de expressar nossa sincera gratidão a todas as pessoas e equipes cujo trabalho tornou este projeto possível. Suas contribuições para a comunidade de código aberto são uma fonte constante de inspiração e um pilar fundamental para o nosso desenvolvimento.

Em especial, agradecemos a:

-   **Equipe Laravel**: Pela criação e manutenção de um framework robusto, elegante e inovador, disponível em [laravel/laravel](https://github.com/laravel/laravel).
-   **Equipe Filament**: Pelo incrível trabalho no [Filament](https://github.com/filamentphp/filament), que nos permite construir painéis administrativos complexos com uma velocidade e simplicidade impressionantes.
-   **Leandro Costa** ([@leandrocfe](https://github.com/leandrocfe)): Por suas valiosas contribuições e por compartilhar conhecimento de alta qualidade sobre Filament em seu canal [Filament Brasil no YouTube](https://www.youtube.com/@filamentbr), que foi fundamental para a implementação de diversas features neste projeto.
-   **Wallace Martins** ([@wallacemartinss](https://github.com/wallacemartinss)): Pela disponibilização do [website_template](https://github.com/wallacemartinss/website_template), que forneceu uma base excelente e moderna para a construção do portal público deste projeto.
-   **Jeferson Gonçalves** ([@jeffersongoncalves](https://github.com/jeffersongoncalves)): Pelo desenvolvimento do pacote [filament-cep-field](https://github.com/jeffersongoncalves/filament-cep-field), que agregou grande valor ao projeto ao fornecer um campo de formulário que busca e preenche automaticamente dados de endereço a partir de um CEP, otimizando a experiência do usuário.

O trabalho de vocês contribui significativamente para o avanço e a qualidade deste projeto.

## 🚀 Recursos Atuais

O Kit oferece uma base sólida com os seguintes recursos já implementados:

**Painel Administrativo (Filament)**
- **Segurança:**
  - **Autenticação de Dois Fatores (2FA):** Sistema de 2FA integrado ao perfil do usuário, compatível com aplicativos de autenticação (Google Authenticator, Authy, etc.).
  - **Códigos de Recuperação:** Geração de códigos de recuperação para acesso seguro em caso de perda do dispositivo de autenticação.
- **Gerenciamento de Usuários:**
  - CRUD completo para usuários (Criação, Leitura, Atualização e Exclusão).
  - Sistema de **autorização/suspensão** de usuários com registro de motivo.
  - Visualização de detalhes do usuário em abas, incluindo informações pessoais, datas de criação/verificação e status da conta.
  - Página de listagem com busca e badges visuais para o status do usuário (Autorizado/Suspenso).
  - Notificações de feedback para todas as ações administrativas (criação, edição, exclusão).

**Website / Landing Page**
- **Página Inicial Completa:** Uma landing page moderna e responsiva construída com componentes Blade e TailwindCSS.
- **Seções Pré-definidas:**
  - **Hero:** Seção principal de boas-vindas.
  - **Benefícios:** Lista de vantagens da plataforma.
  - **Como Funciona:** Guia visual do processo.
  - **Depoimentos:** Seção de prova social com scroll automático.
  - **Tabela de Preços:** Componente interativo com seleção de ciclo de pagamento (mensal/anual).
  - **FAQ:** Acordeão de perguntas e respostas.
- **Navegação Integrada:** Header e footer padronizados com links de navegação e acesso direto à plataforma (`/admin`).
 
## 📄 Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

## 👥 Autor

- **Iury Oliveira** - [@iurygdeoliveira](https://github.com/iurygdeoliveira)

---

<div align="center">
  <strong>LabSIS - Transformando desafios reais em soluções inteligentes</strong>
</div