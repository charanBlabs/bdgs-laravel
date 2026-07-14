<?php

namespace App\Support;

/**
 * Shared BD Growth Suite email chrome — logo, coral/purple brand, DM Sans typography.
 * Applied at render time so every template matches the site theme.
 */
class EmailTheme
{
    public const LOGO_URL = '/images/brand/logo.png';

    public const MARKER = '<!-- bdgs-email-theme -->';

    /** Site body font stack (bdgs-shell.css). */
    public const FONT_STACK = "'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif";

    /** Theme type scale (email-safe fixed sizes from --fs-* tokens). */
    public const FS_BODY = '15px';

    public const FS_SMALL = '13px';

    public const FS_H1 = '24px';

    public const FS_H2 = '20px';

    public const FS_EYEBROW = '12px';

    public static function logoUrl(): string
    {
        $configured = (string) config('mail.brand.logo_url', self::LOGO_URL);
        if ($configured !== '' && preg_match('#^https?://#i', $configured)) {
            return $configured;
        }

        $path = $configured !== '' ? $configured : self::LOGO_URL;

        return rtrim(self::siteUrl(), '/').'/'.ltrim($path, '/');
    }

    public static function siteUrl(): string
    {
        return rtrim((string) config('app.url'), '/') ?: 'https://bdgrowthsuite.com';
    }

    public static function fontStack(): string
    {
        return (string) config('mail.brand.font_family', self::FONT_STACK);
    }

    /**
     * Wrap inner HTML in the branded shell unless already wrapped.
     */
    public static function wrap(string $innerHtml, array $variables = []): string
    {
        if (str_contains($innerHtml, self::MARKER) || str_contains($innerHtml, 'bdgs-email-shell')) {
            return $innerHtml;
        }

        $font = self::fontStack();
        $content = self::extractBody($innerHtml);
        $content = self::restyleLegacyAccents($content);
        $content = self::restyleLegacyFonts($content, $font);
        $siteName = e((string) ($variables['site_name'] ?? config('app.name', 'BD Growth Suite')));
        $logo = self::logoUrl();
        $siteUrl = self::siteUrl();
        $year = date('Y');
        $marker = self::MARKER;
        $fsBody = self::FS_BODY;
        $fsSmall = self::FS_SMALL;

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<title>{$siteName}</title>
{$marker}
<!-- DM Sans — supported in Apple Mail / iOS / many clients; Gmail falls back to Arial -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
<style type="text/css">
  body, table, td, a, p, h1, h2, h3, h4, span, div {
    font-family: {$font} !important;
  }
</style>
<!--[if mso]>
<style type="text/css">
  body, table, td, a, p, h1, h2, h3, h4, span, div { font-family: Arial, Helvetica, sans-serif !important; }
</style>
<![endif]-->
</head>
<body style="margin:0;padding:0;background:#FAFAFA;-webkit-text-size-adjust:100%;font-family:{$font};font-size:{$fsBody};line-height:1.6;color:#2C2C3A;">
<table role="presentation" class="bdgs-email-shell" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#FAFAFA;font-family:{$font};">
  <tr>
    <td align="center" style="padding:28px 16px;font-family:{$font};">
      <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="border-collapse:collapse;max-width:600px;width:100%;background:#FFFFFF;border-radius:14px;overflow:hidden;border:1px solid #E8E8EE;font-family:{$font};">
        <tr>
          <td style="background:linear-gradient(135deg,#E74D56 0%,#95256E 100%);padding:2px 0 0;"></td>
        </tr>
        <tr>
          <td style="background:#FFFFFF;padding:22px 28px 18px;border-bottom:1px solid #E8E8EE;font-family:{$font};">
            <a href="{$siteUrl}" style="text-decoration:none;">
              <img src="{$logo}" alt="{$siteName}" width="160" height="36" style="display:block;border:0;height:36px;width:auto;max-width:180px;">
            </a>
          </td>
        </tr>
        <tr>
          <td class="bdgs-email-content" style="padding:28px;font-family:{$font};font-size:{$fsBody};line-height:1.6;color:#2C2C3A;">
            {$content}
          </td>
        </tr>
        <tr>
          <td style="padding:20px 28px 24px;background:#FAFAFA;border-top:1px solid #E8E8EE;font-family:{$font};">
            <p style="margin:0 0 8px;font-family:{$font};font-size:{$fsSmall};line-height:1.5;color:#6B6B80;">
              <strong style="color:#1A1A2E;">{$siteName}</strong><br>
              Brilliant Directories specialists — setup, tools, and live Zoom Clinics.
            </p>
            <p style="margin:0;font-family:{$font};font-size:{$fsSmall};line-height:1.5;color:#6B6B80;">
              <a href="{$siteUrl}" style="color:#E74D56;text-decoration:none;font-family:{$font};">bdgrowthsuite.com</a>
              &nbsp;·&nbsp;
              <a href="{$siteUrl}/zoom-clinics" style="color:#E74D56;text-decoration:none;font-family:{$font};">Zoom Clinics</a>
              &nbsp;·&nbsp;
              <a href="{$siteUrl}/privacy" style="color:#6B6B80;text-decoration:none;font-family:{$font};">Privacy</a>
            </p>
            <p style="margin:12px 0 0;font-family:{$font};font-size:11px;color:#9CA3AF;">© {$year} {$siteName}. All rights reserved.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
HTML;
    }

    private static function extractBody(string $html): string
    {
        if (preg_match('/<body[^>]*>(.*)<\/body>/is', $html, $matches)) {
            return trim($matches[1]);
        }

        $html = preg_replace('/<!DOCTYPE[^>]*>/i', '', $html) ?? $html;
        $html = preg_replace('/<\/?html[^>]*>/i', '', $html) ?? $html;
        $html = preg_replace('/<head\b[^>]*>.*?<\/head>/is', '', $html) ?? $html;
        $html = preg_replace('/<\/?body[^>]*>/i', '', $html) ?? $html;

        return trim($html);
    }

    private static function restyleLegacyAccents(string $html): string
    {
        return str_ireplace(
            ['#0d83dd', '#0563c1', '#6366f1', '#4f46e5', '#2563eb'],
            '#E74D56',
            $html
        );
    }

    /**
     * Force brand font stack on legacy inline styles (Calibri, Arial-only, etc.).
     */
    private static function restyleLegacyFonts(string $html, string $fontStack): string
    {
        $html = preg_replace(
            '/font-family\s*:\s*[^;"]+/i',
            'font-family:'.$fontStack,
            $html
        ) ?? $html;

        // Common legacy point sizes → theme body / small
        $html = str_ireplace(
            ['font-size:12pt', 'font-size: 12pt', 'font-size:11pt', 'font-size: 11pt'],
            'font-size:'.self::FS_BODY,
            $html
        );
        $html = str_ireplace(
            ['font-size:14px', 'font-size: 14px'],
            'font-size:'.self::FS_BODY,
            $html
        );
        $html = str_ireplace(
            ['font-size:16pt', 'font-size: 16pt', 'font-size:18px', 'font-size: 18px'],
            'font-size:'.self::FS_H2,
            $html
        );
        $html = str_ireplace(
            ['font-size:22px', 'font-size: 22px'],
            'font-size:'.self::FS_H1,
            $html
        );

        return $html;
    }
}
