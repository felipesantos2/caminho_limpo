# PWA

O Caminho Limpo pode ser instalado no celular ou no computador e aberto como aplicativo. A PWA não cria uma segunda aplicação: ela usa as mesmas telas Laravel e Livewire servidas pelo navegador.

## Arquivos envolvidos

| Arquivo | Papel |
| --- | --- |
| `public/manifest.webmanifest` | Nome, cores, ícones, página inicial e atalhos do aplicativo. |
| `public/sw.js` | Service worker responsável pelo fallback offline e pelo cache dos arquivos gerados pelo Vite. |
| `public/offline.html` | Página mostrada quando uma navegação falha por falta de conexão. |
| `public/icons/pwa-192.svg` | Ícone usado pelo manifesto e pelo navegador. |
| `public/icons/pwa-512.svg` | Ícone maior e preparado para uso `maskable`. |
| `resources/js/app.js` | Registra o service worker depois que a página termina de carregar. |

O manifesto e as cores do tema são ligados tanto ao layout do painel quanto à página inicial pública.

## Instalação

Em produção, o navegador precisa acessar o sistema por HTTPS. `localhost` é aceito durante o desenvolvimento. Quando os critérios do navegador forem atendidos, a opção de instalar aparece no menu ou na barra de endereço.

O aplicativo abre em `/gestao?origem=pwa`. O manifesto também oferece atalhos para:

- abrir o painel;
- iniciar uma nova vistoria.

Não existe um botão de instalação próprio nesta fase. Essa escolha evita código diferente para cada navegador antes de validarmos como as equipes realmente instalam o sistema.

## Registro do service worker

O registro acontece somente no build de produção:

```js
if ('serviceWorker' in navigator && import.meta.env.PROD) {
    window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
}
```

Durante `npm run dev`, o service worker não é registrado. Isso evita que uma versão em cache esconda alterações enquanto a interface está sendo desenvolvida.

## O que fica em cache

Na instalação, o service worker guarda somente os arquivos necessários para explicar a falta de conexão:

- `offline.html`;
- `manifest.webmanifest`;
- os dois ícones da PWA.

Arquivos versionados dentro de `/build/` entram no cache depois do primeiro acesso. Como o Vite coloca um hash no nome dos arquivos, um novo build produz URLs novas e não mistura JavaScript ou CSS de versões diferentes.

As páginas Laravel não são guardadas. Para uma navegação, a PWA tenta a rede e usa `offline.html` somente quando a requisição falha. Respostas Livewire e REST também não são armazenadas pelo service worker.

## O que não funciona offline

Sem internet, o aplicativo não promete:

- abrir uma página que ainda não está carregada;
- salvar ou editar um relato;
- pesquisar endereços no Nominatim;
- carregar blocos do mapa do OpenStreetMap;
- consultar ou alterar dados pela API REST.

Esse limite é intencional. Ainda não existe uma fila local confiável para fotos e alterações. A tela offline informa isso em vez de fazer a pessoa acreditar que um cadastro foi salvo.

## Atualizações

Quando `sw.js` muda, o navegador baixa e ativa a nova versão. A instalação usa `skipWaiting()` e a ativação usa `clients.claim()`, portanto a atualização assume o controle sem esperar todas as abas antigas fecharem.

O cache atual se chama `caminho-limpo-shell-v1`. Ao remover ou renomear arquivos fixos da lista offline, altere esse nome para `v2`, `v3` e assim por diante. Na ativação, caches com outros nomes são excluídos.

Depois de qualquer alteração visual ou de JavaScript, execute:

```bash
vendor/bin/sail npm run build
```

O diretório `public/build` é versionado neste projeto. O manifesto do Vite e os novos arquivos com hash devem entrar no mesmo conjunto de commits da mudança correspondente.

## Como validar

Validação automatizada:

```bash
vendor/bin/sail artisan test --compact tests/Feature/PwaTest.php
vendor/bin/sail npm run build
```

Validação manual em produção ou homologação:

1. abra as ferramentas do navegador e confirme que o manifesto foi carregado;
2. confirme que `/sw.js` está ativo para o domínio correto;
3. instale o aplicativo e confira se ele abre no painel;
4. visite o painel uma vez, desligue a rede e tente outra navegação;
5. confirme que a página offline aparece e não promete que dados foram salvos;
6. religue a rede e confira mapas, busca e formulários.

Se uma versão antiga continuar aparecendo, remova o service worker e os dados do site nas ferramentas do navegador, recarregue a página e instale novamente. Em ambiente real, isso deve ser usado apenas para diagnóstico; usuários comuns recebem a atualização automaticamente.
