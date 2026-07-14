<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UsesBdgsPagination;
use App\Models\BdgsZoomClinic;
use App\Services\ZoomClinicService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ZoomClinicIndex extends Component
{
    use UsesBdgsPagination;
    use WithPagination;

    /** @var 'admin'|'dashboard' */
    public string $surface = 'admin';

    #[Url(except: 'active')]
    public string $status = 'active';

    public function mount(string $surface = 'admin'): void
    {
        Gate::authorize('manage-content');

        $this->surface = in_array($surface, ['admin', 'dashboard'], true) ? $surface : 'admin';

        if (! in_array($this->status, ['active', 'completed', 'cancelled', 'all'], true)) {
            $this->status = 'active';
        }

        app(ZoomClinicService::class)->syncLifecycleStatuses();
    }

    public function updatedStatus(): void
    {
        if (! in_array($this->status, ['active', 'completed', 'cancelled', 'all'], true)) {
            $this->status = 'active';
        }

        $this->resetPage();
    }

    public function render(): View
    {
        $query = BdgsZoomClinic::query()
            ->withCount('confirmedRegistrations')
            ->latest('session_starts_at');

        match ($this->status) {
            'active' => $query->whereIn('status', ['scheduled', 'live']),
            'completed' => $query->where('status', 'completed'),
            'cancelled' => $query->where('status', 'cancelled'),
            default => null,
        };

        $view = $this->surface === 'dashboard'
            ? 'livewire.admin.zoom-clinic-index-dashboard'
            : 'livewire.admin.zoom-clinic-index-admin';

        return view($view, [
            'clinics' => $query->paginate(20),
            'statusFilter' => $this->status,
        ]);
    }
}
