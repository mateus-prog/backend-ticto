# ⏱️ Desafio Técnico – Registro de Ponto Eletrônico (Laravel)

## 🚀 Sobre o Projeto

Este projeto foi desenvolvido como parte do processo seletivo para a vaga de **Desenvolvedor Back-end Sênior**.  
O objetivo principal é implementar uma aplicação simples de **registro de ponto eletrônico**, com foco em boas práticas de Laravel, uso correto de Eloquent e SQL puro, organização de código e versionamento limpo.

---

## 🛠️ Tecnologias Utilizadas

- **PHP (Laravel - última versão estável)**
- **MySQL (InnoDB Engine)**
- **Composer**
- **Blade (opcional para visualizações)**
- **Bootstrap ou template administrativo (opcional)**
- **API ViaCEP** para consulta de endereço via CEP

---

## 🧩 Funcionalidades

### 👨‍💼 Funcionário
- Autenticação via login
- Registro de ponto (com apenas um botão)
- Troca de senha

### 🛠️ Administrador
- CRUD completo de funcionários
- Visualização de pontos registrados por qualquer funcionário
- Filtro de registros por período (data inicial e final)

---

## 📋 Campos Obrigatórios dos Funcionários

- Nome completo
- CPF (com validação de formato e duplicidade)
- E-mail
- Senha (criptografada)
- Cargo
- Data de nascimento
- CEP (com preenchimento automático do endereço via API)
- Endereço completo

---

## 🧱 Banco de Dados

- Migrations e relacionamentos implementados usando **Eloquent ORM**
- Relacionamento entre Funcionário e Administrador (um para muitos)
- Campos indexados corretamente
- **Consulta especial em SQL puro** para listagem dos registros de ponto com:
  - ID do registro
  - Nome do funcionário
  - Cargo
  - Idade
  - Nome do gestor
  - Data e hora do registro (com segundos)

---

## 📦 Instalação do Projeto

### Pré-requisitos
- PHP 8.1+
- Composer
- MySQL

### Passos para rodar localmente

```bash
# Clone o repositório
git clone https://github.com/mateus-prog/backend-ticto.git

# Acesse o diretório
cd nome-do-repo

# Copie o arquivo .env
cp .env.example .env

# Suba os containers
docker-compose up -d

# Acesse o container app
docker exec -it nome-do-container-app bash

# Dentro do container: instale dependências e gere a key
composer install
php artisan key:generate

# Rode as migrations
php artisan migrate

# (Opcional) Rode seeders
php artisan db:seed

#Query para extrair relatorios
SELECT
    t.id AS registro_id,
    CONCAT(u.first_name, ' ', u.last_name) AS nome_funcionario,
    u.position AS cargo,
    TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) AS idade,
    CONCAT(m.first_name, ' ', m.last_name) AS nome_gestor,
    CONCAT(t.entry_date, ' ', t.entry_time) AS data_hora_registro
FROM time_entries t
JOIN users u ON u.id = t.user_id
LEFT JOIN users m ON u.manager_id = m.id
ORDER BY t.entry_date DESC, t.entry_time DESC;
