@php
    $bdgsAuthUser = auth()->check()
        ? [
            'name' => auth()->user()->fullName(),
            'email' => auth()->user()->email,
            'avatarUrl' => auth()->user()->avatarUrl('thumb'),
            'role' => auth()->user()->primaryRoleLabel(),
        ]
        : null;
@endphp
<script type="application/json" id="bdgs-auth-user-data">{!! json_encode($bdgsAuthUser, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE) !!}</script>
<script>
window.bdgsAuthUser = JSON.parse(document.getElementById('bdgs-auth-user-data').textContent);
</script>
