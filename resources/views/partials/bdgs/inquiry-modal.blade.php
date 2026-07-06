<!-- Final CTA Inquiry Modal (front-end shell; backend automation to be wired later) -->
<div class="bdgsownv2-modal-overlay" id="bdgsInquiryModal">
  <div class="bdgsownv2-modal">
    <button class="bdgsownv2-modal-close" onclick="bdgsCloseInquiryModal()">×</button>
    <div id="bdgsInquiryFormWrap">
      <h3>Get Started with BDGS</h3>
      <p>Tell us about your directory. After submission, this will trigger email, WhatsApp, and SMS updates for you and our internal team once connected to automation.</p>
      <form class="bdgsownv2-modal-form" id="bdgsInquiryForm">
        <input type="text" name="name" placeholder="Your name" required>
        <input type="email" name="email" placeholder="Email address" required>
        <input type="tel" name="phone" placeholder="Phone / WhatsApp number" required>
        <input type="url" name="site" placeholder="Directory website URL (if any)">
        <select name="need" required>
          <option value="">What do you need help with?</option>
          <option>Setup & Launch</option>
          <option>Dedicated Developer</option>
          <option>AI / Automation</option>
          <option>Solutions / Tools / Themes</option>
          <option>Growth Plan</option>
          <option>Not sure yet</option>
        </select>
        <textarea name="message" placeholder="What are you trying to build or fix?"></textarea>
        <p class="bdgsownv2-modal-note">Automation note: this form UI is ready. Email, WhatsApp, and SMS delivery need backend/Zapier/Make/API wiring next.</p>
        <button type="submit" class="bdgsownv2-btn-primary" style="border:none;cursor:pointer">Send My Inquiry →</button>
      </form>
    </div>
    <div class="bdgsownv2-modal-thanks" id="bdgsInquiryThanks">
      <div class="check">✓</div>
      <h3>Thank you. We received it.</h3>
      <p>Your request is ready to route to the BDGS team. Once automation is connected, you and our team will receive email, WhatsApp, and SMS confirmations.</p>
      <button onclick="bdgsCloseInquiryModal()" class="bdgsownv2-btn-primary" style="border:none;cursor:pointer">Done</button>
    </div>
  </div>
</div>
