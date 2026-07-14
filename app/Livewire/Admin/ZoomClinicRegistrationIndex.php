<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UsesBdgsPagination;
use App\Models\BdgsZoomClinic;
use App\Models\BdgsZoomClinicRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ZoomClinicRegistrationIndex extends Component
{
    use UsesBdgsPagination;
    use WithPagination;

    /** @var 'admin'|'dashboard' */
    public string $surface = 'admin';

    #[Url(as: 'clinic_id', except: '')]
    public string $clinicId = '';

    public function mount(string $surface = 'admin', ?int $clinicId = null): void
    {
        Gate::authorize('manage-content');

        $this->surface = in_array($surface, ['admin', 'dashboard'], true) ? $surface : 'admin';

        if ($clinicId) {
            $this->clinicId = (string) $clinicId;
        }
    }

    public function updatedClinicId(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $clinicId = $this->clinicId !== '' ? (int) $this->clinicId : null;

        $registrations = BdgsZoomClinicRegistration::query()
            ->with('clinic:clinic_id,title,session_starts_at')
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->latest('registered_at')
            ->paginate(25);

        $clinics = BdgsZoomClinic::query()
            ->orderByDesc('session_starts_at')
            ->get(['clinic_id', 'title', 'session_starts_at']);

        $filteredClinic = $clinicId
            ? $clinics->firstWhere('clinic_id', $clinicId)
            : null;

        $view = $this->surface === 'dashboard'
            ? 'livewire.admin.zoom-clinic-registration-index-dashboard'
            : 'livewire.admin.zoom-clinic-registration-index-admin';

        return view($view, [
            'registrations' => $registrations,
            'clinics' => $clinics,
            'clinicId' => $clinicId,
            'filteredClinic' => $filteredClinic,
        ]);
    }
}
