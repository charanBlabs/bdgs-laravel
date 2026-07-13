<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\ZoomClinicController as AdminZoomClinicController;
use App\Http\Controllers\Admin\ZoomClinicRegistrationController as AdminZoomClinicRegistrationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\BlabsReviewController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\Dashboard\AdminDashboardController as DashboardAdminController;
use App\Http\Controllers\Dashboard\ContactController;
use App\Http\Controllers\Dashboard\ContentManageController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\MyZoomClinicController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\ZoomClinicController as DashboardZoomClinicController;
use App\Http\Controllers\Dashboard\ZoomClinicRegistrationController as DashboardZoomClinicRegistrationController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\Frontend\ContentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryWebController;
use App\Http\Controllers\LicenseSdclController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SitemapPostsController;
use App\Http\Controllers\TermsOfUseController;
use App\Http\Controllers\WebinarsController;
use App\Http\Controllers\ZoomClinicsController;
use App\Http\Middleware\ProvideMarkdownResponse;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class)->name('robots');

Route::get('/inquiry/form-guard', [InquiryWebController::class, 'formGuard'])
    ->middleware('throttle:30,1');

Route::post('/inquiry/submit', [InquiryWebController::class, 'store'])
    ->middleware('throttle:5,1');

Route::get('/feed/blog.xml', [FeedController::class, 'blog'])->name('feed.blog');
Route::get('/sitemap-posts.xml', [SitemapPostsController::class, 'index'])->name('sitemap.posts');

Route::middleware(ProvideMarkdownResponse::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/index.md', [HomeController::class, 'index']);

    Route::get('/services', [ServicesController::class, 'index']);
    Route::get('/services/', [ServicesController::class, 'index'])->name('services');
    Route::get('/services.md', [ServicesController::class, 'index']);
    Route::get('/services/index.md', [ServicesController::class, 'index']);

    Route::get('/customization', [CustomizationController::class, 'index'])->name('customization');
    Route::get('/customization/', [CustomizationController::class, 'index']);
    Route::get('/customization.md', [CustomizationController::class, 'index']);
    Route::get('/customization/index.md', [CustomizationController::class, 'index']);

    Route::get('/reviews', [BlabsReviewController::class, 'index'])->name('reviews');
    Route::get('/reviews/', [BlabsReviewController::class, 'index']);
    Route::get('/reviews.md', [BlabsReviewController::class, 'index']);
    Route::get('/reviews/index.md', [BlabsReviewController::class, 'index']);
    Route::redirect('/blabs-review', '/reviews', 301);
    Route::redirect('/blabs-review/', '/reviews', 301);
    Route::redirect('/blabs-review.md', '/reviews.md', 301);
    Route::redirect('/blabs-review/index.md', '/reviews.md', 301);

    Route::get('/webinars', [WebinarsController::class, 'index'])->name('webinars');
    Route::get('/webinars/', [WebinarsController::class, 'index']);
    Route::get('/webinars.md', [WebinarsController::class, 'index']);
    Route::get('/webinars/index.md', [WebinarsController::class, 'index']);

    Route::get('/zoom-clinics', [ZoomClinicsController::class, 'index'])->name('zoom-clinics');
    Route::get('/zoom-clinics/', [ZoomClinicsController::class, 'index']);
    Route::get('/zoom-clinics.md', [ZoomClinicsController::class, 'index']);
    Route::get('/zoom-clinics/index.md', [ZoomClinicsController::class, 'index']);

    Route::get('/about/privacy', [PrivacyPolicyController::class, 'index'])->name('privacy');
    Route::get('/about/privacy/', [PrivacyPolicyController::class, 'index']);
    Route::get('/about/privacy.md', [PrivacyPolicyController::class, 'index']);
    Route::redirect('/privacy', '/about/privacy', 301);
    Route::redirect('/privacy/', '/about/privacy', 301);

    Route::get('/about/terms', [TermsOfUseController::class, 'index'])->name('terms');
    Route::get('/about/terms/', [TermsOfUseController::class, 'index']);
    Route::get('/about/terms.md', [TermsOfUseController::class, 'index']);
    Route::redirect('/terms', '/about/terms', 301);
    Route::redirect('/terms/', '/about/terms', 301);

    Route::get('/license/sdcl-v1', [LicenseSdclController::class, 'index'])->name('license');
    Route::get('/license/sdcl-v1/', [LicenseSdclController::class, 'index']);
    Route::get('/license/sdcl-v1.md', [LicenseSdclController::class, 'index']);
    Route::redirect('/license', '/license/sdcl-v1', 301);
    Route::redirect('/license/', '/license/sdcl-v1', 301);

    Route::get('/api-inquiry.md', fn () => abort(404));

    Route::get('/blog', [ContentController::class, 'listing'])->defaults('type', 'blog');
    Route::get('/blog/', [ContentController::class, 'listing'])->defaults('type', 'blog')->name('blog.index');
    Route::get('/blog/{slug}', [ContentController::class, 'show'])->defaults('type', 'blog')->name('blog.show');

    Route::get('/solutions', [ContentController::class, 'listing'])->defaults('type', 'solution');
    Route::get('/solutions/', [ContentController::class, 'listing'])->defaults('type', 'solution')->name('solutions.index');
    Route::get('/solutions.md', [ContentController::class, 'listing'])->defaults('type', 'solution');
    Route::get('/solutions/index.md', [ContentController::class, 'listing'])->defaults('type', 'solution');
    Route::get('/solutions/{slug}', [ContentController::class, 'show'])->defaults('type', 'solution')->name('solutions.show');

    Route::redirect('/solution', '/solutions', 301);
    Route::redirect('/solution/', '/solutions', 301);
    Route::get('/solution/{slug}', fn (string $slug) => redirect("/solutions/{$slug}", 301));

    Route::get('/tools', [ContentController::class, 'listing'])->defaults('type', 'tool');
    Route::get('/tools/', [ContentController::class, 'listing'])->defaults('type', 'tool')->name('tools.index');
    Route::get('/tools/{slug}', [ContentController::class, 'show'])->defaults('type', 'tool')->name('tools.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
        Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
        Route::get('/settings/password', [SettingsController::class, 'password'])->name('settings.password');
        Route::put('/settings/password', [PasswordController::class, 'update'])->name('settings.password.update');
        Route::get('/settings/photo', [SettingsController::class, 'photo'])->name('settings.photo');
        Route::post('/settings/photo', [SettingsController::class, 'updatePhoto'])->name('settings.photo.update');
        Route::view('/services', 'dashboard.coming-soon', ['featureLabel' => 'My Services', 'featureIcon' => 'services'])->name('services')->middleware('role:user');
        Route::view('/orders', 'dashboard.coming-soon', ['featureLabel' => 'My Orders', 'featureIcon' => 'orders'])->name('orders')->middleware('role:user');
        Route::view('/tickets', 'dashboard.coming-soon', ['featureLabel' => 'My Tickets', 'featureIcon' => 'tickets'])->name('tickets')->middleware('role:user');

        Route::get('/my-zoom-clinics', [MyZoomClinicController::class, 'index'])->name('my-zoom-clinics.index');
        Route::post('/my-zoom-clinics/{registration}/calendar-added', [MyZoomClinicController::class, 'markCalendarAdded'])
            ->name('my-zoom-clinics.calendar-added');

        Route::get('/contact', [ContactController::class, 'create'])->name('contact')->middleware('role:user');
        Route::post('/contact', [ContactController::class, 'store'])->middleware(['throttle:5,1', 'role:user'])->name('contact.store');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

        Route::middleware('role:admin')->prefix('content')->name('content.')->group(function () {
            Route::get('/{type}', [ContentManageController::class, 'index'])->name('index');
            Route::get('/{type}/create', [ContentManageController::class, 'create'])->name('create');
            Route::post('/{type}', [ContentManageController::class, 'store'])->name('store');
            Route::get('/{type}/{post}/edit', [ContentManageController::class, 'edit'])->name('edit');
            Route::put('/{type}/{post}', [ContentManageController::class, 'update'])->name('update');
            Route::post('/{type}/{post}/clone', [ContentManageController::class, 'clone'])->name('clone');
            Route::delete('/{type}/{post}', [ContentManageController::class, 'destroy'])->name('destroy');
            Route::get('/{type}/{post}/thumbnail', [ContentManageController::class, 'thumbnail'])->name('thumbnail');
            Route::post('/{type}/{post}/thumbnail', [ContentManageController::class, 'uploadThumbnail'])->name('thumbnail.upload');
            Route::delete('/{type}/{post}/thumbnail', [ContentManageController::class, 'removeThumbnail'])->name('thumbnail.remove');
        });

        Route::middleware('role:admin')->prefix('zoom-clinics')->name('zoom-clinics.')->group(function () {
            Route::get('/', [DashboardZoomClinicController::class, 'index'])->name('index');
            Route::get('/create', [DashboardZoomClinicController::class, 'create'])->name('create');
            Route::post('/', [DashboardZoomClinicController::class, 'store'])->name('store');
            Route::get('/registrations', [DashboardZoomClinicRegistrationController::class, 'index'])->name('registrations.index');
            Route::get('/registrations/{registration}', [DashboardZoomClinicRegistrationController::class, 'show'])->name('registrations.show');
            Route::get('/{clinic}/edit', [DashboardZoomClinicController::class, 'edit'])->name('edit');
            Route::put('/{clinic}', [DashboardZoomClinicController::class, 'update'])->name('update');
            Route::delete('/{clinic}', [DashboardZoomClinicController::class, 'destroy'])->name('destroy');
        });

        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [DashboardAdminController::class, 'overview'])->name('overview');
            Route::get('/inquiries', [DashboardAdminController::class, 'inquiries'])->name('inquiries.index');
            Route::get('/inquiries/{inquiry}', [DashboardAdminController::class, 'showInquiry'])->name('inquiries.show');
            Route::post('/inquiries/{inquiry}/reply', [DashboardAdminController::class, 'replyInquiry'])->name('inquiries.reply');
        });
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::get('/posts/{type}', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{type}/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts/{type}', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{type}/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{type}/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{type}/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{type}/{post}/thumbnail', [PostController::class, 'thumbnail'])->name('posts.thumbnail');
    Route::post('/posts/{type}/{post}/thumbnail', [PostController::class, 'uploadThumbnail'])->name('posts.thumbnail.upload');
    Route::delete('/posts/{type}/{post}/thumbnail', [PostController::class, 'removeThumbnail'])->name('posts.thumbnail.remove');
    Route::get('/categories/{type}', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/{type}', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{type}/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('/email-templates/{template}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
    Route::put('/email-templates/{template}', [EmailTemplateController::class, 'update'])->name('email-templates.update');
    Route::post('/email-templates/{template}/test', [EmailTemplateController::class, 'testSend'])->name('email-templates.test');
    Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
    Route::post('/inquiries/{inquiry}/reply', [AdminInquiryController::class, 'reply'])->name('inquiries.reply');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::get('/redirects', [RedirectController::class, 'index'])->name('redirects.index');
    Route::post('/redirects', [RedirectController::class, 'store'])->name('redirects.store');
    Route::delete('/redirects/{redirect}', [RedirectController::class, 'destroy'])->name('redirects.destroy');
    Route::get('/zoom-clinics', [AdminZoomClinicController::class, 'index'])->name('zoom-clinics.index');
    Route::get('/zoom-clinics/create', [AdminZoomClinicController::class, 'create'])->name('zoom-clinics.create');
    Route::post('/zoom-clinics', [AdminZoomClinicController::class, 'store'])->name('zoom-clinics.store');
    Route::get('/zoom-clinics/{clinic}/edit', [AdminZoomClinicController::class, 'edit'])->name('zoom-clinics.edit');
    Route::put('/zoom-clinics/{clinic}', [AdminZoomClinicController::class, 'update'])->name('zoom-clinics.update');
    Route::delete('/zoom-clinics/{clinic}', [AdminZoomClinicController::class, 'destroy'])->name('zoom-clinics.destroy');
    Route::get('/zoom-clinic-registrations', [AdminZoomClinicRegistrationController::class, 'index'])->name('zoom-clinic-registrations.index');
    Route::get('/zoom-clinic-registrations/{registration}', [AdminZoomClinicRegistrationController::class, 'show'])->name('zoom-clinic-registrations.show');
});

require __DIR__.'/auth.php';
