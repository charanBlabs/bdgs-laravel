<script>
(function () {
  var modal = document.getElementById("bdgsInquiryModal");
  var form = document.getElementById("bdgsInquiryForm");
  var formWrap = document.getElementById("bdgsInquiryFormWrap");
  var thanks = document.getElementById("bdgsInquiryThanks");
  var errorEl = document.getElementById("bdgsInquiryError");
  var guardInput = document.getElementById("bdgsInquiryFormGuard");
  var submitBtn = document.getElementById("bdgsInquirySubmitBtn");
  var guardReadyAt = 0;

  if (!modal || !form) return;

  function csrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute("content") : "";
  }

  function showError(message) {
    if (!errorEl) return;
    errorEl.textContent = message;
    errorEl.hidden = !message;
  }

  function setSubmitting(isSubmitting) {
    if (!submitBtn) return;
    submitBtn.disabled = isSubmitting;
    submitBtn.textContent = isSubmitting ? "Sending…" : "Send My Inquiry →";
  }

  function refreshFormGuard() {
    return fetch("/inquiry/form-guard", {
      headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
      credentials: "same-origin",
    })
      .then(function (r) {
        if (!r.ok) throw new Error("guard");
        return r.json();
      })
      .then(function (data) {
        if (guardInput && data.form_guard) {
          guardInput.value = data.form_guard;
          guardReadyAt = Date.now();
        }
      })
      .catch(function () {
        showError("Could not open the secure form session. Please refresh the page and try again.");
      });
  }

  window.bdgsOpenInquiryModal = function () {
    modal.classList.add("active");
    formWrap.style.display = "block";
    thanks.classList.remove("active");
    showError("");
    setSubmitting(false);
    document.body.style.overflow = "hidden";
    refreshFormGuard();
  };

  window.bdgsCloseInquiryModal = function () {
    modal.classList.remove("active");
    document.body.style.overflow = "";
  };

  modal.addEventListener("click", function (e) {
    if (e.target === modal) bdgsCloseInquiryModal();
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    showError("");

    if (!guardInput || !guardInput.value) {
      showError("Secure form session is not ready yet. Please wait a moment and try again.");
      return;
    }

    if (guardReadyAt && Date.now() - guardReadyAt < 3000) {
      showError("Please take a moment to complete the form, then submit again.");
      return;
    }

    setSubmitting(true);

    var payload = {
      form_guard: guardInput.value,
      name: form.name.value.trim(),
      email: form.email.value.trim(),
      phone: form.phone.value.trim(),
      directory_url: form.directory_url.value.trim(),
      need: form.need.value,
      message: form.message.value.trim(),
      company_website: form.company_website ? form.company_website.value : "",
      fax_number: form.fax_number ? form.fax_number.value : "",
      source: "web",
    };

    fetch("/inquiry/submit", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken(),
        "X-Requested-With": "XMLHttpRequest",
      },
      credentials: "same-origin",
      body: JSON.stringify(payload),
    })
      .then(function (r) {
        return r.json().then(function (data) {
          return { ok: r.ok, status: r.status, data: data };
        });
      })
      .then(function (result) {
        if (result.ok && result.data && result.data.ok) {
          form.reset();
          if (guardInput) guardInput.value = "";
          formWrap.style.display = "none";
          thanks.classList.add("active");
          return;
        }

        var msg =
          (result.data && (result.data.message || (result.data.errors && Object.values(result.data.errors)[0][0]))) ||
          "Something went wrong. Please try again.";
        showError(typeof msg === "string" ? msg : "Something went wrong. Please try again.");
        if (result.status === 422 && result.data && result.data.message && result.data.message.indexOf("session expired") !== -1) {
          refreshFormGuard();
        }
      })
      .catch(function () {
        showError("Network error. Check your connection and try again.");
      })
      .finally(function () {
        setSubmitting(false);
      });
  });
})();
</script>
