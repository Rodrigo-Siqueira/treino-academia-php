# Treino Academia PHP

Aplicação didática para organização, registro e acompanhamento de treinos de academia, desenvolvida progressivamente com PHP moderno, orientação a objetos, testes automatizados e boas práticas de arquitetura de software.

> O principal objetivo deste repositório é documentar minha evolução técnica enquanto construo uma aplicação útil, testável e preparada para crescer de forma sustentável.

## Sobre o projeto

O projeto nasceu da necessidade de organizar fichas de treino, registrar séries realizadas e acompanhar informações objetivas sobre a evolução dos treinos.

Além da aplicação em si, o repositório é utilizado como laboratório de aprendizado para:

- PHP moderno;
- programação orientada a objetos;
- modelagem de domínio;
- testes automatizados;
- análise estática;
- padronização de código;
- Composer;
- Git e GitHub;
- PostgreSQL;
- Docker;
- Laravel;
- arquitetura de software.

O desenvolvimento é realizado em etapas. Cada novo recurso é introduzido somente depois da compreensão do problema, das responsabilidades envolvidas e dos testes necessários.

## Status do projeto

**Em desenvolvimento didático.**

O primeiro marco foi concluído em PHP puro, antes da introdução do Laravel e da persistência em banco de dados.

### Qualidade atual

- 59 testes automatizados;
- 72 asserções;
- análise estática com PHPStan no nível máximo;
- formatação automatizada com PHP CS Fixer;
- autoload PSR-4 com Composer;
- verificação completa por um único comando.

```bash
composer check
```

## Funcionalidades modeladas

### Exercícios

- criação de exercício com nome válido;
- normalização do nome;
- ativação e inativação;
- proteção contra transições inválidas.

### Séries realizadas

- associação com um exercício;
- registro de carga;
- registro de repetições;
- validação de valores;
- cálculo do volume da série.

### Sessões de treino

- estados `EM_ANDAMENTO`, `CONCLUIDA` e `CANCELADA`;
- conclusão e cancelamento controlados;
- validação dos horários;
- inclusão de séries;
- bloqueio de alterações após o encerramento;
- cálculo da duração;
- cálculo do volume total;
- preservação do estado após operações inválidas.

### Fichas de treino

- nome normalizado;
- planejamento por exercício;
- quantidade planejada de séries;
- faixa planejada de repetições;
- rejeição de exercício inativo;
- prevenção de exercícios duplicados;
- preservação de itens históricos;
- cálculo do total de séries planejadas.

## Estrutura atual

```text
treino-academia-php/
├── src/
│   ├── EstadoDaSessao.php
│   ├── Exercicio.php
│   ├── FichaDeTreino.php
│   ├── ItemDaFicha.php
│   ├── SerieRealizada.php
│   ├── SessaoDeTreino.php
│   └── SessaoEncerrada.php
├── tests/
│   └── Unit/
├── composer.json
├── composer.lock
├── phpunit.xml
└── .php-cs-fixer.dist.php
```

## Tecnologias e ferramentas

- PHP 8.3;
- Composer;
- PHPUnit 12;
- PHPStan;
- PHP CS Fixer;
- Git;
- GitHub.

### Tecnologias previstas para as próximas etapas

- PHP 8.4 em ambiente Docker;
- Docker Compose;
- PostgreSQL;
- Laravel;
- Blade;
- Bootstrap;
- GitHub Actions.

## Como executar o projeto

### Pré-requisitos atuais

- PHP 8.3 ou superior;
- Composer.

### Instalação

Clone o repositório:

```bash
git clone git@github.com:Rodrigo-Siqueira/treino-academia-php.git
```

Entre no diretório:

```bash
cd treino-academia-php
```

Instale as dependências:

```bash
composer install
```

## Comandos disponíveis

### Executar os testes

```bash
composer test
```

### Executar a análise estática

```bash
composer analyse
```

### Verificar a formatação

```bash
composer format:check
```

### Corrigir a formatação

```bash
composer format
```

### Executar todas as verificações

```bash
composer check
```

O comando completo executa:

1. testes automatizados;
2. análise estática;
3. verificação de formatação.

## Princípios adotados

O desenvolvimento procura seguir estes princípios:

- objetos devem nascer em estado válido;
- propriedades internas devem permanecer protegidas;
- alterações de estado devem ocorrer por comportamentos explícitos;
- validações devem ocorrer antes das alterações;
- operações inválidas não devem modificar parcialmente os objetos;
- dados derivados devem ser calculados sempre que possível;
- testes devem proteger comportamentos e valores-limite;
- abstrações devem surgir de necessidades reais;
- planejamento e execução devem permanecer separados;
- código que apenas funciona ainda pode precisar de melhorias.

## Decisões de modelagem

### Planejamento e execução

O projeto diferencia:

```text
FichaDeTreino
→ organiza o planejamento

ItemDaFicha
→ define séries e repetições planejadas

SessaoDeTreino
→ agrupa aquilo que foi executado

SerieRealizada
→ registra carga e repetições realizadas
```

Uma execução diferente do planejamento não é considerada automaticamente inválida. O sistema registra os valores objetivos sem produzir conclusões sobre qualidade, segurança ou eficácia do treino.

### Dados armazenados e calculados

Alguns valores são calculados em vez de armazenados:

- volume de uma série;
- volume total da sessão;
- duração da sessão;
- quantidade de itens da ficha;
- quantidade total de séries planejadas.

Essa decisão evita múltiplas fontes de verdade e reduz o risco de inconsistência.

## Roadmap

### Marco 1: núcleo em PHP puro

- [x] Modelagem de exercícios;
- [x] séries realizadas;
- [x] sessões de treino;
- [x] estados e transições;
- [x] fichas de treino;
- [x] planejamento de séries e repetições;
- [x] testes unitários;
- [x] análise estática;
- [x] formatação automática;
- [x] autoload PSR-4;
- [x] versionamento com Git.

### Marco 2: ambiente e fluxo profissional

- [ ] branches de funcionalidade;
- [ ] pull requests;
- [ ] revisão e resolução de conflitos;
- [ ] Docker;
- [ ] PHP 8.4 em container;
- [ ] execução dos testes no container;
- [ ] Composer em maior profundidade;
- [ ] preparação do PostgreSQL.

### Próximos marcos

- [ ] fundamentos do Laravel;
- [ ] persistência com PostgreSQL;
- [ ] migrations, factories e Eloquent;
- [ ] formulários e validação;
- [ ] autenticação e autorização;
- [ ] histórico de treinos;
- [ ] indicadores e relatórios;
- [ ] filas e tarefas agendadas;
- [ ] API;
- [ ] integração contínua;
- [ ] implantação.

## Objetivo de aprendizado

Este projeto não tem como propósito apenas entregar funcionalidades. Cada etapa busca desenvolver autonomia para:

- compreender o problema antes de programar;
- justificar decisões técnicas;
- identificar a responsabilidade de cada componente;
- escrever testes relevantes;
- consultar documentação oficial;
- revisar e melhorar código existente;
- diagnosticar falhas;
- evoluir a arquitetura conforme a complexidade real.

## Aviso

Este projeto possui finalidade didática e de organização pessoal.

A aplicação registra informações objetivas fornecidas pelo usuário, mas não prescreve exercícios, cargas, progressões ou condutas relacionadas à saúde. O uso do sistema não substitui o acompanhamento de profissionais qualificados.

## Autor

Projeto desenvolvido por Rodrigo Tebaldi Siqueira como parte de uma jornada prática de aprendizado em desenvolvimento de software.