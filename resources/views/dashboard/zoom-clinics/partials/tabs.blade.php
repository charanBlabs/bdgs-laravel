@php($activeTab = $activeTab ?? 'clinics')
<div class="bdgs-sf__tabs">
  <a href="{{ route('dashboard.zoom-clinics.index') }}"
     @class(['bdgs-sf__tab', 'bdgs-sf__tab--active' => $activeTab === 'clinics'])>Clinics</a>
  <a href="{{ route('dashboard.zoom-clinics.registrations.index') }}"
     @class(['bdgs-sf__tab', 'bdgs-sf__tab--active' => $activeTab === 'registrations'])>Registrations</a>
</div>
