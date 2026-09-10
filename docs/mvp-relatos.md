# MVP de relatos

Esta entrega permite que uma equipe registre, organize e consulte lugares que precisam de cuidado. Ela foi mantida pequena para chegar à apresentação de pré-incubação com um fluxo completo e testado, mas útil mesmo sem participação do público.

## Como funciona

1. Uma pessoa da equipe abre o **Painel de atendimento** e registra uma vistoria.
2. A foto é informada primeiro. O sistema tenta ler o GPS dos metadados.
3. Se a foto não tiver GPS, a equipe pesquisa um endereço ou marca o ponto no mapa.
4. O sistema valida os dados, guarda a foto, calcula o Plus Code e gera um protocolo público.
5. O relato pode permanecer recebido, entrar em triagem ou ser publicado.
6. Somente relatos publicados aparecem no mural e na leitura pública da API.
7. A exclusão é lógica: o registro deixa de aparecer, mas continua no banco para eventual recuperação.

O CRUD web está aberto apenas para facilitar a demonstração. As escritas da API exigem autenticação Sanctum.

O nome exibido no menu lateral vem de `APP_ORGANIZATION_NAME`. Assim, a mesma instalação pode representar uma prefeitura, autarquia ou instituto sem alterar o código.

## Colunas da tabela `reports`

| Coluna | Finalidade |
| --- | --- |
| `id` | Identificador interno do banco. Não aparece como referência pública. |
| `protocol` | Código público único no formato `CL-` seguido de um ULID. Também identifica o relato nas URLs. |
| `category` | Categoria fechada do problema. É um `ENUM` no banco e um enum PHP no código. |
| `description` | Texto original sobre o que foi observado, limitado a 500 caracteres pela validação. |
| `address` | Endereço aproximado ou ponto de referência. |
| `latitude` | Coordenada opcional entre -90 e 90. |
| `longitude` | Coordenada opcional entre -180 e 180. |
| `plus_code` | Código global opcional, calculado automaticamente quando há coordenadas. |
| `status` | Situação fechada do relato. É um `ENUM` e define se o registro pode aparecer publicamente. |
| `image_path` | Caminho da foto no disco público. |
| `created_at` e `updated_at` | Datas de criação e da última alteração. |
| `deleted_at` | Data da exclusão lógica. Quando preenchida, o relato deixa de aparecer nas consultas normais. |

As coordenadas são opcionais, mas devem ser enviadas juntas. O formulário tenta obtê-las da foto, permite pesquisar um endereço e também aceita clique ou arraste do pino. A dashboard reúne os relatos geolocalizados.

O mapa usa Leaflet e os blocos cartográficos do OpenStreetMap. A busca usa Nominatim com envio explícito e cache de um dia. O centro inicial pode ser ajustado por `APP_MAP_DEFAULT_LATITUDE`, `APP_MAP_DEFAULT_LONGITUDE` e `APP_MAP_DEFAULT_ZOOM`.

## Gaiolas de coleta

As gaiolas são pontos operacionais e não relatos. A tabela `collection_points` mantém:

| Coluna | Finalidade |
| --- | --- |
| `name` | Nome curto usado pela equipe. |
| `address` | Endereço ou referência operacional. |
| `latitude` e `longitude` | Ponto obrigatório marcado no mapa. |
| `plus_code` | Código calculado para compartilhar o ponto. |
| `status` | Situação fechada: ativa, em manutenção ou inativa. |
| `notes` | Observação interna opcional. |
| `created_at` e `updated_at` | Datas de criação e alteração. |

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
- `/gestao/relatos/{protocolo}`: detalhe administrativo em qualquer situação;
- `/gestao/relatos/{protocolo}/editar`: edição;
- `/gestao/analise-de-imagens`: leitura avulsa de GPS e Plus Code de uma foto;
- `/gestao/gaiolas`: cadastro, mapa e lista de gaiolas;
- `/api/v1/reports`: contrato JSON paginado. `GET` é público; `POST`, `PATCH`, `PUT` e `DELETE` exigem Sanctum;
- `/api/v1/dashboard`: indicadores da dashboard em JSON, protegidos pelo Sanctum;
- `/api/v1/collection-points`: CRUD JSON das gaiolas, protegido pelo Sanctum.

A API aceita `category` e `per_page` como filtros na listagem. A mesma validação usada nas telas é reutilizada nos endpoints de escrita.

## Dados de demonstração e PWA

O seeder padrão cria 15 focos fictícios de entulho — três em cada um dos municípios Novo Cruzeiro, Águas Formosas, Teófilo Otoni, Itaipé e Catuji — e cinco gaiolas demonstrativas. Os seeders usam chaves estáveis e podem ser executados novamente sem duplicar esses registros.

O manifesto permite instalar o Caminho Limpo e oferece atalhos para o painel e uma nova vistoria. O service worker entrega uma tela offline, mas mapas, busca de endereço e gravações continuam dependentes de internet.

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

Para manter o MVP simples, ficaram para depois: autenticação das telas, emissão de tokens pela interface, vários arquivos por relato, tabela consolidada de locais, geocodificação reversa, histórico de moderação e funcionamento offline de escrita.
