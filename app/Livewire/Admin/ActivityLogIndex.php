<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UsesBdgsPagination;
use App\Models\BdgsActivityLog;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogIndex extends Component
{
    use UsesBdgsPagination;
    use WithPagination;

    #[Url(except: '')]
    public string $action = '';

    public function updatedAction(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = BdgsActivityLog::query()->with('user')->latest('created_at');

        $action = trim($this->action);
        if ($action !== '') {
            $query->where('action', $action);
        }

        return view('livewire.admin.activity-log-index', [
            'logs' => $query->paginate(50),
        ]);
    }
}
