# Post-production checklist

Items that are **implemented in code** but need **live credentials / console setup** before they work on [bdgrowthsuite.com](https://bdgrowthsuite.com). Do not leave these blank in production `.env` if you rely on the feature.

---

## Marketing pixels — Zoom Clinic signup

**Status:** Code is live. Pixels fire **on successful registration submit** inside the Zoom modal (no thank-you page redirect).

| Network | Event | When |
|---------|--------|------|
| Google Analytics 4 | `gtag('event', 'zoom_clinic_signup', {…})` | After `POST /api/zoom-clinics/register` succeeds |
| Meta (Facebook) Pixel | `fbq('track', 'Lead', {…})` | Same |
| LinkedIn Insight Tag | `lintrk('track', { conversion_id })` | Same (needs conversion ID) |

### Production `.env` (required)

```ini
APP_ENV=production
# TRACKING_ENABLED defaults to true when APP_ENV=production; set false to disable all pixels.
# TRACKING_ENABLED=true

GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
FACEBOOK_PIXEL_ID=XXXXXXXXXXXXXXXX
LINKEDIN_PARTNER_ID=XXXXXXX
LINKEDIN_ZOOM_CLINIC_CONVERSION_ID=XXXXXXX
```

Then:

```bash
php artisan config:clear
php artisan config:cache
```

### Where to get each ID

1. **Google Analytics 4** — [analytics.google.com](https://analytics.google.com) → Admin → Data streams → Web → Measurement ID (`G-…`).
2. **Meta Pixel** — [Events Manager](https://business.facebook.com/events_manager) → Data sources → Pixel → Pixel ID. Confirm **Lead** is a standard event you use in ads.
3. **LinkedIn Partner ID** — [Campaign Manager](https://www.linkedin.com/campaignmanager/) → Analyze → Insight Tag → Partner ID.
4. **LinkedIn Zoom Clinic conversion ID** — Campaign Manager → Insight Tag → **Conversions** → create an **Insight Tag** conversion named e.g. “Zoom Clinic Signup” → copy Conversion ID into `LINKEDIN_ZOOM_CLINIC_CONVERSION_ID`.

### Verify after production deploy

1. Register for a clinic on production (use a test email).
2. GA4 → Admin → DebugView / Realtime → look for `zoom_clinic_signup`.
3. Meta Events Manager → Test events / recent activity → **Lead**.
4. LinkedIn Campaign Manager → conversion should increment for that conversion ID.
5. Browser DevTools → Network: `google-analytics.com` / `facebook.com/tr` / `linkedin.com` / `px.ads.linkedin.com` requests on submit success.

### Staging

Leave IDs empty on staging (default). To test:

```ini
TRACKING_ENABLED=true
GOOGLE_ANALYTICS_ID=G-…
# …other IDs
```

Prefer **test**/dev property IDs so staging does not pollute production ads reporting.

### Code touchpoints

| Piece | Path |
|-------|------|
| Env config | `config/services.php` → `tracking` |
| Base tags + `bdgsTrackZoomClinicSignup()` | `resources/views/partials/bdgs/tracking.blade.php` |
| Layout include | `resources/views/layouts/bdgs.blade.php` |
| Fire on success | `resources/views/partials/bdgs/zoom-clinic-scripts.blade.php` → `bdgsShowZoomBookingSuccess` |

---

## Other production secrets (existing)

Confirm these are also set on production (not related to pixels, but easy to miss):

| Variable | Purpose |
|----------|---------|
| `APP_KEY` | App encryption |
| `REVIEWS_SYNC_TOKEN` | Reviews sync API |
| `INQUIRY_AGENT_TOKEN` | Inquiry agent API |
| `MAIL_*` | Transactional email |
| `CAL_LINK` / Cal.com | Discovery Call embed |
