<div class="bdgsownv2-modal-overlay" id="bdgsZoomModal">
  <div class="bdgsownv2-modal">
    <button class="bdgsownv2-modal-close" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; top: 14px; right: 14px; padding: 0;" onclick="bdgsCloseZoomModal()">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
    <div id="bdgsZoomModalContent">
      <div id="bdgsZoomModalStep1">
        <h3>🟢 Join Free Zoom Clinics</h3>
        <p>Stuck on small things? Drop into our live Zoom session to get free help and learn Brilliant Directories.</p>
        <div style="background:var(--bdgs-bg);padding:16px;border-radius:8px;font-size:14px;border-left:3px solid var(--bdgs-coral);margin-bottom:16px;">
          <style>
.bdgs-tz-wrapper { position:relative; display:inline-flex; align-items:center; background-color:rgba(17, 24, 39, 0.05); border-radius:6px; padding:4px 10px; margin-left:4px; cursor:pointer; user-select:none; transition: background-color 0.2s; }
.bdgs-tz-wrapper:hover { background-color: rgba(17, 24, 39, 0.09) !important; }
.bdgs-custom-dropdown { display:none; position:absolute; top:calc(100% + 4px); left:0; min-width:100%; width:max-content; background:var(--bdgs-white); border:1px solid rgba(0,0,0,0.08); border-radius:8px; box-shadow:0 12px 28px -6px rgba(0,0,0,0.12), 0 8px 16px -8px rgba(0,0,0,0.08); z-index:1000; padding:6px; flex-direction:column; gap:2px; }
.bdgs-custom-dropdown.show { display:flex; animation: bdgsDropdownFade 0.2s ease; }
@keyframes bdgsDropdownFade { from { opacity:0; transform:translateY(-5px); } to { opacity:1; transform:translateY(0); } }
.bdgs-tz-option { padding:8px 12px; font-size:13px; color:var(--bdgs-dark); border-radius:4px; cursor:pointer; transition:background 0.2s, color 0.2s; font-weight: 500; }
.bdgs-tz-option:hover, .bdgs-tz-option.selected { background:rgba(231,77,86,0.08); color:var(--bdgs-coral); }
</style>

          <div style="margin-bottom:8px;">
            <strong style="color:var(--bdgs-dark)">Next session:</strong> <span id="bdgsZoomSessionDisplay">Loading...</span>
            <div style="margin-top: 2px;">
              <strong style="color:var(--bdgs-dark)">Timezone:</strong>
              
              <div class="bdgs-tz-wrapper" id="bdgsTzWrapper" onclick="document.getElementById('bdgsTzDropdown').classList.toggle('show')">
                <span id="bdgsZoomTimezoneText" style="font-size:13px; color:inherit; margin-right:8px; pointer-events:none; font-weight:600;">Eastern Time (EDT / EST) - New York</span>
                <svg width="10" height="6" xmlns="http://www.w3.org/2000/svg" style="pointer-events:none; flex-shrink:0;"><path d="M1 1l4 4 4-4" stroke="#6b7280" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <input type="hidden" id="bdgsZoomTimezone" value="America/New_York">
                
                <div class="bdgs-custom-dropdown" id="bdgsTzDropdown">
                  <div class="bdgs-tz-option selected" data-value="America/New_York" data-base="Eastern Time ({tz}) - New York" onclick="bdgsSelectTimezone(this, event)">Eastern Time - New York</div>
                  <div class="bdgs-tz-option" data-value="America/Chicago" data-base="Central Time ({tz}) - Chicago" onclick="bdgsSelectTimezone(this, event)">Central Time - Chicago</div>
                  <div class="bdgs-tz-option" data-value="America/Denver" data-base="Mountain Time ({tz}) - Denver" onclick="bdgsSelectTimezone(this, event)">Mountain Time - Denver</div>
                  <div class="bdgs-tz-option" data-value="America/Los_Angeles" data-base="Pacific Time ({tz}) - Los Angeles" onclick="bdgsSelectTimezone(this, event)">Pacific Time - Los Angeles</div>
                  <div class="bdgs-tz-option" data-value="America/Anchorage" data-base="Alaska Time ({tz}) - Anchorage" onclick="bdgsSelectTimezone(this, event)">Alaska Time - Anchorage</div>
                  <div class="bdgs-tz-option" data-value="Europe/London" data-base="British Time ({tz}) - London" onclick="bdgsSelectTimezone(this, event)">British Time - London</div>

                  <div class="bdgs-tz-option" data-value="Asia/Kolkata" data-base="Indian Standard Time ({tz}) - New Delhi" onclick="bdgsSelectTimezone(this, event)">Indian Standard Time - New Delhi</div>
                </div>
              </div>
            </div>
          </div>
          <div style="margin-bottom:2px;">
            <strong style="color:var(--bdgs-dark)">Agenda:</strong> <span data-bdgs-zoom-agenda>Live Website Reviews &amp; Open Q&amp;A</span>
          </div>
          <strong style="color:var(--bdgs-dark)">Format:</strong> 60-min open Q&amp;A with our devs<br>
          <strong style="color:var(--bdgs-dark)">Cost:</strong> Free, forever
        </div>

        <p style="font-size:12px;color:var(--bdgs-text-muted);margin-bottom:24px;">Strategy questions? Those need our Founder Concierge or Express Setup. Everything else - come on in.</p>
        <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
          <button type="button" onclick="bdgsShowZoomForm()" class="bdgsownv2-btn-primary" style="width:100%;padding:14px 20px;font-size:15px;text-align:center;">Register Now</button>
          @unless($hideZoomAllLink ?? false)
          <a href="/zoom-clinics" style="font-size:13px;color:var(--bdgs-coral);font-weight:600;text-decoration:none;">View all upcoming clinics &rarr;</a>
          @endunless
        </div>
      </div>

      <form id="bdgsZoomBookingForm" onsubmit="event.preventDefault(); bdgsProcessZoomBooking();" style="display:none;">
        <div style="margin-bottom:28px;">
          <h3 class="bdgsownv2-section-title" style="margin:0 0 20px;padding-right:32px;font-size:var(--fs-h3)" data-bdgs-zoom-agenda>Live Website Reviews &amp; Open Q&amp;A</h3>
          <input type="hidden" id="bdgsZoomClinicId" name="clinic_id" value="">
          
          <div style="font-size:15px; color:var(--bdgs-dark); margin-bottom:14px; display:flex; align-items:center;">
            <strong style="font-weight:700; margin-right:6px;">Session:</strong> <span id="bdgsZoomSessionDisplayForm">Loading...</span>
          </div>
          
          <div style="font-size:15px; color:var(--bdgs-dark); margin-bottom:20px; display:flex; align-items:center;">
            <strong style="font-weight:700; margin-right:6px;">Timezone:</strong>
            <div class="bdgs-tz-wrapper" id="bdgsTzWrapperForm" onclick="document.getElementById('bdgsTzDropdownForm').classList.toggle('show')" style="background-color:rgba(17, 24, 39, 0.04); margin-left:0; padding:6px 12px; border-radius:6px;">
              <span id="bdgsZoomTimezoneTextForm" style="font-size:13px; color:inherit; margin-right:8px; pointer-events:none; font-weight:600;">Eastern Time (EDT / EST) - New York</span>
              <svg width="10" height="6" xmlns="http://www.w3.org/2000/svg" style="pointer-events:none; flex-shrink:0;"><path d="M1 1l4 4 4-4" stroke="#6b7280" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
              <input type="hidden" id="bdgsZoomTimezoneForm" value="America/New_York">
              <div class="bdgs-custom-dropdown" id="bdgsTzDropdownForm">
                <div class="bdgs-tz-option selected" data-value="America/New_York" data-base="Eastern Time ({tz}) - New York" onclick="bdgsSelectTimezone(this, event, 'Form')">Eastern Time - New York</div>
                <div class="bdgs-tz-option" data-value="America/Chicago" data-base="Central Time ({tz}) - Chicago" onclick="bdgsSelectTimezone(this, event, 'Form')">Central Time - Chicago</div>
                <div class="bdgs-tz-option" data-value="America/Denver" data-base="Mountain Time ({tz}) - Denver" onclick="bdgsSelectTimezone(this, event, 'Form')">Mountain Time - Denver</div>
                <div class="bdgs-tz-option" data-value="America/Los_Angeles" data-base="Pacific Time ({tz}) - Los Angeles" onclick="bdgsSelectTimezone(this, event, 'Form')">Pacific Time - Los Angeles</div>
                <div class="bdgs-tz-option" data-value="America/Anchorage" data-base="Alaska Time ({tz}) - Anchorage" onclick="bdgsSelectTimezone(this, event, 'Form')">Alaska Time - Anchorage</div>
                <div class="bdgs-tz-option" data-value="Europe/London" data-base="British Time ({tz}) - London" onclick="bdgsSelectTimezone(this, event, 'Form')">British Time - London</div>

                <div class="bdgs-tz-option" data-value="Asia/Kolkata" data-base="Indian Standard Time ({tz}) - New Delhi" onclick="bdgsSelectTimezone(this, event, 'Form')">Indian Standard Time - New Delhi</div>
              </div>
            </div>
          </div>
          
          <p style="font-size:14px; color:var(--bdgs-text-muted); line-height:1.6; margin:0;">Stuck on small things? Drop into our live Zoom session to get free help and learn Brilliant Directories.</p>
        </div>

        <h4 style="margin:0 0 6px; font-size:16px; font-weight:700; color:var(--bdgs-dark); letter-spacing:-0.2px;">Your details please:</h4>
        <p style="font-size:13px; color:var(--bdgs-text-muted); margin-bottom:16px;">Please enter your name and email to receive the calendar invite.</p>
        
        <div class="bdgsownv2-modal-form" style="margin-top:0; margin-bottom:24px;">
          <input type="text" id="bdgsZoomName" name="name" placeholder="Your Name" required style="border-radius:8px; border:1px solid rgba(0,0,0,0.08); padding:12px 16px; width:100%; margin-bottom:12px; font-size:15px; outline:none; transition:border-color 0.2s, box-shadow 0.2s;">
          <input type="email" id="bdgsZoomEmail" name="email" placeholder="Your Email" required style="border-radius:8px; border:1px solid rgba(0,0,0,0.08); padding:12px 16px; width:100%; font-size:15px; outline:none; transition:border-color 0.2s, box-shadow 0.2s;">
        </div>
        
        <button type="submit" id="bdgsZoomScheduleBtn" class="bdgsownv2-btn-primary" style="width:100%;padding:14px 20px;font-size:15px;text-align:center;border-radius:8px;font-weight:600;">Complete Registration</button>
      </form>
    </div>
    <div id="bdgsZoomModalSuccess" style="display:none;text-align:center;padding:24px 20px;">
      <div style="font-size:64px;margin-bottom:20px;display:inline-block;text-shadow:0 10px 30px rgba(231,77,86,0.2);">🎉</div>
      <h3 class="bdgsownv2-section-title" style="color:var(--bdgs-coral);margin-bottom:20px;font-size:var(--fs-h3)">Registration Complete!</h3>

      <div style="text-align:left;margin:0 auto 24px;padding:14px 16px;background:var(--bdgs-bg);border-radius:10px;border-left:3px solid var(--bdgs-coral);">
        <p style="font-size:14px;font-weight:700;color:var(--bdgs-dark);margin:0 0 10px;line-height:1.35;" data-bdgs-zoom-agenda>Live Website Reviews &amp; Open Q&amp;A</p>
        <p style="font-size:13px;color:var(--bdgs-text-muted);margin:0 0 6px;line-height:1.5;"><strong style="font-weight:600;color:var(--bdgs-dark);">Session:</strong> <span id="bdgsZoomSuccessWhen"></span></p>
        <p style="font-size:13px;color:var(--bdgs-text-muted);margin:0;line-height:1.5;"><strong style="font-weight:600;color:var(--bdgs-dark);">Timezone:</strong> <span id="bdgsZoomSuccessTz"></span></p>
      </div>

      <a id="bdgsGoogleCalLink" target="_blank" rel="noopener noreferrer" href="#" class="bdgsownv2-btn-primary" style="width:100%;padding:14px 20px;font-size:15px;text-decoration:none;display:flex;justify-content:center;align-items:center;gap:8px;border-radius:8px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        Add to Google Calendar
      </a>
    </div>
  </div>
</div>
