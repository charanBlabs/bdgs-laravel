<div class="auth-page">
  <main class="auth-main">
    <div class="auth-container">
      <div class="auth-card">
        <h1>Customer Login</h1>

        <div id="bdgsLoginBanner" class="bdgs-auth-banner bdgs-auth-banner--success" role="status" aria-live="polite"></div>

        <hr class="auth-divider" aria-hidden="true">

        <form id="bdgsLoginForm" class="bdgs-auth-form" novalidate>
          <div class="bdgs-auth-field" data-field="email">
            <label for="loginEmail">Email Address<span class="bdgs-auth-required" aria-hidden="true">*</span></label>
            <input
              type="email"
              id="loginEmail"
              name="email"
              autocomplete="email"
              inputmode="email"
              required
              aria-required="true"
              aria-describedby="loginEmailError"
            >
            <span id="loginEmailError" class="bdgs-auth-error" role="alert"></span>
          </div>

          <div class="bdgs-auth-field" data-field="password">
            <label for="loginPassword">Password<span class="bdgs-auth-required" aria-hidden="true">*</span></label>
            <input
              type="password"
              id="loginPassword"
              name="password"
              autocomplete="current-password"
              required
              aria-required="true"
              aria-describedby="loginPasswordError"
            >
            <span id="loginPasswordError" class="bdgs-auth-error" role="alert"></span>
          </div>

          <p class="bdgs-auth-forgot">
            Did you forget your password?
            <a href="/login/retrieval">Reset your password</a>
          </p>

          <button type="submit" class="bdgsownv2-btn-primary bdgs-auth-submit">Log In</button>
        </form>

        <p class="auth-alt-action">
          Not a registered customer?
          <a href="/signup">Create an account</a>
        </p>
      </div>
    </div>
  </main>
</div>
