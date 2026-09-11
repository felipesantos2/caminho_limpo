# Decisões do projeto

Anotações curtas para não rediscutir escolhas que já fizemos.

## Produto

- O Caminho Limpo deve ter utilidade para uma equipe de gestão mesmo sem receber relatos do público.
- Instituto, prefeitura, autarquia ou equipe de campo são os usuários principais do painel.
- A dashboard é a página inicial do painel.
- O mural público é uma publicação opcional de dados aprovados, não o centro do produto.
- O prazo curto de apresentação pede um fluxo completo e compreensível, não muitos módulos incompletos.

## Experiência

- Mary UI é o padrão de todas as telas Livewire, incluindo a página inicial pública, que usa um layout próprio (sem o menu lateral de gestão) mas o mesmo tema, cores e componentes do painel.
- O painel deve manter o mesmo layout entre as páginas e funcionar em dark mode.
- A foto aparece no início do cadastro porque é a primeira evidência usada para validar o local.
- Ninguém deve precisar descobrir latitude e longitude manualmente: a ordem de tentativa é GPS da foto, busca de endereço e clique no mapa.
- A interface deve ser institucional e contida, sem excesso de cartões, badges ou elementos decorativos.
- A página inicial pública usa o mesmo design system Mary UI, explica o fluxo em poucas seções e não inventa métricas de impacto.

## Dados e localização

- Categorias e situações com lista fechada usam enums PHP e `ENUM` no banco.
- Uma “gaiola” neste projeto significa geocerca: um círculo com centro e raio que delimita a área de um município.
- Coordenadas são armazenadas em sete casas decimais.
- O Plus Code é calculado localmente, sem chave ou cobrança do Google.
- A busca usa Nominatim com ação explícita, até cinco resultados e cache de um dia. Não deve virar autocomplete agressivo.
- Pontos criados por seeders são fictícios e servem para demonstração; não afirmam a existência de descarte real nos endereços.

## Fotos

- O cadastro aceita JPG, PNG e WebP de até 5 MB.
- A leitura de localização usa EXIF e funciona principalmente com JPG/JPEG.
- Fotos enviadas por mensageiros podem perder os metadados; a tela deve explicar isso e oferecer busca/mapa como alternativa.
- A análise avulsa não cadastra nem mantém a foto depois da requisição.

## API e segurança

- A API é versionada em `/api/v1` e usa Resources para manter o JSON previsível.
- A leitura pública de relatos mostra somente itens publicados.
- Dashboard, geocercas municipais e qualquer escrita exigem autenticação Sanctum.
- As telas administrativas ainda estão abertas para facilitar a apresentação. Autenticação e autorização web são prioridade antes de uso externo.

## PWA e conectividade

- O aplicativo é instalável e oferece atalhos para painel e nova vistoria.
- O service worker fornece uma página de orientação quando a navegação falha.
- Busca de endereço, mapas e gravações não são anunciados como offline, pois dependem de rede.

## Código e validação

- Preferir classes pequenas, nomes claros e APIs do Laravel compatíveis com as versões instaladas.
- Regras de validação compartilhadas devem ser reutilizadas por Livewire e REST.
- Comentários explicam decisões e limites; não repetem o que o código já diz.
- Toda mudança funcional recebe teste essencial, Pint, build e um commit coerente.
- Todos os comandos PHP, Artisan, Composer e Node são executados pelo Laravel Sail.

Os próximos trabalhos estão em [TODO.md](TODO.md). Este arquivo registra escolhas; não precisa virar outra lista de tarefas.
