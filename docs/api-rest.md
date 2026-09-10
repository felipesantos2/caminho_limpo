# API REST

A API fica em `/api/v1` e responde JSON. A versão faz parte da URL para que uma mudança futura de contrato não quebre integrações existentes sem aviso.

Envie sempre:

```http
Accept: application/json
```

Nas rotas protegidas, envie também:

```http
Authorization: Bearer SEU_TOKEN
```

## Autenticação

O projeto usa Laravel Sanctum. Ainda não existe uma tela para emitir tokens; em desenvolvimento, um token pode ser criado para a conta demonstrativa com:

```bash
vendor/bin/sail artisan tinker --execute '$user = App\Models\User::where("email", "demo@caminholimpo.org")->sole(); echo $user->createToken("rest-local")->plainTextToken;'
```

O valor completo é mostrado apenas nesse momento. Guarde-o fora do repositório. Para revogar o token, exclua o registro correspondente em `personal_access_tokens` por um fluxo administrativo seguro.

## Rotas

### Relatos

| Método | Rota | Autenticação | Uso |
| --- | --- | --- | --- |
| `GET` | `/api/v1/reports` | pública | Lista somente relatos publicados. |
| `GET` | `/api/v1/reports/{protocol}` | pública | Mostra um relato publicado. |
| `POST` | `/api/v1/reports` | Sanctum | Cria um relato e salva a foto. |
| `PUT/PATCH` | `/api/v1/reports/{protocol}` | Sanctum | Atualiza os dados e, opcionalmente, substitui a foto. |
| `DELETE` | `/api/v1/reports/{protocol}` | Sanctum | Faz exclusão lógica. |

A listagem aceita `category` e `per_page`. `per_page` pode variar de 1 a 50.

Exemplo público:

```bash
curl -H 'Accept: application/json' \
  'http://localhost/api/v1/reports?category=construction_debris&per_page=10'
```

Criação usa `multipart/form-data` porque contém uma foto:

```bash
curl -X POST http://localhost/api/v1/reports \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer SEU_TOKEN' \
  -F 'category=construction_debris' \
  -F 'description=Entulho acumulado ao lado da via principal.' \
  -F 'address=Acesso norte, Novo Cruzeiro/MG' \
  -F 'latitude=-17.4629000' \
  -F 'longitude=-41.8814000' \
  -F 'status=received' \
  -F 'image=@/caminho/para/foto.jpg'
```

O retorno contém protocolo, categoria, endereço, coordenadas, Plus Code, situação, URL da foto e datas. Um relato não publicado retorna `404` na rota pública de detalhe.

### Dashboard

| Método | Rota | Autenticação | Uso |
| --- | --- | --- | --- |
| `GET` | `/api/v1/dashboard` | Sanctum | Indicadores, séries dos gráficos, pontos do mapa e relatos recentes. |

Exemplo:

```bash
curl -H 'Accept: application/json' \
  -H 'Authorization: Bearer SEU_TOKEN' \
  http://localhost/api/v1/dashboard
```

### Geocercas municipais

| Método | Rota | Autenticação | Uso |
| --- | --- | --- | --- |
| `GET` | `/api/v1/municipality-geofences` | Sanctum | Lista as áreas municipais. |
| `POST` | `/api/v1/municipality-geofences` | Sanctum | Define uma nova área. |
| `GET` | `/api/v1/municipality-geofences/{id}` | Sanctum | Consulta uma área. |
| `PUT/PATCH` | `/api/v1/municipality-geofences/{id}` | Sanctum | Altera centro ou raio. |
| `DELETE` | `/api/v1/municipality-geofences/{id}` | Sanctum | Remove a área. |

Payload de criação ou atualização:

```json
{
    "municipality": "teofilo_otoni",
    "center_latitude": "-17.8607000",
    "center_longitude": "-41.5019000",
    "radius_km": "22"
}
```

Cada município pode ter somente uma geocerca. O raio aceito fica entre `0.5` e `100` quilômetros.

## Códigos de resposta

| Código | Significado no projeto |
| --- | --- |
| `200` | Consulta ou atualização concluída. |
| `201` | Registro criado. |
| `204` | Registro excluído sem corpo de resposta. |
| `401` | Token ausente ou inválido. |
| `404` | Registro inexistente ou relato ainda não publicado em rota pública. |
| `422` | Dados inválidos; o corpo contém `message` e `errors`. |

## Formato dos dados

Os Resources mantêm rótulo e valor de enums juntos. Exemplo:

```json
{
    "status": {
        "value": "triage",
        "label": "Em triagem"
    }
}
```

Use `value` em integrações e `label` para exibição. Coordenadas e valores decimais são serializados como strings para preservar a precisão do banco.

## Validação

```bash
vendor/bin/sail artisan route:list --path=api --except-vendor
vendor/bin/sail artisan test --compact tests/Feature/Api
```

Os testes conferem leitura pública, autenticação Sanctum, validação, criação, atualização e exclusão. Um teste HTTP local adicional pode confirmar o comportamento do servidor em execução.
