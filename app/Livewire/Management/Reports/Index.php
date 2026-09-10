<?php

declare(strict_types=1);

namespace App\Livewire\Management\Reports;

use App\Actions\Reports\DeleteReport;
use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
final class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $status = '';

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'category', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function delete(string $protocol, DeleteReport $action): void
    {
        $action->handle(Report::query()->where('protocol', $protocol)->firstOrFail());

        session()->flash('success', 'Relato excluído com sucesso.');
    }

    public function render(): View
    {
        $reports = Report::query()
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($query): void {
                    $query
                        ->where('protocol', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhere('address', 'like', "%{$this->search}%");
                });
            })
            ->when($this->category !== '', fn ($query) => $query->where('category', $this->category))
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.management.reports.index', [
            'reports'          => $reports,
            'categories'       => ReportCategoryEnum::options(),
            'statuses'         => ReportStatusEnum::options(),
            'totalReports'     => Report::query()->count(),
            'receivedReports'  => Report::query()->where('status', ReportStatusEnum::Received->value)->count(),
            'publishedReports' => Report::query()->where('status', ReportStatusEnum::Published->value)->count(),
            'headers'          => [
                ['key' => 'address', 'label' => 'Relato'],
                ['key' => 'category', 'label' => 'Categoria'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'created_at', 'label' => 'Data'],
            ],
        ]);
    }
}
