# MVP de relatos

Esta primeira entrega permite registrar, organizar e consultar lugares que precisam de cuidado. Ela foi mantida pequena para chegar à apresentação de pré-incubação com um fluxo completo e testado.

## Como funciona

1. Uma pessoa da equipe abre o **Painel de atendimento** e registra um local.
2. O sistema valida os dados, guarda a foto e gera um protocolo público.
3. O relato pode permanecer recebido, entrar em triagem ou ser publicado.
4. Somente relatos publicados aparecem em **Explorar relatos** e na leitura pública da API.
5. A exclusão é lógica: o registro deixa de aparecer, mas continua no banco para eventual recuperação.

O CRUD web está aberto apenas para facilitar a demonstração. As escritas da API exigem autenticação Sanctum.

O nome exibido no menu lateral vem de `APP_ORGANIZATION_NAME`. Assim, a mesma instalação pode representar uma prefeitura, autarquia ou instituto sem alterar o código.

## Colunas da tabela `reports`

| Coluna | Finalidade |
| --- | --- |
| `id` | Identificador interno do banco. Não aparece como referência pública. |
| `protocol` | Código público único, como `CL-2026-AB12CD34`. Também identifica o relato nas URLs. |
| `category` | Categoria fechada do problema. É um `ENUM` no banco e um enum PHP no código. |
| `description` | Texto original sobre o que foi observado, limitado a 500 caracteres pela validação. |
| `address` | Endereço aproximado ou ponto de referência. |
| `latitude` | Coordenada opcional entre -90 e 90. |
| `longitude` | Coordenada opcional entre -180 e 180. |
| `status` | Situação fechada do relato. É um `ENUM` e define se o registro pode aparecer publicamente. |
| `image_path` | Caminho da foto no disco público. |
| `created_at` e `updated_at` | Datas de criação e da última alteração. |
| `deleted_at` | Data da exclusão lógica. Quando preenchida, o relato deixa de aparecer nas consultas normais. |

As coordenadas são opcionais, mas devem ser enviadas juntas. Nesta fase não há mapa nem busca automática de endereço.

## Valores fechados

Categorias:

- lixo doméstico;
- entulho de obra;
- móvel ou objeto volumoso;
- resíduo reciclável;
- resíduo perigoso ou químico;
- esgoto ou água contaminada;
- terreno ou área abandonada;
- outro.

Status:

- recebido;
- em triagem;
- publicado;
- restrito;
- rejeitado.

## Telas e API

- `/relatos`: consulta pública dos relatos publicados;
- `/relatos/{protocolo}`: detalhe público;
- `/gestao`: dashboard institucional com indicadores, gráficos e relatos recentes;
- `/gestao/relatos`: listagem, filtros e exclusão;
- `/gestao/relatos/criar`: cadastro;
- `/gestao/relatos/{protocolo}/editar`: edição;
- `/api/v1/reports`: contrato JSON paginado. `GET` é público; `POST`, `PATCH`, `PUT` e `DELETE` exigem Sanctum;
- `/api/v1/dashboard`: indicadores da dashboard em JSON, protegidos pelo Sanctum.

A API aceita `category` e `per_page` como filtros na listagem. A mesma validação usada nas telas é reutilizada nos endpoints de escrita.

## Validação da entrega

Com o Docker ativo:

```bash
vendor/bin/sail up -d
vendor/bin/sail artisan migrate:fresh --seed
vendor/bin/sail artisan storage:link
vendor/bin/sail artisan test --compact
vendor/bin/sail bin pint --dirty --format agent
vendor/bin/sail npm run build
```

Os testes usam o banco MySQL `caminhoLimpo_testing`, criado pelo Sail, e nunca devem reutilizar o banco local de desenvolvimento.

## Decisões adiadas

Para manter o MVP simples, ficaram para depois: autenticação das telas, emissão de tokens, vários arquivos por relato, tabela separada de locais, mapa, geocodificação e histórico de moderação.
