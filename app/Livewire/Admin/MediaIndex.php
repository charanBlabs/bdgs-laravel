<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UsesBdgsPagination;
use App\Models\BdgsMedia;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MediaIndex extends Component
{
    use UsesBdgsPagination;
    use WithPagination;

    #[Url(except: '')]
    public string $q = '';

    public function updatedQ(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = BdgsMedia::query()->with(['uploader', 'variants'])->latest();

        $search = trim($this->q);
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        return view('livewire.admin.media-index', [
            'media' => $query->paginate(24),
        ]);
    }
}
