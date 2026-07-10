<!-- Final CTA Inquiry Modal -->
<div class="bdgsownv2-modal-overlay" id="bdgsInquiryModal">
  <div class="bdgsownv2-modal">
    <button type="button" class="bdgsownv2-modal-close" onclick="bdgsCloseInquiryModal()" aria-label="Close inquiry form">&times;</button>
    <div id="bdgsInquiryFormWrap">
      <h3>Get Started with BDGS</h3>
      <p>Tell us about your directory. We will follow up by email to schedule next steps.</p>
      <p id="bdgsInquiryError" class="bdgsownv2-modal-error" role="alert" hidden></p>
      <form class="bdgsownv2-modal-form" id="bdgsInquiryForm" novalidate>
        <input type="hidden" name="form_guard" id="bdgsInquiryFormGuard" value="">
        <div class="bdgs-hp" aria-hidden="true">
          <label for="bdgsInquiryCompanyWebsite">Company website</label>
          <input type="text" name="company_website" id="bdgsInquiryCompanyWebsite" tabindex="-1" autocomplete="off">
          <label for="bdgsInquiryFax">Fax</label>
          <input type="text" name="fax_number" id="bdgsInquiryFax" tabindex="-1" autocomplete="off">
        </div>
        <label class="bdgs-sr-only" for="bdgsInquiryName">Your name</label>
        <input type="text" name="name" id="bdgsInquiryName" placeholder="Your name" required autocomplete="name">
        <label class="bdgs-sr-only" for="bdgsInquiryEmail">Email address</label>
        <input type="email" name="email" id="bdgsInquiryEmail" placeholder="Email address" required autocomplete="email">
        <label class="bdgs-sr-only" for="bdgsInquiryPhone">Phone / WhatsApp number</label>
        <input type="tel" name="phone" id="bdgsInquiryPhone" placeholder="Phone / WhatsApp number" required autocomplete="tel">
        <label class="bdgs-sr-only" for="bdgsInquirySite">Directory website URL</label>
        <input type="url" name="directory_url" id="bdgsInquirySite" placeholder="Directory website URL (if any)" autocomplete="url">
        <label class="bdgs-sr-only" for="bdgsInquiryNeed">What do you need help with?</label>
        <div class="bdgsownv2-select-wrap">
          <select name="need" id="bdgsInquiryNeed" required>
            <option value="">What do you need help with?</option>
            @foreach (\App\Services\InquiryService::NEED_OPTIONS as $option)
            <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
          </select>
        </div>
        <label class="bdgs-sr-only" for="bdgsInquiryMessage">Project message</label>
        <textarea name="message" id="bdgsInquiryMessage" placeholder="What are you trying to build or fix?"></textarea>
        <button type="submit" class="bdgsownv2-btn-primary bdgs-inquiry-submit" id="bdgsInquirySubmitBtn">Send My Inquiry &rarr;</button>
      </form>
    </div>
    <div class="bdgsownv2-modal-thanks" id="bdgsInquiryThanks">
      <div class="check">&#10003;</div>
      <h3>Thank you. We received it.</h3>
      <p>The BD Growth Suite team will follow up by email shortly.</p>
      <button type="button" onclick="bdgsCloseInquiryModal()" class="bdgsownv2-btn-primary">Done</button>
    </div>
  </div>
</div>
