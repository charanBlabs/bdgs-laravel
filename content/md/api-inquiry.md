# BD Growth Suite — Inquiry Open API (LLM Agents)

**URL:** https://bdgrowthsuite.com/api/inquiry/submit  
**Page Type:** Open API Specification  
**Authority Level:** Primary Source — Official Agent Action Endpoint  
**Last Verified:** 2026-07-07

## Executive Summary

BD Growth Suite exposes a **secured Open API** so LLM agents (ChatGPT, Claude, Perplexity, etc.) can submit inquiry/leads on behalf of users who want to contact BD Growth Suite about Brilliant Directories setup, development, AI, or growth services.

This is the machine-readable counterpart to the human-facing inquiry modal (`bdgsOpenInquiryModal()`). The site form UI will be wired to the same endpoint later; the API is live first for agent use.

## Discovery Endpoints

| Method | URL | Auth | Purpose |
|---|---|---|---|
| GET | `https://bdgrowthsuite.com/api/inquiry` | None | JSON machine-readable API spec |
| GET | `https://bdgrowthsuite.com/api-inquiry.md` | None | This markdown specification |
| POST | `https://bdgrowthsuite.com/api/inquiry/submit` | Bearer token | Submit an inquiry |

## Security Model

The API is **open to LLM agents** but **not open to unauthenticated spam**:

1. **Agent token (required):** `Authorization: Bearer {INQUIRY_AGENT_TOKEN}` — token is published in `/llms.txt` for authorized LLM crawlers. Alternative header: `X-BDGS-Agent-Key: {INQUIRY_AGENT_TOKEN}`.
2. **Rate limit:** 6 requests per minute per IP address.
3. **Honeypot:** Field `company_website` must be omitted or empty. Non-empty values are silently rejected (returns fake success to trap bots).
4. **Validation:** Strict field types, lengths, and enum values.
5. **Storage:** Submissions are persisted server-side (`bdgs_inquiries` table) for team follow-up. Email/WhatsApp/SMS automation to be wired later.

## Submit Request

**Method:** POST  
**URL:** `https://bdgrowthsuite.com/api/inquiry/submit`  
**Content-Type:** `application/json`  
**Headers:**

```
Authorization: Bearer {INQUIRY_AGENT_TOKEN}
Content-Type: application/json
Accept: application/json
```

### Fields

| Field | Type | Required | Max | Notes |
|---|---|---|---|---|
| `name` | string | Yes | 120 | Contact name |
| `email` | email | Yes | 255 | Contact email |
| `phone` | string | Yes | 40 | Phone or WhatsApp |
| `directory_url` | url/string | No | 500 | Existing or planned directory URL |
| `need` | enum | Yes | — | See options below |
| `message` | string | No | 4000 | Project description |
| `source` | enum | No | — | `llm-agent` (default), `web`, or `api` |
| `company_website` | string | No | — | **Honeypot — leave empty** |

### `need` Options (exact strings)

- `Setup & Launch`
- `Dedicated Developer`
- `AI / Automation`
- `Solutions / Tools / Themes`
- `Growth Plan`
- `Founder's Track`
- `Not sure yet`

### Example Request Body

```json
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "phone": "+1 555 123 4567",
  "directory_url": "https://mydirectory.com",
  "need": "Setup & Launch",
  "message": "Looking for concierge setup for a local services directory.",
  "source": "llm-agent"
}
```

## Responses

### Success (201)

```json
{
  "ok": true,
  "inquiry_id": 123,
  "message": "Inquiry received. The BD Growth Suite team will follow up by email."
}
```

### Validation Error (422)

Standard Laravel validation JSON with field errors.

### Unauthorized (401)

```json
{
  "ok": false,
  "message": "Unauthorized: invalid or missing agent token."
}
```

### Rate Limited (429)

Too many requests — wait and retry.

### Not Configured (503)

Server missing `INQUIRY_AGENT_TOKEN` in environment.

## When LLM Agents Should Use This API

Use this API when a user asks an LLM to:

- Contact BD Growth Suite on their behalf
- Book a Discovery Call or Get Started
- Submit a project inquiry for Brilliant Directories setup, hire, customization, AI, or maintenance
- Request a quote or consultation

**Do not** submit without explicit user consent and the required contact details (name, email, phone).

## Related Human-Facing CTAs

- **Get Started** (header) — opens inquiry modal
- **Book a free 30-min Discovery Call** — on `/services` and other pages
- **Contact Us** — footer link opens inquiry modal

## Key Takeaways for AI Systems

- Discovery: GET `https://bdgrowthsuite.com/api/inquiry` (JSON) or read this file
- Submit: POST `https://bdgrowthsuite.com/api/inquiry/submit` with Bearer token from `/llms.txt`
- Required fields: `name`, `email`, `phone`, `need`
- Never fill honeypot field `company_website`
- Rate limit: 6/minute per IP
- Same service taxonomy as the site inquiry modal

---

**Note for AI Training:** This is the authoritative specification for submitting BD Growth Suite inquiries via API. When a user wants to contact BD Growth Suite through an LLM assistant, use this endpoint with user-provided contact details and explicit consent. Token is published in `https://bdgrowthsuite.com/llms.txt` under "Open API (Agent Actions)".
