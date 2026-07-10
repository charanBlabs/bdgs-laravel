/**
 * Client-side validation for BDGS login + signup (no backend yet).
 */
(function () {
  'use strict';

  var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var NAME_RE = /^[A-Za-z][A-Za-z\s'.-]{1,}$/;
  var PHONE_RE = /^[\d\s()+\-.]{10,}$/;

  function trim(value) {
    return (value || '').trim();
  }

  function setFieldError(fieldWrap, message) {
    var input = fieldWrap.querySelector('input, select, textarea');
    var errorEl = fieldWrap.querySelector('.bdgs-auth-error');
    if (!input || !errorEl) return;
    if (message) {
      input.classList.add('bdgs-auth-input--error');
      input.setAttribute('aria-invalid', 'true');
      errorEl.textContent = message;
    } else {
      input.classList.remove('bdgs-auth-input--error');
      input.setAttribute('aria-invalid', 'false');
      errorEl.textContent = '';
    }
  }

  function clearFormErrors(form) {
    form.querySelectorAll('.bdgs-auth-field').forEach(function (wrap) {
      setFieldError(wrap, '');
    });
  }

  function focusFirstError(form) {
    var firstInvalid = form.querySelector('.bdgs-auth-input--error');
    if (firstInvalid) firstInvalid.focus();
  }

  function validateEmail(value) {
    if (!value) return 'Email address is required.';
    if (!EMAIL_RE.test(value)) return 'Enter a valid email address.';
    return '';
  }

  function validateLoginPassword(value) {
    if (!value) return 'Password is required.';
    return '';
  }

  function validateName(value, label) {
    if (!value) return label + ' is required.';
    if (value.length < 2) return label + ' must be at least 2 characters.';
    if (!NAME_RE.test(value)) return label + ' may only contain letters, spaces, hyphens, and apostrophes.';
    return '';
  }

  function validatePhone(value) {
    if (!value) return 'Phone number is required.';
    var digits = value.replace(/\D/g, '');
    if (digits.length < 10) return 'Enter a valid phone number (at least 10 digits).';
    if (!PHONE_RE.test(value)) return 'Enter a valid phone number.';
    return '';
  }

  function passwordRules(password) {
    return {
      length: password.length >= 5,
      upper: /[A-Z]/.test(password),
      lower: /[a-z]/.test(password),
      number: /\d/.test(password),
      special: /[^A-Za-z0-9]/.test(password)
    };
  }

  function validateSignupPassword(value) {
    if (!value) return 'Password is required.';
    var rules = passwordRules(value);
    if (!rules.length) return 'Password must be at least 5 characters.';
    if (!rules.upper) return 'Password must include at least one capital letter.';
    if (!rules.lower) return 'Password must include at least one small letter.';
    if (!rules.number) return 'Password must include at least one number.';
    if (!rules.special) return 'Password must include at least one special character.';
    return '';
  }

  function isMobileAuth() {
    return window.matchMedia('(max-width: 767px)').matches;
  }

  function setPasswordRulesVisible(form, visible) {
    var panel = form.querySelector('.bdgs-auth-pw-rules-panel');
    if (!panel) return;
    panel.classList.toggle('bdgs-auth-pw-rules-panel--visible', visible);
    panel.setAttribute('aria-hidden', visible ? 'false' : 'true');
  }

  function updatePasswordRuleList(form, password) {
    var panel = form.querySelector('.bdgs-auth-pw-rules-panel');
    if (!panel) return;

    var hasInput = password.length > 0;
    setPasswordRulesVisible(form, hasInput);

    if (!hasInput) return;

    var rules = passwordRules(password);
    panel.querySelectorAll('[data-rule]').forEach(function (item) {
      var key = item.getAttribute('data-rule');
      var met = !!rules[key];
      item.classList.toggle('bdgs-auth-pw-rule--met', met);
      item.classList.toggle('bdgs-auth-pw-rule--unmet', !met);
      item.classList.toggle('bdgs-auth-pw-rule--collapsed', isMobileAuth() && met);
    });

    var visibleRules = panel.querySelectorAll('[data-rule]:not(.bdgs-auth-pw-rule--collapsed)');
    var allMet = visibleRules.length === 0 && password.length > 0;
    panel.classList.toggle('bdgs-auth-pw-rules-panel--all-met', allMet);
  }

  function showPlaceholderSuccess(form, bannerId, message) {
    var banner = document.getElementById(bannerId);
    if (!banner) return;
    banner.textContent = message;
    banner.classList.add('bdgs-auth-banner--visible');
    form.classList.add('bdgs-auth-form--hidden');
    var alt = form.closest('.auth-card');
    if (alt) {
      var altAction = alt.querySelector('.auth-alt-action');
      if (altAction) altAction.style.display = 'none';
    }
  }

  function wireLoginForm() {
    var form = document.getElementById('bdgsLoginForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      clearFormErrors(form);

      var emailWrap = form.querySelector('[data-field="email"]');
      var passwordWrap = form.querySelector('[data-field="password"]');
      var email = trim(form.querySelector('#loginEmail').value);
      var password = form.querySelector('#loginPassword').value;

      var emailError = validateEmail(email);
      var passwordError = validateLoginPassword(password);

      setFieldError(emailWrap, emailError);
      setFieldError(passwordWrap, passwordError);

      if (emailError || passwordError) {
        focusFirstError(form);
        return;
      }

      showPlaceholderSuccess(
        form,
        'bdgsLoginBanner',
        'Your credentials look valid. Customer login will connect to the account system once the database is ready.'
      );
    });

    form.querySelectorAll('input').forEach(function (input) {
      input.addEventListener('input', function () {
        var wrap = input.closest('.bdgs-auth-field');
        if (wrap) setFieldError(wrap, '');
      });
    });
  }

  function wireSignupForm() {
    var form = document.getElementById('bdgsSignupForm');
    if (!form) return;

    var passwordInput = form.querySelector('#signupPassword');
    if (passwordInput) {
      passwordInput.addEventListener('input', function () {
        updatePasswordRuleList(form, passwordInput.value);
        var wrap = passwordInput.closest('.bdgs-auth-field');
        if (wrap) setFieldError(wrap, '');
      });

      passwordInput.addEventListener('blur', function () {
        if (!passwordInput.value.length) {
          setPasswordRulesVisible(form, false);
        }
      });

      window.addEventListener('resize', function () {
        if (passwordInput.value.length) {
          updatePasswordRuleList(form, passwordInput.value);
        }
      });
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      clearFormErrors(form);

      var firstName = trim(form.querySelector('#signupFirstName').value);
      var lastName = trim(form.querySelector('#signupLastName').value);
      var email = trim(form.querySelector('#signupEmail').value);
      var phone = trim(form.querySelector('#signupPhone').value);
      var password = passwordInput ? passwordInput.value : '';
      var confirmPassword = form.querySelector('#signupConfirmPassword').value;

      var errors = {
        firstName: validateName(firstName, 'First name'),
        lastName: validateName(lastName, 'Last name'),
        email: validateEmail(email),
        phone: validatePhone(phone),
        password: validateSignupPassword(password),
        confirmPassword: !confirmPassword
          ? 'Please confirm your password.'
          : (confirmPassword !== password ? 'Passwords do not match.' : '')
      };

      Object.keys(errors).forEach(function (key) {
        var wrap = form.querySelector('[data-field="' + key + '"]');
        if (wrap) setFieldError(wrap, errors[key]);
      });

      if (password.length > 0) {
        updatePasswordRuleList(form, password);
      }

      if (Object.keys(errors).some(function (key) { return errors[key]; })) {
        focusFirstError(form);
        return;
      }

      showPlaceholderSuccess(
        form,
        'bdgsSignupBanner',
        'Your account details look good. Registration will connect to the database once customer accounts are set up.'
      );
    });

    form.querySelectorAll('input').forEach(function (input) {
      input.addEventListener('input', function () {
        var wrap = input.closest('.bdgs-auth-field');
        if (wrap) setFieldError(wrap, '');
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      wireLoginForm();
      wireSignupForm();
    });
  } else {
    wireLoginForm();
    wireSignupForm();
  }
})();
