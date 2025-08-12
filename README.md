# Desafio da aula 07

Organize o projeto para que fique independente do Framework(Laravel) em cima de um modelo de arquitetura por camadas.

Análise o projeto, é uma pequena API de aluguel de livros, onde os usuários cadastrados podem alugar livros e pagar
por dias que os reservaram, a cobrança é por dia fixo de R$ 4,50, essa lógica já está pronta, mas está em `Controller`.

Regras de negócio:
Uma reserva fica aberta enquanto não tiver uma data na coluna da tabela reservations(returned_at).

Uma devolução deve indicar a data de retorno(return_date) na requisição que não deve aceitar uma data menor que a 
data de reserva(reserved_at).

Uma reserva não pode ser alterada após a confirmação de devolução(/reservations/return).

O calculo do custo só deve acontecer após uma confirmação de devolução.

### Seu objetivo

1. Separe o projeto por camadas Controller > UseCase > Repository > Domains.
2. Para o caso atual o `Repository` pode ser específico, não necessitanto criar um genérico.
3. Faça somente a camada Repository acessar o Eloquent ORM (coloque-a em Infrastructure).
4. Elimine repetições (DRY) nos controllers retirando lógicas de aplicação e domínio dos `Controllers`.
5. Identifique regras de negócio e coloque-as na camada de Domínio criando um Modelo.
6. Use o contêiner `ServiceProvider` do Laravel para programar para interfaces, pelo menos a partir da camada do UseCase.
7. Testes unitários não fazem parte deste exercício, não é preciso faze-los.

Extras: 
1. melhorias como validações com ValueObjects dos campos serão consideradas.
2. Melhorar a saída de error e padronizar a saída com uma camada Presenter.

### Dicas
Subir os containers do projeto:
```bash
$ docker compose up -d
```
Instalar as dependências:
```bash
$ make container
$ composer install
```

Migrations e Seeder(fora do controller):
```bash
$ ./php artisan migrate --seed
```

### Testar no Postman ou Insomnia
Criar uma reserva
```
POST http://localhost:8888/api/reservations
{
    "user_id": 1,
    "stored_book_id": 23
}
```

Salvar a data de devolução
```
POST http://localhost:8888/api/reservations/return
{
    "reservation_id": 6,
    "return_date": "2024-12-18 11:22:11"
}
```

Resgatar o custo do aluguel
```
GET http://localhost:8888/api/reservations/cost
{
    "reservation_id": 3
}
```
