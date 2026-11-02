# Chevette TV

<p align="center">
  <img src="logo.png" alt="Logo Chevette TV" width="180">
</p>

<h3 align="center">Sua central de entretenimento.</h3>

<p align="center">Site institucional e painel administrativo para apresentar os serviços, aplicativos, planos, dispositivos compatíveis e novidades do Chevette TV.</p>

---

## Sobre o projeto

O **Chevette TV** é uma aplicação web desenvolvida em PHP, HTML, CSS e JavaScript. A página inicial apresenta a plataforma e seus recursos. Um painel administrativo autenticado permite atualizar informações exibidas no site sem editar manualmente o código-fonte.

## Recursos

- Página inicial responsiva.
- Apresentação de aplicativos, dispositivos compatíveis, vantagens e planos.
- Painel administrativo com criação inicial de senha.
- Autenticação com hash de senha, sessão protegida e limite de tentativas.
- Proteção CSRF e validação/sanitização dos dados enviados.
- Armazenamento em arquivos, sem necessidade de banco de dados.
- Backup dos dados anteriores ao salvar.

## Tecnologias

- **PHP 8+** — lógica do servidor, autenticação e persistência.
- **HTML5** — estrutura da interface.
- **CSS3** — identidade visual e responsividade.
- **JavaScript** — interações e painel de edição.
- **Apache / .htaccess** — configuração do servidor.

## Estrutura

```text
chevette-tv-site/
├── .gitignore
├── .htaccess
├── README.md
├── LEIA-ME.txt
├── admin.php
├── config.php
├── config.local.php.example
├── index.php
├── lib.php
├── logo.png
├── logout.php
└── save.php
```

> A pasta `_data/` é criada no servidor e não deve ser versionada. Ela guarda os dados do painel e os arquivos de autenticação.

## Instalação

1. Hospede os arquivos em um servidor com **PHP 8 ou superior** e Apache compatível com `.htaccess`.
2. Envie os arquivos para a pasta pública do domínio, mantendo a estrutura.
3. Crie a pasta `_data/` no mesmo nível de `index.php` e conceda permissão de escrita ao PHP (normalmente 755 ou 775, conforme a hospedagem).
4. Copie `config.local.php.example` para `config.local.php` e substitua a chave de exemplo por uma chave aleatória e privada com pelo menos 32 caracteres.
5. Acesse `https://SEU-DOMINIO/admin.php` e conclua a criação da senha administrativa.
6. Abra a página inicial e use o botão de administração para editar o conteúdo.

**Importante:** `config.local.php` é privado e está excluído do Git. Nunca publique a chave de instalação, senha, arquivos de sessão ou conteúdo de `_data/`.

## Configuração privada

O arquivo `config.php` carrega `config.local.php`, quando presente. A chave também pode ser fornecida pela variável de ambiente `CHEVETTE_SETUP_KEY`. Não coloque segredos diretamente em arquivos versionados.

## Segurança e operação

- Ative HTTPS no painel da hospedagem.
- Não publique `config.local.php` nem a pasta `_data/`.
- Faça backup periódico de `_data/`.
- A chave antiga foi exposta no histórico público e não deve ser reutilizada.
- O GitHub Pages não executa PHP. Para o site funcionar, use hospedagem com PHP.

## Desenvolvedor

**Lucas Oliveira** — [@LucasChevette](https://github.com/LucasChevette)

Projeto: [github.com/LucasChevette/chevette-tv-site](https://github.com/LucasChevette/chevette-tv-site)
