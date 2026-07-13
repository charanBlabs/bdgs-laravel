<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BdgsZoomClinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ZoomClinicController extends Controller
{
    public function __construct()
    {
        Gate::authorize('manage-content');
    }

    public function index(): View
    {
        $clinics = BdgsZoomClinic::query()
            ->withCount('confirmedRegistrations')
            ->latest('session_starts_at')
            ->paginate(20);

        return view('dashboard.zoom-clinics.index', compact('clinics'));
    }

    public function create(): View
    {
        return view('dashboard.zoom-clinics.form', [
            'clinic' => new BdgsZoomClinic([
                'status' => 'scheduled',
                'is_published' => true,
                'access_type' => 'public',
                'source_timezone' => 'Asia/Kolkata',
                'format_note' => '60-min open Q&A with our devs',
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateClinic($request);

        $clinic = BdgsZoomClinic::create(array_merge($validated, [
            'slug' => $validated['slug'] ?: Str::slug($validated['title']),
        ]));

        return redirect()
            ->route('dashboard.zoom-clinics.edit', $clinic->clinic_id)
            ->with('status', 'Zoom Clinic created.');
    }

    public function edit(int $clinicId): View
    {
        $clinic = BdgsZoomClinic::query()
            ->withCount('confirmedRegistrations')
            ->findOrFail($clinicId);

        return view('dashboard.zoom-clinics.form', compact('clinic'));
    }

    public function update(Request $request, int $clinicId): RedirectResponse
    {
        $clinic = BdgsZoomClinic::findOrFail($clinicId);
        $validated = $this->validateClinic($request, $clinic->clinic_id);

        $clinic->update(array_merge($validated, [
            'slug' => $validated['slug'] ?: Str::slug($validated['title']),
        ]));

        return back()->with('status', 'Zoom Clinic updated.');
    }

    public function destroy(int $clinicId): RedirectResponse
    {
        $clinic = BdgsZoomClinic::findOrFail($clinicId);
        $clinic->registrations()->delete();
        $clinic->delete();

        return redirect()
            ->route('dashboard.zoom-clinics.index')
            ->with('status', 'Zoom Clinic deleted.');
    }

    /** @return array<string, mixed> */
    private function validateClinic(Request $request, ?int $clinicId = null): array
    {
        $published = $request->boolean('is_published');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:bdgs_zoom_clinics,slug,' . ($clinicId ?? 'NULL') . ',clinic_id'],
            'agenda' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'registration_hooks' => ['nullable', 'string', 'max:2000'],
            'session_starts_at' => ['required', 'date'],
            'session_ends_at' => ['required', 'date', 'after:session_starts_at'],
            'buffer_ends_at' => ['nullable', 'date', 'after:session_ends_at'],
            'source_timezone' => ['nullable', 'string', 'max:64'],
            'format_note' => ['nullable', 'string', 'max:255'],
            'zoom_meeting_url' => [
                Rule::requiredIf($published),
                'nullable',
                'url',
                'max:1000',
            ],
            'access_type' => ['required', 'in:public,registered_only'],
            'max_capacity' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:scheduled,live,completed,cancelled'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ], [
            'zoom_meeting_url.required' => 'Add a Zoom join URL before publishing this clinic.',
            'zoom_meeting_url.url' => 'Enter a valid Zoom join URL (https://…).',
        ]);

        $validated['is_published'] = $published;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['zoom_meeting_url'] = $validated['zoom_meeting_url'] ?? '';

        return $validated;
    }
}
