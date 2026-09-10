<!DOCTYPE html>
<html class="scroll-smooth motion-reduce:scroll-auto" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @head

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white font-sans text-stone-800 selection:bg-green-200 selection:text-green-950">
    <!-- Navbar -->
    <nav
        aria-label="Navegação principal"
        class="fixed inset-x-0 top-0 z-50 border-b border-stone-200/80 bg-white/90 shadow-xs backdrop-blur-md"
    >
        <div class="mx-auto flex h-20 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <a
                href="#inicio"
                class="group flex items-center gap-3 rounded-lg focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-green-600"
                aria-label="Caminho Limpo, início"
            >
                <span
                    class="flex size-10 items-center justify-center rounded-lg bg-green-700 text-white transition group-hover:bg-green-800"
                    aria-hidden="true"
                >
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 21V10m0 0C10.5 6.5 7.5 5 4 5c0 4.5 2.5 7.5 8 7m0-2c1.5-3.5 4.5-5 8-5 0 4.5-2.5 7.5-8 7"
                        />
                    </svg>
                </span>
                <span>
                    <span class="block text-base leading-none font-bold text-stone-900 sm:text-lg">Caminho Limpo</span>
                    <span class="mt-1 hidden text-xs font-medium tracking-wide text-stone-500 sm:block">Ação ambiental</span>
                </span>
            </a>

            <div class="hidden items-center gap-1 md:flex">
                <a
                    href="#problema"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-stone-600 transition hover:bg-green-50 hover:text-green-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600"
                >O Problema</a>
                <a
                    href="#solucao"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-stone-600 transition hover:bg-green-50 hover:text-green-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600"
                >A Solução</a>
                <a
                    href="#impacto"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-stone-600 transition hover:bg-green-50 hover:text-green-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600"
                >Impacto</a>
            </div>

            <a
                href="{{ route('management.reports.create') }}"
                class="hidden items-center gap-2 rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-green-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-700 md:inline-flex"
            >
                Relatar um local
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                </svg>
            </a>

            <button
                id="navigation-toggle"
                type="button"
                class="inline-flex size-11 items-center justify-center rounded-lg border border-stone-200 text-stone-700 transition hover:border-green-200 hover:bg-green-50 hover:text-green-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 md:hidden"
                aria-controls="mobile-navigation"
                aria-expanded="false"
                aria-label="Abrir menu"
            >
                <svg
                    data-menu-icon
                    class="size-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg
                    data-close-icon
                    class="hidden size-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        <div id="mobile-navigation" class="hidden border-t border-stone-200 bg-white px-4 py-4 shadow-lg md:hidden">
            <div class="mx-auto grid max-w-6xl gap-1">
                <a
                    href="#problema"
                    class="rounded-lg px-3 py-3 font-medium text-stone-700 transition hover:bg-green-50 hover:text-green-800 focus-visible:outline-2 focus-visible:outline-green-600"
                >O Problema</a>
                <a
                    href="#solucao"
                    class="rounded-lg px-3 py-3 font-medium text-stone-700 transition hover:bg-green-50 hover:text-green-800 focus-visible:outline-2 focus-visible:outline-green-600"
                >A Solução</a>
                <a
                    href="#impacto"
                    class="rounded-lg px-3 py-3 font-medium text-stone-700 transition hover:bg-green-50 hover:text-green-800 focus-visible:outline-2 focus-visible:outline-green-600"
                >Impacto</a>
                <a
                    href="{{ route('management.reports.create') }}"
                    class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-green-700 px-5 py-3 font-semibold text-white transition hover:bg-green-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-700"
                >
                    Relatar um local
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 12h14m-6-6 6 6-6 6"
                        />
                    </svg>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section id="inicio" class="relative scroll-mt-24 pt-24 pb-0">
        <div class="relative h-[70vh] overflow-hidden">
            <img
                src="{{ asset('attached_assets/br/hero_dirty.jpg') }}"
                alt="Rua brasileira com lixo e entulho"
                class="h-full w-full object-cover"
            />
            <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/40 to-transparent"></div>
            <div class="absolute right-0 bottom-0 left-0 p-8 text-white md:p-16">
                <div class="max-w-4xl">
                    <p class="mb-3 font-medium text-green-400">Esse é o caminho que temos hoje.</p>
                    <h1 class="mb-6 text-4xl leading-tight font-bold md:text-5xl lg:text-6xl">
                        Vias sujas, entulhos abandonados, lixo por toda parte.
                    </h1>
                    <p class="mb-8 max-w-2xl text-xl text-stone-200">
                        Milhares de toneladas de resíduos são descartados irregularmente todos os dias nas ruas,
                        estradas e terrenos do Brasil.
                    </p>
                    <a
                        href="#solucao"
                        class="inline-flex items-center gap-2 rounded-full bg-green-600 px-8 py-4 font-medium text-white transition hover:bg-green-700 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-green-400"
                    >
                        Veja como mudar isso
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- O Problema -->
    <section id="problema" class="scroll-mt-24 bg-stone-900 px-6 py-20 text-white">
        <div class="mx-auto max-w-6xl">
            <div class="mb-16 text-center">
                <p class="mb-3 font-medium text-green-400">O Problema</p>
                <h2 class="text-3xl font-bold lg:text-4xl">Caminhos bloqueados pelo descaso</h2>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                <!-- Foto BH -->
                <figure>
                    <div class="relative aspect-4/3 overflow-hidden">
                        <img
                            src="{{ asset('attached_assets/br/real_bh.jpg') }}"
                            alt="Descarte irregular em Belo Horizonte MG"
                            class="h-full w-full object-cover"
                        />
                        <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-sm font-semibold tracking-wide text-white uppercase">Belo Horizonte, MG</span>
                    </div>
                    <figcaption class="mt-3 text-sm leading-snug text-stone-400">
                        BH registrou mais de 2.000 denúncias de descarte incorreto em 2026. Moradores e comerciantes
                        sofrem com acúmulo de lixo em vias públicas.<br />
                        <a
                            href="https://bhaz.com.br/noticias/bh/descarte-incorreto-lixo-bh-2026/"
                            target="_blank"
                            class="mt-1 inline-block text-stone-500 underline underline-offset-2 hover:text-green-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-400"
                        >BHaz — Descarte incorreto de lixo em BH registrou mais de 2 mil denúncias ↗</a>
                    </figcaption>
                </figure>

                <!-- Foto MG -->
                <figure>
                    <div class="relative aspect-4/3 overflow-hidden">
                        <img
                            src="{{ asset('attached_assets/br/real_mg.png') }}"
                            alt="Estrada vira lixão em Águas Formosas, próximo a Novo Cruzeiro MG"
                            class="h-full w-full object-cover"
                        />
                        <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-sm font-semibold tracking-wide text-white uppercase">Águas Formosas, MG</span>
                    </div>
                    <figcaption class="mt-3 text-sm leading-snug text-stone-400">
                        Trecho da estrada entre Novo Cruzeiro e Teófilo Otoni (Vale do Mucuri). Moradores denunciam
                        acúmulo de lixo às margens da via.<br />
                        <a
                            href="https://jornaldiarioteo.com.br/estrada-vira-lixao-em-aguas-formosas/"
                            target="_blank"
                            class="mt-1 inline-block text-stone-500 underline underline-offset-2 hover:text-green-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-400"
                        >Diário de Teófilo Otoni — Estrada vira lixão em Águas Formosas ↗</a>
                    </figcaption>
                </figure>

                <!-- Foto VdC -->
                <figure>
                    <div class="relative aspect-4/3 overflow-hidden">
                        <img
                            src="{{ asset('attached_assets/br/real_vdc_1.jpg') }}"
                            alt="Descarte irregular em Vitória da Conquista, Bahia"
                            class="h-full w-full object-cover"
                        />
                        <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-sm font-semibold tracking-wide text-white uppercase">Vitória da Conquista, BA</span>
                    </div>
                    <figcaption class="mt-3 text-sm leading-snug text-stone-400">
                        Sofás, colchões e resíduos domésticos descartados em calçadas e áreas verdes da cidade.<br />
                        <a
                            href="https://www.blogdoanderson.com/2026/04/18/lixour/"
                            target="_blank"
                            class="mt-1 inline-block text-stone-500 underline underline-offset-2 hover:text-green-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-400"
                        >Blog do Anderson — Descarte irregular cria "lixões urbanos" em Vitória da Conquista ↗</a>
                    </figcaption>
                </figure>
            </div>

            <div class="mt-12 grid gap-8 text-center md:grid-cols-3">
                <div>
                    <p class="text-4xl font-bold text-red-400">79 milhões</p>
                    <p class="mt-2 text-stone-400">de toneladas de lixo geradas por ano no Brasil</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-red-400">40%</p>
                    <p class="mt-2 text-stone-400">têm destinação inadequada</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-red-400">3 mil</p>
                    <p class="mt-2 text-stone-400">lixões ainda ativos no país</p>
                </div>
            </div>
        </div>
    </section>

    <!-- A Solução -->
    <section id="solucao" class="relative scroll-mt-24 py-0">
        <div class="relative h-[70vh] overflow-hidden">
            <img
                src="{{ asset('attached_assets/br/solucao_estrada.jpg') }}"
                alt="Estrada limpa no Brasil"
                class="h-full w-full object-cover"
            />
            <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/30 to-transparent"></div>
            <div class="absolute right-0 bottom-0 left-0 p-8 text-white md:p-16">
                <div class="max-w-4xl">
                    <p class="mb-3 font-medium text-green-400">Esse é o caminho que queremos.</p>
                    <h2 class="mb-6 text-4xl leading-tight font-bold md:text-5xl lg:text-6xl">
                        Vias limpas, natureza preservada, futuro garantido.
                    </h2>
                    <p class="max-w-2xl text-xl text-stone-200">
                        O <strong>Caminho Limpo</strong> trabalha para transformar essa realidade através de coleta
                        responsável, educação ambiental e ação comunitária.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Como Funciona -->
    <section class="bg-green-50 px-6 py-20">
        <div class="mx-auto max-w-6xl">
            <div class="mb-16 text-center">
                <p class="mb-3 font-medium text-green-600">Nossa Atuação</p>
                <h2 class="text-3xl font-bold text-stone-900 lg:text-4xl">Como limpamos o caminho</h2>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl bg-white p-6 shadow-xs">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-2 font-semibold">Mapeamento</h3>
                    <p class="text-sm text-stone-600">Identificamos pontos críticos de descarte irregular na cidade.</p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-xs">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-2 font-semibold">Coleta</h3>
                    <p class="text-sm text-stone-600">Organizamos mutirões e coletas programadas de resíduos.</p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-xs">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-2 font-semibold">Reciclagem</h3>
                    <p class="text-sm text-stone-600">Encaminhamos materiais para cooperativas de reciclagem.</p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-xs">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-2 font-semibold">Educação</h3>
                    <p class="text-sm text-stone-600">Conscientizamos a população sobre descarte correto.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Ação -->
    <section class="px-6 py-20">
        <div class="mx-auto grid max-w-6xl items-center gap-12 lg:grid-cols-2">
            <div>
                <img
                    src="{{ asset('attached_assets/br/voluntarios.jpg') }}"
                    alt="Voluntários limpando ruas no Brasil"
                    class="aspect-4/3 w-full rounded-xl object-cover shadow-lg"
                />
            </div>
            <div>
                <p class="mb-3 font-medium text-green-600">Ação Comunitária</p>
                <h2 class="mb-6 text-3xl font-bold text-stone-900 lg:text-4xl">Juntos, limpamos o caminho.</h2>
                <p class="mb-6 leading-relaxed text-stone-600">
                    Nossos voluntários já realizaram mais de <strong>50 mutirões</strong> de limpeza em bairros,
                    estradas e áreas verdes. Cada ação remove toneladas de lixo e transforma a paisagem urbana.
                </p>
                <ul class="mb-8 space-y-3">
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </span>
                        <span class="text-stone-700">+200 voluntários ativos</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </span>
                        <span class="text-stone-700">+30 toneladas de lixo recolhidas</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </span>
                        <span class="text-stone-700">+15 bairros beneficiados</span>
                    </li>
                </ul>
                <a
                    href="#contato"
                    class="inline-flex items-center gap-2 rounded-full bg-green-600 px-6 py-3 font-medium text-white transition hover:bg-green-700 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-green-700"
                >
                    Quero ser voluntário
                </a>
            </div>
        </div>
    </section>

    <!-- Impacto -->
    <section id="impacto" class="scroll-mt-24 bg-stone-50 px-6 py-20">
        <div class="mx-auto max-w-6xl">
            <div class="mb-16 text-center">
                <p class="mb-3 font-medium text-green-600">Nosso Impacto</p>
                <h2 class="text-3xl font-bold text-stone-900 lg:text-4xl">Cada caminho limpo faz a diferença</h2>
            </div>

            <div class="grid items-center gap-8 md:grid-cols-2">
                <div>
                    <img
                        src="{{ asset('attached_assets/br/impacto_limpo.jpg') }}"
                        alt="Rua limpa no Brasil"
                        class="aspect-4/3 w-full rounded-xl object-cover shadow-lg"
                    />
                </div>
                <div class="space-y-6">
                    <div class="rounded-xl bg-white p-6 shadow-xs">
                        <div class="flex items-center gap-4">
                            <div class="text-4xl font-bold text-green-600">-70%</div>
                            <div>
                                <p class="font-medium">Menos lixo nas ruas</p>
                                <p class="text-sm text-stone-500">nos bairros onde atuamos</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl bg-white p-6 shadow-xs">
                        <div class="flex items-center gap-4">
                            <div class="text-4xl font-bold text-green-600">85%</div>
                            <div>
                                <p class="font-medium">Material reciclado</p>
                                <p class="text-sm text-stone-500">do total coletado em mutirões</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl bg-white p-6 shadow-xs">
                        <div class="flex items-center gap-4">
                            <div class="text-4xl font-bold text-green-600">+500</div>
                            <div>
                                <p class="font-medium">Famílias impactadas</p>
                                <p class="text-sm text-stone-500">com ruas mais limpas e seguras</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="contato" class="scroll-mt-24 bg-green-700 px-6 py-20 text-white">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="mb-6 text-3xl font-bold lg:text-4xl">O caminho limpo começa com você.</h2>
            <p class="mb-10 text-lg text-green-100">
                Seja voluntário, denuncie pontos de descarte irregular ou apoie nossa causa. Cada ação conta.
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <a
                    href="mailto:contato@caminholimpo.org"
                    class="rounded-full bg-white px-8 py-4 font-semibold text-green-700 transition hover:bg-green-50 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white"
                >
                    Fale Conosco
                </a>
                <a
                    href="https://wa.me/5511999999999"
                    target="_blank"
                    class="rounded-full border-2 border-white px-8 py-4 font-semibold transition hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white"
                >
                    WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-stone-900 px-6 py-16">
        <div class="mx-auto max-w-4xl text-center">
            <h3 class="mb-3 text-2xl font-bold text-white">Caminho Limpo</h3>
            <p class="mb-8 text-stone-400">Limpando vias, transformando vidas, construindo futuros.</p>

            <div class="mb-10 flex flex-col items-center justify-center gap-6 sm:flex-row">
                <a
                    href="mailto:contato@caminholimpo.org"
                    class="flex items-center gap-2 text-stone-300 transition hover:text-green-400 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-green-400"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                    </svg>
                    contato@caminholimpo.org
                </a>
                <a
                    href="https://wa.me/5511999999999"
                    target="_blank"
                    class="flex items-center gap-2 text-stone-300 transition hover:text-green-400 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-green-400"
                >
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    (11) 99999-9999
                </a>
            </div>

            <div class="border-t border-stone-800 pt-8">
                <p class="text-sm text-stone-500">&copy; 2024 Caminho Limpo. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const navigationToggle = document.querySelector('#navigation-toggle');
            const mobileNavigation = document.querySelector('#mobile-navigation');
            const menuIcon = navigationToggle.querySelector('[data-menu-icon]');
            const closeIcon = navigationToggle.querySelector('[data-close-icon]');

            const setNavigationOpen = (isOpen) => {
                navigationToggle.setAttribute('aria-expanded', String(isOpen));
                navigationToggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
                mobileNavigation.classList.toggle('hidden', !isOpen);
                menuIcon.classList.toggle('hidden', isOpen);
                closeIcon.classList.toggle('hidden', !isOpen);
            };

            navigationToggle.addEventListener('click', () => {
                setNavigationOpen(navigationToggle.getAttribute('aria-expanded') !== 'true');
            });

            mobileNavigation.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => setNavigationOpen(false));
            });

            document.body.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && navigationToggle.getAttribute('aria-expanded') === 'true') {
                    setNavigationOpen(false);
                    navigationToggle.focus();
                }
            });
        })();
    </script>
</body>
</html>
