<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Enums\ReportCategoryEnum;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
final class Feed extends Component
{
    use WithPagination;

    #[Url]
    public string $category = '';

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $reports = Report::query()
            ->published()
            ->when($this->category !== '', fn ($query) => $query->where('category', $this->category))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('livewire.reports.feed', [
            'reports'    => $reports,
            'categories' => ReportCategoryEnum::options(),
        ]);
    }
}
