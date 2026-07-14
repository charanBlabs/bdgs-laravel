@php
  $row1 = [
    ['src' => '/images/clients/LogoP1/1.png', 'alt' => 'Client directory logo'],
    ['src' => '/images/clients/LogoP1/9.png', 'alt' => 'Client directory logo'],
    ['src' => '/images/clients/LogoP1/8.png', 'alt' => 'Client directory logo'],
    ['src' => '/images/clients/LogoP1/3.png', 'alt' => 'Client directory logo'],
    ['src' => '/images/clients/CCRG-LOGO-edit1.png', 'alt' => 'CCRG directory logo'],
    ['src' => '/images/clients/LogoP2/supplyseys.png', 'alt' => 'Supplyseys logo'],
    ['src' => '/images/clients/LogoP1/4.png', 'alt' => 'Client directory logo'],
    ['src' => '/images/clients/LogoP2/hotelsjuction.png', 'alt' => 'Hotels Junction logo'],
  ];
  $row2 = [
    ['src' => '/images/clients/LogoP2/yep.png', 'alt' => 'Yep directory logo'],
    ['src' => '/images/clients/LogoP1/7.png', 'alt' => 'Client directory logo'],
    ['src' => '/images/clients/LogoP2/isostylist.png', 'alt' => 'Iso Stylist logo'],
    ['src' => '/images/clients/LogoP1/9_1.png', 'alt' => 'Client directory logo'],
    ['src' => '/images/clients/LogoP1/5.png', 'alt' => 'Client directory logo'],
    ['src' => '/images/clients/LogoP2/localbulls.png', 'alt' => 'Local Bulls logo'],
    ['src' => '/images/clients/LogoP2/insureblack.png', 'alt' => 'Insure Black logo'],
    ['src' => '/images/clients/LogoP1/2.png', 'alt' => 'Client directory logo'],
  ];
  $secondRowMod = ! empty($secondRowFlush) ? 'bdgsownv1-marquee-container--flush' : 'bdgsownv1-marquee-container--spaced';
@endphp
{{-- Client logos marquee: named alts on first track; decorative duplicate track --}}
<div class="bdgsownv1-marquee-container">
  <div class="bdgsownv1-marquee-track">
    <div class="bdgsownv1-marquee-content">
      @foreach ($row1 as $logo)
        <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="{{ $logo['src'] }}" alt="{{ $logo['alt'] }}"></div>
      @endforeach
    </div>
    <div class="bdgsownv1-marquee-content" aria-hidden="true">
      @foreach ($row1 as $logo)
        <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="{{ $logo['src'] }}" alt=""></div>
      @endforeach
    </div>
  </div>
</div>

<div class="bdgsownv1-marquee-container {{ $secondRowMod }}">
  <div class="bdgsownv1-marquee-track reverse">
    <div class="bdgsownv1-marquee-content">
      @foreach ($row2 as $logo)
        <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="{{ $logo['src'] }}" alt="{{ $logo['alt'] }}"></div>
      @endforeach
    </div>
    <div class="bdgsownv1-marquee-content" aria-hidden="true">
      @foreach ($row2 as $logo)
        <div class="bdgsownv1-logo-item"><img width="160" height="64" loading="lazy" src="{{ $logo['src'] }}" alt=""></div>
      @endforeach
    </div>
  </div>
</div>
