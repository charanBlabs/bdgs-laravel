<?php

namespace Database\Seeders;

use App\Models\BdgsCategory;
use App\Models\BdgsDataType;
use Illuminate\Database\Seeder;

class SolutionCategorySeeder extends Seeder
{
    /** @var array<int, array{name: string, slug: string, description: string, sort_order: int}> */
    private array $hubs = [
        [
            'name' => 'SEO & Schema',
            'slug' => 'seo',
            'description' => 'Advanced markup and technical SEO. Rank higher in search and AI results.',
            'sort_order' => 1,
        ],
        [
            'name' => 'Lead Generation & Conversion',
            'slug' => 'lead-gen',
            'description' => 'Capture more leads and turn visitors into members.',
            'sort_order' => 2,
        ],
        [
            'name' => 'Member Profile Enhancement',
            'slug' => 'member-profiles',
            'description' => 'Custom profile layouts that make members stand out.',
            'sort_order' => 3,
        ],
        [
            'name' => 'Search & Discovery',
            'slug' => 'search',
            'description' => 'Optimized search flows so visitors find what they need faster.',
            'sort_order' => 4,
        ],
        [
            'name' => 'Page Design & Development',
            'slug' => 'page-design',
            'description' => 'Stunning layouts built for modern directory sites.',
            'sort_order' => 5,
        ],
        [
            'name' => 'Content & Engagement',
            'slug' => 'content',
            'description' => 'Keep audiences hooked with structured, engaging content.',
            'sort_order' => 6,
        ],
        [
            'name' => 'Integrations',
            'slug' => 'integrations',
            'description' => 'Connect your favorite tools with clean API integrations.',
            'sort_order' => 7,
        ],
        [
            'name' => 'Member Management & Automation',
            'slug' => 'member-management',
            'description' => 'Approval workflows, dashboards, and member control.',
            'sort_order' => 8,
        ],
    ];

    public function run(): void
    {
        $solutionType = BdgsDataType::query()->where('slug', 'solution')->firstOrFail();

        foreach ($this->hubs as $hub) {
            BdgsCategory::query()->updateOrCreate(
                [
                    'slug' => $hub['slug'],
                    'post_type_id' => $solutionType->id,
                ],
                [
                    'name' => $hub['name'],
                    'description' => $hub['description'],
                    'sort_order' => $hub['sort_order'],
                ]
            );
        }
    }

    /** Reserved category slugs — must not be used as solution post slugs. */
    public static function reservedSlugs(): array
    {
        return array_column((new self)->hubs, 'slug');
    }
}
