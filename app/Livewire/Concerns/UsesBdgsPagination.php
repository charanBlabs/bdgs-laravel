<?php

namespace App\Livewire\Concerns;

trait UsesBdgsPagination
{
    public function paginationView(): string
    {
        return 'livewire.pagination.bdgs';
    }

    public function updatedPaginators(mixed $page, string $pageName): void
    {
        $this->dispatch('bdgs-scroll-list-top');
    }
}
