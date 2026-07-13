<?php

namespace Tests\Feature;

use App\Models\BdgsCategory;
use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use App\Models\BdgsRole;
use App\Models\User;
use Database\Seeders\DataTypeSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SolutionCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SolutionPostManageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private BdgsDataType $solutionType;

    private BdgsCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(DataTypeSeeder::class);
        $this->seed(SolutionCategorySeeder::class);

        $this->admin = User::factory()->create([
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $adminRole = BdgsRole::query()->where('name', 'admin')->firstOrFail();
        $this->admin->roles()->attach($adminRole->id);

        $this->solutionType = BdgsDataType::query()->where('slug', 'solution')->firstOrFail();
        $this->category = BdgsCategory::query()
            ->where('post_type_id', $this->solutionType->id)
            ->where('slug', 'integrations')
            ->firstOrFail();
    }

    public function test_admin_can_create_edit_and_publish_solution(): void
    {
        $create = $this->actingAs($this->admin)->post(route('admin.posts.store', 'solution'), [
            'title' => 'Test Mailchimp Bridge',
            'slug' => 'test-mailchimp-bridge',
            'live' => 'yes',
            'visibility' => 'public',
            'short_title' => 'Mailchimp Bridge',
            'excerpt' => 'Connect newsletter signups to Mailchimp.',
            'content' => '<p>Full description of the solution.</p>',
            'pricing_type' => 'fixed_price',
            'price' => '99.00',
            'implementation_type' => 'quick',
            'delivery_time' => '3_days',
            'warranty' => '30 Days',
            'wysiwyg_cta' => 'no',
            'product_type' => 'tool',
            'category_id' => $this->category->id,
            'meta_title' => 'Mailchimp Bridge SEO Title',
            'meta_description' => 'SEO description for Mailchimp Bridge',
            'robots' => 'index,follow',
        ]);

        $create->assertRedirect();

        $post = BdgsDataPost::query()->where('slug', 'test-mailchimp-bridge')->first();
        $this->assertNotNull($post);
        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertSame('fixed_price', $post->pricing_type);
        $this->assertTrue($post->categories->contains('id', $this->category->id));
        $this->assertSame('tool', $post->getMetaValue('product_type'));
        $this->assertSame('Mailchimp Bridge SEO Title', $post->seo?->meta_title);

        $public = $this->get('/solutions/test-mailchimp-bridge');
        $public->assertOk();
        $public->assertSee('Test Mailchimp Bridge', false);

        $update = $this->actingAs($this->admin)->put(route('admin.posts.update', ['solution', $post]), [
            'title' => 'Test Mailchimp Bridge Updated',
            'slug' => 'test-mailchimp-bridge',
            'live' => 'yes',
            'visibility' => 'public',
            'short_title' => 'Mailchimp Bridge',
            'excerpt' => 'Updated excerpt.',
            'content' => '<p>Updated body.</p>',
            'pricing_type' => 'starts_from',
            'price' => '149.00',
            'commitment_price' => '49.00',
            'implementation_type' => 'semi_custom',
            'delivery_time' => '1_week',
            'warranty' => '1 Year',
            'wysiwyg_cta' => 'yes',
            'product_type' => 'service',
            'category_id' => $this->category->id,
            'meta_title' => 'Updated SEO',
            'meta_description' => 'Updated meta description',
            'robots' => 'index,follow',
        ]);

        $update->assertRedirect();
        $post->refresh();
        $this->assertSame('Test Mailchimp Bridge Updated', $post->title);
        $this->assertSame('starts_from', $post->pricing_type);
        $this->assertTrue((bool) $post->wysiwyg_cta);
        $this->assertSame('Updated SEO', $post->seo?->meta_title);
    }

    public function test_dashboard_can_create_clone_and_upload_thumbnail(): void
    {
        Storage::fake('public');

        $create = $this->actingAs($this->admin)->post(route('dashboard.content.store', 'solution'), [
            'title' => 'Dashboard Solution',
            'slug' => 'dashboard-solution',
            'live' => 'yes',
            'visibility' => 'public',
            'excerpt' => 'Created from dashboard.',
            'content' => '<p>Dashboard body</p>',
            'pricing_type' => 'free',
            'wysiwyg_cta' => 'no',
            'category_id' => $this->category->id,
            'meta_title' => 'Dashboard SEO',
            'meta_description' => 'Dashboard meta',
            'robots' => 'index,follow',
        ]);

        $create->assertRedirect();
        $post = BdgsDataPost::query()->where('slug', 'dashboard-solution')->firstOrFail();

        $index = $this->actingAs($this->admin)->get(route('dashboard.content.index', 'solution'));
        $index->assertOk();
        $index->assertSee('/solutions/dashboard-solution', false);

        $clone = $this->actingAs($this->admin)->post(route('dashboard.content.clone', ['solution', $post]));
        $clone->assertRedirect();
        $cloned = BdgsDataPost::query()->where('title', 'Dashboard Solution (Copy)')->first();
        $this->assertNotNull($cloned);
        $this->assertSame('draft', $cloned->status);
        $this->assertSame('Dashboard SEO', $cloned->seo?->meta_title);

        if (! function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD required for thumbnail upload.');
        }

        $img = imagecreatetruecolor(40, 30);
        $tmp = tempnam(sys_get_temp_dir(), 'sol');
        imagepng($img, $tmp);
        imagedestroy($img);

        $upload = $this->actingAs($this->admin)->post(
            route('dashboard.content.thumbnail.upload', ['solution', $post]),
            ['thumbnail' => new UploadedFile($tmp, 'thumb.png', 'image/png', null, true)]
        );
        @unlink($tmp);

        $upload->assertRedirect();
        $post->refresh();
        $this->assertNotNull($post->featured_media_id);
        $this->assertSame('image/webp', $post->featuredMedia?->mime_type);
        $this->assertCount(3, $post->featuredMedia?->variants ?? collect());
    }

    public function test_public_category_and_sitemap_posts_use_solutions_plural(): void
    {
        BdgsDataPost::query()->create([
            'post_type_id' => $this->solutionType->id,
            'author_id' => $this->admin->id,
            'title' => 'Public Solution',
            'slug' => 'public-solution',
            'excerpt' => 'Public excerpt',
            'content' => '<p>Body</p>',
            'status' => 'published',
            'visibility' => 'public',
            'published_at' => now(),
            'pricing_type' => 'fixed_price',
            'price' => 50,
        ])->categories()->sync([$this->category->id]);

        $this->get('/solutions/integrations')->assertOk()->assertSee('Public Solution', false);
        $this->get('/solutions/public-solution')->assertOk();

        $sitemap = $this->get('/sitemap-posts.xml');
        $sitemap->assertOk();
        $sitemap->assertSee('/solutions/public-solution', false);
        $sitemap->assertSee('/solutions/integrations', false);
        $sitemap->assertDontSee('/solution/public-solution', false);
    }
}
