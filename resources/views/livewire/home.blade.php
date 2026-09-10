{{-- Hero --}}
<section class="scroll-mt-24 px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
    <div
        class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start"
    >
        {{-- Conteúdo --}}
        <div class="lg:pt-16">
            <x-badge
                value="Serviço público de limpeza urbana"
                class="badge-outline badge-lg"
            />

            <h1 class="mt-6 text-4xl leading-tight font-bold tracking-tight sm:text-5xl">
                Um caminho simples para uma cidade mais limpa.
            </h1>

            <p class="text-base-content/70 mt-6 max-w-xl text-lg leading-relaxed">
                Cidadãos registram problemas de limpeza urbana com foto,
                localização e categoria. A administração municipal recebe,
                organiza, acompanha e atualiza cada demanda até a solução.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <x-button
                    label="Registrar ocorrência"
                    icon="o-plus"
                    :link="route('management.reports.create')"
                    class="btn-primary"
                />

                <x-button
                    label="Ver ocorrências"
                    icon="o-map"
                    :link="route('reports.index')"
                    class="btn-outline"
                />
            </div>
        </div>

        {{-- Representação do mural --}}
        <x-card shadow class="border-base-content/10 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold">
                        Mural de ocorrências
                    </p>

                    <p class="text-base-content/60 mt-1 text-xs">
                        Registros próximos
                    </p>
                </div>

                <x-badge
                    value="12 pontos"
                    class="badge-outline badge-sm"
                />
            </div>

            {{-- Mapa --}}
            <div
                class="bg-base-200 rounded-box relative mt-4 min-h-[430px] overflow-hidden"
            >
                {{-- Simulação de ruas / áreas --}}
                <div class="absolute inset-0 opacity-30">
                    <div
                        class="border-base-content/20 absolute top-[24%] left-[-10%] h-20 w-[120%] -rotate-6 border-t"
                    ></div>

                    <div
                        class="border-base-content/20 absolute top-[54%] left-[-10%] h-20 w-[120%] rotate-3 border-t"
                    ></div>

                    <div
                        class="border-base-content/20 absolute top-[-10%] left-[32%] h-[120%] w-20 rotate-12 border-l"
                    ></div>

                    <div
                        class="border-base-content/20 absolute top-[-10%] left-[68%] h-[120%] w-20 -rotate-12 border-l"
                    ></div>
                </div>

                {{-- Ocorrência 1 --}}
                <div class="group absolute top-[14%] left-[17%]">
                    <div
                        class="bg-error text-error-content flex size-9 items-center justify-center rounded-full shadow"
                    >
                        <x-icon name="o-map-pin" class="size-5" />
                    </div>
                </div>

                {{-- Ocorrência selecionada --}}
                <div class="absolute top-[34%] left-[43%]">
                    <div
                        class="bg-warning text-warning-content flex size-10 items-center justify-center rounded-full shadow"
                    >
                        <x-icon name="o-map-pin" class="size-6" />
                    </div>

                    <div
                        class="border-base-content/10 bg-base-100 absolute top-12 left-1/2 z-10 w-52 -translate-x-1/2 rounded-lg border p-3 shadow-lg"
                    >
                        <div class="flex items-start gap-3">
                            <div
                                class="bg-warning/10 text-warning flex size-9 shrink-0 items-center justify-center rounded-lg"
                            >
                                <x-icon
                                    name="o-trash"
                                    class="size-5"
                                />
                            </div>

                            <div>
                                <p class="text-sm font-semibold">
                                    Entulho de obra
                                </p>

                                <p class="text-base-content/60 mt-1 text-xs">
                                    Bairro Centro
                                </p>
                            </div>
                        </div>

                        <div
                            class="border-base-content/10 mt-3 flex items-center gap-2 border-t pt-3"
                        >
                            <x-icon
                                name="o-map-pin"
                                class="text-base-content/50 size-4"
                            />

                            <span class="text-base-content/60 text-xs">
                                Rua das Flores
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Ocorrência 3 --}}
                <div class="absolute right-[15%] top-[22%]">
                    <div
                        class="bg-info text-info-content flex size-8 items-center justify-center rounded-full shadow"
                    >
                        <x-icon name="o-map-pin" class="size-5" />
                    </div>
                </div>

                {{-- Ocorrência 4 --}}
                <div class="absolute right-[18%] bottom-[15%]">
                    <div
                        class="bg-success text-success-content flex size-9 items-center justify-center rounded-full shadow"
                    >
                        <x-icon name="o-map-pin" class="size-5" />
                    </div>
                </div>

                {{-- Ocorrência 5 --}}
                <div class="absolute bottom-[19%] left-[19%]">
                    <div
                        class="bg-primary text-primary-content flex size-8 items-center justify-center rounded-full shadow"
                    >
                        <x-icon name="o-map-pin" class="size-5" />
                    </div>
                </div>

                {{-- Legenda --}}
                <div
                    class="border-base-content/10 bg-base-100/90 absolute right-4 bottom-4 rounded-lg border px-3 py-2 backdrop-blur-sm"
                >
                    <div class="flex items-center gap-2">
                        <x-icon
                            name="o-map-pin"
                            class="text-primary size-4"
                        />

                        <span class="text-xs font-medium">
                            Ocorrências registradas
                        </span>
                    </div>
                </div>
            </div>
        </x-card>


    </div>
</section>
