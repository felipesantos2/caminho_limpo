# Caminho Limpo

O Caminho Limpo é uma ferramenta simples de gestão territorial para institutos, prefeituras e equipes de campo. O MVP organiza vistorias de descarte irregular, fotos, localização, triagem e pontos operacionais de coleta.

O painel de gestão é o produto principal. O mural público existe como uma saída opcional para os registros aprovados, sem depender de participação espontânea para que o sistema tenha valor.

## O que já funciona

- dashboard institucional com indicadores, gráficos e mapa;
- CRUD de relatos com foto, categoria, situação e protocolo ULID;
- foto como primeiro passo, com leitura de GPS do EXIF quando disponível;
- busca de endereço e marcação manual com Leaflet/OpenStreetMap;
- Plus Code calculado e salvo a partir das coordenadas;
- análise avulsa de imagens no painel;
- geocercas municipais definidas por centro e raio;
- API REST v1, com escritas e dados de gestão protegidos pelo Sanctum;
- PWA instalável, dark mode no painel e tela de indisponibilidade offline;
- dados demonstrativos de cinco municípios do nordeste de Minas Gerais.

## Ambiente local

O projeto usa PHP 8.5, Laravel 13, Livewire 4, Mary UI 2, Sanctum 4, Tailwind CSS 4, Pest 4 e Laravel Sail.

```bash
vendor/bin/sail up -d
vendor/bin/sail composer install
vendor/bin/sail npm install
vendor/bin/sail artisan migrate:fresh --seed
vendor/bin/sail artisan storage:link
vendor/bin/sail npm run build
```

A conta demonstrativa criada pelo seeder usa `demo@caminholimpo.org` e a senha `password`. Ela existe somente para desenvolvimento e apresentação.

## Validação

```bash
vendor/bin/sail bin pint --dirty --format agent
vendor/bin/sail artisan test --compact
vendor/bin/sail npm run build
vendor/bin/sail artisan route:list --path=api --except-vendor
```

## Documentação

- [Índice da documentação](docs/README.md)
- [MVP implementado](docs/mvp-relatos.md)
- [Decisões do projeto](docs/decisoes-do-projeto.md)
- [PWA](docs/pwa.md)
- [API REST](docs/api-rest.md)
- [Visão de evolução](docs/ecossistema-de-relatos.md)
- [Plano histórico e próximos passos](docs/TODO.md)
