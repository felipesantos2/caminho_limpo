<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Models\Report;
use App\Services\Location\GeneratePlusCode;
use Illuminate\Database\Seeder;

final class RegionalDebrisReportSeeder extends Seeder
{
    public function run(): void
    {
        $plusCode = app(GeneratePlusCode::class);

        foreach ($this->reports() as $index => $data) {
            Report::query()->updateOrCreate(
                ['protocol' => $data['protocol']],
                [
                    ...$data,
                    'category'   => ReportCategoryEnum::ConstructionDebris,
                    'plus_code'  => $plusCode->handle((float) $data['latitude'], (float) $data['longitude']),
                    'image_path' => 'attached_assets/br/problema_entulho.jpg',
                    'created_at' => now()->subDays($index),
                ],
            );
        }
    }

    /**
     * Pontos aproximados e fictícios para demonstração do fluxo de gestão.
     *
     * @return array<int, array<string, mixed>>
     */
    private function reports(): array
    {
        return [
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXK', 'Restos de tijolos e concreto acumulados próximos à margem da via.', 'Acesso norte, Novo Cruzeiro/MG', '-17.4629000', '-41.8814000', ReportStatusEnum::Received),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXM', 'Montante de terra, telhas e madeira deixado ao lado da passagem.', 'Entorno da área central, Novo Cruzeiro/MG', '-17.4708000', '-41.8732000', ReportStatusEnum::Triage),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXN', 'Materiais de demolição ocupando parte do acostamento da estrada.', 'Saída para a zona rural, Novo Cruzeiro/MG', '-17.4781000', '-41.8893000', ReportStatusEnum::Published),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXP', 'Entulho de pequena reforma acumulado perto de uma área de circulação.', 'Acesso leste, Águas Formosas/MG', '-17.0780000', '-40.9289000', ReportStatusEnum::Received),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXQ', 'Blocos, areia e embalagens de obra próximos à margem de drenagem.', 'Entorno da área central, Águas Formosas/MG', '-17.0867000', '-40.9381000', ReportStatusEnum::Triage),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXR', 'Restos de concreto e cerâmica depositados no acesso a uma via local.', 'Saída sul, Águas Formosas/MG', '-17.0935000', '-40.9316000', ReportStatusEnum::Published),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXS', 'Resíduos de construção espalhados em trecho lateral de acesso urbano.', 'Acesso norte, Teófilo Otoni/MG', '-17.8489000', '-41.5088000', ReportStatusEnum::Received),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXT', 'Acúmulo de tijolos, reboco e madeira próximo a um terreno aberto.', 'Entorno da região central, Teófilo Otoni/MG', '-17.8607000', '-41.5019000', ReportStatusEnum::Triage),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXV', 'Material de demolição reduzindo o espaço disponível no acostamento.', 'Acesso sul, Teófilo Otoni/MG', '-17.8702000', '-41.5145000', ReportStatusEnum::Published),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXW', 'Restos de obra e solo depositados perto da margem da estrada.', 'Acesso norte, Itaipé/MG', '-17.3958000', '-41.6673000', ReportStatusEnum::Received),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXX', 'Telhas quebradas e blocos acumulados próximos a uma área de passagem.', 'Entorno da área central, Itaipé/MG', '-17.4056000', '-41.6741000', ReportStatusEnum::Triage),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXY', 'Pequeno descarte de concreto e madeira na saída para a zona rural.', 'Saída sul, Itaipé/MG', '-17.4131000', '-41.6629000', ReportStatusEnum::Published),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMXZ', 'Materiais de construção descartados na lateral de uma via de acesso.', 'Acesso norte, Catuji/MG', '-17.2963000', '-41.5298000', ReportStatusEnum::Received),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMY0', 'Acúmulo de blocos e cerâmica próximo a uma área sem fechamento.', 'Entorno da área central, Catuji/MG', '-17.3039000', '-41.5217000', ReportStatusEnum::Triage),
            $this->report('CL-01M264XPKCVWGSEHM3BWJ0AMY1', 'Restos de demolição concentrados em trecho lateral do acesso municipal.', 'Saída sul, Catuji/MG', '-17.3115000', '-41.5340000', ReportStatusEnum::Published),
        ];
    }

    /** @return array<string, mixed> */
    private function report(
        string $protocol,
        string $description,
        string $address,
        string $latitude,
        string $longitude,
        ReportStatusEnum $status,
    ): array {
        return compact('protocol', 'description', 'address', 'latitude', 'longitude', 'status');
    }
}
