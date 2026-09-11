<div>
    <section id="inicio" class="scroll-mt-20 px-4 py-12 sm:px-6 lg:px-8 lg:py-20">
        <div class="mx-auto grid max-w-6xl items-center gap-12 lg:grid-cols-[1fr_0.85fr]">
            <div class="max-w-2xl">
                <x-badge value="Gestão de limpeza urbana" class="badge-outline" />
                <h1 class="mt-6 text-4xl leading-tight font-bold tracking-tight sm:text-5xl lg:text-6xl">
                    Um caminho simples para cuidar melhor da cidade.
                </h1>
                <p class="text-base-content/70 mt-6 max-w-xl text-lg leading-relaxed">
                    Registre um ponto de descarte com foto e localização. A equipe organiza as ocorrências no mapa,
                    acompanha cada providência e mantém um histórico do que foi resolvido.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <x-button
                        label="Registrar ocorrência"
                        icon="o-plus"
                        :link="route('management.reports.create')"
                        class="btn-primary"
                    />
                    <x-button
                        label="Ver mapa público"
                        icon="o-map"
                        :link="route('reports.index')"
                        class="btn-outline"
                    />
                </div>
                <p class="text-base-content/55 mt-5 text-sm">
                    Feito para começar pequeno: uma equipe, um mapa e uma rotina de acompanhamento.
                </p>
            </div>

            <x-card
                title="O que a equipe enxerga"
                subtitle="Uma ocorrência do registro à solução."
                shadow
                class="border-base-content/10 border"
            >
                <div class="space-y-3">
                    <div class="bg-base-200 flex items-start gap-3 rounded-xl p-4">
                        <x-icon name="o-camera" class="text-primary mt-0.5 size-5 shrink-0" />
                        <div>
                            <p class="font-semibold">Foto e localização</p>
                            <p class="text-base-content/60 mt-1 text-sm">
                                A situação é registrada no local, com coordenadas e referência.
                            </p>
                        </div>
                    </div>
                    <div class="bg-base-200 flex items-start gap-3 rounded-xl p-4">
                        <x-icon name="o-clipboard-document-list" class="text-primary mt-0.5 size-5 shrink-0" />
                        <div>
                            <p class="font-semibold">Triagem e prioridade</p>
                            <p class="text-base-content/60 mt-1 text-sm">
                                A equipe organiza os casos por situação, categoria e município.
                            </p>
                        </div>
                    </div>
                    <div class="bg-base-200 flex items-start gap-3 rounded-xl p-4">
                        <x-icon name="o-check-circle" class="text-success mt-0.5 size-5 shrink-0" />
                        <div>
                            <p class="font-semibold">Acompanhamento</p>
                            <p class="text-base-content/60 mt-1 text-sm">
                                Cada atualização fica registrada para orientar a próxima ação.
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </section>

    <section id="como-funciona" class="bg-base-200/60 scroll-mt-20 px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
        <div class="mx-auto max-w-6xl">
            <div class="max-w-2xl">
                <p class="text-primary text-sm font-semibold tracking-wide uppercase">Como funciona</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Do ponto crítico à ação organizada.</h2>
                <p class="text-base-content/70 mt-4 text-lg">
                    O sistema transforma uma vistoria ou um relato em uma fila de trabalho clara para a administração.
                </p>
            </div>
            <div class="mt-10 grid gap-4 md:grid-cols-3">
                <x-card
                    title="1. Registrar"
                    subtitle="Uma foto ajuda a confirmar a situação."
                    shadow
                    class="border-base-content/10 border"
                >
                    <x-icon name="o-camera" class="text-primary size-7" />
                </x-card>
                <x-card
                    title="2. Organizar"
                    subtitle="Filtros, mapa e status mostram o que precisa de atenção."
                    shadow
                    class="border-base-content/10 border"
                >
                    <x-icon name="o-map" class="text-primary size-7" />
                </x-card>
                <x-card
                    title="3. Resolver"
                    subtitle="A equipe atualiza o caso e mantém o histórico."
                    shadow
                    class="border-base-content/10 border"
                >
                    <x-icon name="o-check-circle" class="text-success size-7" />
                </x-card>
            </div>
        </div>
    </section>

    <section id="para-municipios" class="scroll-mt-20 px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
        <div class="mx-auto grid max-w-6xl items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div>
                <p class="text-primary text-sm font-semibold tracking-wide uppercase">Para a administração municipal</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    Uma base simples para decidir onde agir primeiro.
                </h2>
                <p class="text-base-content/70 mt-4 max-w-2xl text-lg leading-relaxed">
                    O painel reúne os relatos, as vistorias da própria equipe e as áreas municipais no mesmo lugar.
                    Assim, a gestão começa com dados concretos e pode crescer conforme a rotina pedir.
                </p>
                <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                    <x-button
                        label="Abrir painel de gestão"
                        icon="o-squares-2x2"
                        :link="route('management.dashboard')"
                        class="btn-primary"
                    />
                    <x-button
                        label="Conhecer o mural"
                        icon="o-arrow-right"
                        :link="route('reports.index')"
                        class="btn-ghost"
                    />
                </div>
            </div>

            <x-card shadow class="border-primary/20 bg-primary/5 border">
                <div class="flex items-center gap-3">
                    <div class="bg-primary text-primary-content flex size-11 items-center justify-center rounded-xl">
                        <x-icon name="o-building-office-2" class="size-6" />
                    </div>
                    <div>
                        <p class="font-semibold">Comece pelo seu território</p>
                        <p class="text-base-content/60 mt-1 text-sm">Defina os municípios e suas geocercas no mapa.</p>
                    </div>
                </div>
                <div class="divider my-5"></div>
                <p class="text-base-content/70 text-sm leading-relaxed">
                    A “gaiola” do sistema é uma geocerca: um círculo de referência ao redor do município. Ela ajuda a
                    organizar os pontos no mapa, sem criar uma estrutura física ou uma etapa extra para a equipe.
                </p>
                <x-button
                    label="Configurar áreas municipais"
                    icon="o-map-pin"
                    :link="route('management.municipality-geofences.index')"
                    class="btn-outline mt-5 w-full"
                />
            </x-card>
        </div>
    </section>

    <section class="bg-primary text-primary-content px-4 py-14 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-bold sm:text-3xl">Quer testar uma primeira rotina?</h2>
                <p class="text-primary-content/80 mt-2 max-w-2xl">
                    Cadastre um ponto, veja no mapa e acompanhe a próxima ação.
                </p>
            </div>
            <x-button
                label="Registrar ocorrência"
                icon="o-plus"
                :link="route('management.reports.create')"
                class="btn-neutral shrink-0"
            />
        </div>
    </section>
</div>
