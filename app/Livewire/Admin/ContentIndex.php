<?php

namespace App\Livewire\Admin;

use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use App\Livewire\Concerns\UsesBdgsPagination;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ContentIndex extends Component
{
    use UsesBdgsPagination;
    use WithPagination;

    public string $type = '';

    #[Url(except: '')]
    public string $q = '';

    #[Url(except: '')]
    public string $status = '';

    #[Url(except: 'newest')]
    public string $sort = 'newest';

    #[Url(as: 'per_page', except: 5)]
    public int $perPage = 5;

    /** Bumps when filters clear so debounced search inputs remount cleanly. */
    public int $filterEpoch = 0;

    public function mount(string $type): void
    {
        Gate::authorize('manage-content');

        $dataType = BdgsDataType::query()
            ->where('slug', $type)
            ->where('is_active', true)
            ->firstOrFail();

        $this->type = $dataType->slug;

        if (! in_array($this->perPage, [5, 10, 25, 50], true)) {
            $this->perPage = 5;
        }
    }

    public function updatedQ(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        if (! in_array($this->perPage, [5, 10, 25, 50], true)) {
            $this->perPage = 5;
        }

        $this->resetPage();
    }

    public function clearFilters(): void
    {
        // Assign defaults explicitly — $this->reset() restores URL-hydrated
        // mount values, so clearing would no-op when filters are in the query string.
        $this->q = '';
        $this->status = '';
        $this->sort = 'newest';
        $this->perPage = 5;
        $this->filterEpoch++;
        $this->resetPage();
    }

    public function render(): View
    {
        $dataType = BdgsDataType::query()
            ->where('slug', $this->type)
            ->where('is_active', true)
            ->firstOrFail();

        $query = BdgsDataPost::query()
            ->with(['author', 'featuredMedia.variants', 'categories'])
            ->where('post_type_id', $dataType->id);

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        $keyword = trim($this->q);
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%");
            });
        }

        $query = match ($this->sort) {
            'oldest' => $query->oldest('created_at'),
            'updated_first' => $query->latest('updated_at'),
            'updated_last' => $query->oldest('updated_at'),
            'published_first' => $query->latest('published_at'),
            'published_last' => $query->oldest('published_at'),
            default => $query->latest('created_at'),
        };

        return view('livewire.admin.content-index', [
            'dataType' => $dataType,
            'posts' => $query->paginate($this->perPage),
        ]);
    }
}
