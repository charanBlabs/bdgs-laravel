<?php

namespace Database\Seeders;

use App\Models\BdgsDataType;
use Illuminate\Database\Seeder;

class DataTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['slug' => 'blog', 'name' => 'Blog Post', 'name_plural' => 'Blog Posts', 'sort_order' => 1, 'supports' => ['featured_image' => true, 'gallery' => false]],
            ['slug' => 'event', 'name' => 'Event', 'name_plural' => 'Events', 'sort_order' => 2, 'supports' => ['featured_image' => true, 'dates' => true]],
            ['slug' => 'solution', 'name' => 'Solution', 'name_plural' => 'Solutions', 'sort_order' => 3, 'supports' => ['featured_image' => true, 'pricing' => true]],
            ['slug' => 'tool', 'name' => 'Tool', 'name_plural' => 'Tools', 'sort_order' => 4, 'supports' => ['featured_image' => true, 'pricing' => true, 'demo_url' => true]],
            ['slug' => 'theme', 'name' => 'Theme', 'name_plural' => 'Themes', 'sort_order' => 5, 'supports' => ['featured_image' => true, 'preview_url' => true]],
        ];

        foreach ($types as $type) {
            BdgsDataType::query()->updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
