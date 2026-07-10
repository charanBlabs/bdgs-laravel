<div class="auth-page auth-page--signup">
  <main class="auth-main">
    <div class="auth-container auth-container--wide">
      <div class="auth-card">
        <h1>Create Your Account</h1>
        <p class="auth-lead">Register as a BD Growth Suite customer to manage your Brilliant Directories projects and orders.</p>

        <div id="bdgsSignupBanner" class="bdgs-auth-banner bdgs-auth-banner--success" role="status" aria-live="polite"></div>

        <hr class="auth-divider" aria-hidden="true">

        <form id="bdgsSignupForm" class="bdgs-auth-form" novalidate>
          <div class="bdgs-auth-row bdgs-auth-row--2">
            <div class="bdgs-auth-field" data-field="firstName">
              <label for="signupFirstName">First Name<span class="bdgs-auth-required" aria-hidden="true">*</span></label>
              <input
                type="text"
                id="signupFirstName"
                name="first_name"
                autocomplete="given-name"
                required
                aria-required="true"
                aria-describedby="signupFirstNameError"
              >
              <span id="signupFirstNameError" class="bdgs-auth-error" role="alert"></span>
            </div>

            <div class="bdgs-auth-field" data-field="lastName">
              <label for="signupLastName">Last Name<span class="bdgs-auth-required" aria-hidden="true">*</span></label>
              <input
                type="text"
                id="signupLastName"
                name="last_name"
                autocomplete="family-name"
                required
                aria-required="true"
                aria-describedby="signupLastNameError"
              >
              <span id="signupLastNameError" class="bdgs-auth-error" role="alert"></span>
            </div>
          </div>

          <div class="bdgs-auth-field" data-field="email">
            <label for="signupEmail">Email Address<span class="bdgs-auth-required" aria-hidden="true">*</span></label>
            <input
              type="email"
              id="signupEmail"
              name="email"
              autocomplete="email"
              inputmode="email"
              required
              aria-required="true"
              aria-describedby="signupEmailError"
            >
            <span id="signupEmailError" class="bdgs-auth-error" role="alert"></span>
          </div>

          <div class="bdgs-auth-field" data-field="phone">
            <label for="signupPhone">Phone Number<span class="bdgs-auth-required" aria-hidden="true">*</span></label>
            <input
              type="tel"
              id="signupPhone"
              name="phone"
              autocomplete="tel"
              inputmode="tel"
              required
              aria-required="true"
              aria-describedby="signupPhoneError"
            >
            <span id="signupPhoneError" class="bdgs-auth-error" role="alert"></span>
          </div>

          <div class="bdgs-auth-field" data-field="password">
            <label for="signupPassword">Create Password<span class="bdgs-auth-required" aria-hidden="true">*</span></label>
            <input
              type="password"
              id="signupPassword"
              name="password"
              autocomplete="new-password"
              required
              aria-required="true"
              aria-describedby="signupPasswordRules signupPasswordError"
            >
            <div id="signupPasswordRules" class="bdgs-auth-pw-rules-panel" aria-hidden="true" aria-live="polite">
              <p class="bdgs-auth-pw-rules-title">Password must include:</p>
              <ul class="bdgs-auth-pw-rules">
                <li class="bdgs-auth-pw-rule" data-rule="length">
                  <span class="bdgs-auth-pw-rule-icon" aria-hidden="true">
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--tick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                  </span>
                  <span class="bdgs-auth-pw-rule-text">
                    <span class="bdgs-auth-pw-rule-text-full">At least 5 characters</span>
                    <span class="bdgs-auth-pw-rule-text-short" aria-hidden="true">5+ characters</span>
                  </span>
                </li>
                <li class="bdgs-auth-pw-rule" data-rule="special">
                  <span class="bdgs-auth-pw-rule-icon" aria-hidden="true">
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--tick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                  </span>
                  <span class="bdgs-auth-pw-rule-text">
                    <span class="bdgs-auth-pw-rule-text-full">At least one special character</span>
                    <span class="bdgs-auth-pw-rule-text-short" aria-hidden="true">Special char</span>
                  </span>
                </li>
                <li class="bdgs-auth-pw-rule" data-rule="upper">
                  <span class="bdgs-auth-pw-rule-icon" aria-hidden="true">
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--tick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                  </span>
                  <span class="bdgs-auth-pw-rule-text">
                    <span class="bdgs-auth-pw-rule-text-full">At least one capital letter</span>
                    <span class="bdgs-auth-pw-rule-text-short" aria-hidden="true">Capital letter</span>
                  </span>
                </li>
                <li class="bdgs-auth-pw-rule" data-rule="lower">
                  <span class="bdgs-auth-pw-rule-icon" aria-hidden="true">
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--tick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                  </span>
                  <span class="bdgs-auth-pw-rule-text">
                    <span class="bdgs-auth-pw-rule-text-full">At least one small letter</span>
                    <span class="bdgs-auth-pw-rule-text-short" aria-hidden="true">Small letter</span>
                  </span>
                </li>
                <li class="bdgs-auth-pw-rule" data-rule="number">
                  <span class="bdgs-auth-pw-rule-icon" aria-hidden="true">
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    <svg class="bdgs-auth-pw-icon bdgs-auth-pw-icon--tick" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                  </span>
                  <span class="bdgs-auth-pw-rule-text">
                    <span class="bdgs-auth-pw-rule-text-full">At least one number</span>
                    <span class="bdgs-auth-pw-rule-text-short" aria-hidden="true">Number</span>
                  </span>
                </li>
              </ul>
            </div>
            <span id="signupPasswordError" class="bdgs-auth-error" role="alert"></span>
          </div>

          <div class="bdgs-auth-field" data-field="confirmPassword">
            <label for="signupConfirmPassword">Confirm Password<span class="bdgs-auth-required" aria-hidden="true">*</span></label>
            <input
              type="password"
              id="signupConfirmPassword"
              name="password_confirmation"
              autocomplete="new-password"
              required
              aria-required="true"
              aria-describedby="signupConfirmPasswordError"
            >
            <span id="signupConfirmPasswordError" class="bdgs-auth-error" role="alert"></span>
          </div>

          <button type="submit" class="bdgsownv2-btn-primary bdgs-auth-submit">Create Account</button>
        </form>

        <p class="auth-alt-action">
          Already have an account?
          <a href="/login">Log in</a>
        </p>
      </div>
    </div>
  </main>
</div>
