<?php

namespace App\Support;

use App\Models\BdgsBlabsReview;

class ReviewPresenter
{
    public const SUBMITTER_LABEL = 'Verified Brilliant Directories Site Owner';

    public const MARKETPLACE_PARTNER_BASE = 'https://marketplace.brilliantdirectories.com/india/partner/business-labs';

    /** @var list<string> */
    public const CAROUSEL_MARKETPLACE_IDS = [
        '910', '912', '862', '928', '932', '881', '864', '848', '742', '838', '827',
    ];

    public static function anonymize(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $clean = $text;
        $replacements = [
            '/arendal\.net/i' => 'our directory website',
            '/ConnectCare\.ie/i' => 'our directory website',
            '/uppercervicalcare\.com/i' => 'our directory website',
            '/RealEstatePhotography\.com/i' => 'our directory website',
            '/petrolheadlife\.com/i' => 'our directory website',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $clean = preg_replace($pattern, $replacement, $clean) ?? $clean;
        }

        return preg_replace(
            '/\b([a-z0-9]+(-[a-z0-9]+)*\.)+(com|net|org|ie|co|io|edu|gov|co\.uk)\b/i',
            'our directory website',
            $clean
        ) ?? $clean;
    }

    public static function verifyLink(BdgsBlabsReview $review): string
    {
        if ($review->verify_link !== '' && preg_match('#^https?://#i', $review->verify_link)) {
            return $review->verify_link;
        }

        $marketplaceId = $review->marketplace_review_id;

        if ($marketplaceId !== '') {
            return self::MARKETPLACE_PARTNER_BASE.'/reviews/'.$marketplaceId;
        }

        return self::MARKETPLACE_PARTNER_BASE;
    }

    public static function displayDate(BdgsBlabsReview $review): string
    {
        if ($review->review_date === null) {
            return 'Recent';
        }

        return $review->review_date->format('j M Y');
    }

    public static function schemaDate(BdgsBlabsReview $review): string
    {
        if ($review->review_date === null) {
            return now()->format('Y-m-d');
        }

        return $review->review_date->format('Y-m-d');
    }

    public static function isoDate(BdgsBlabsReview $review): string
    {
        if ($review->review_date === null) {
            return now()->toIso8601String();
        }

        return $review->review_date->startOfDay()->toIso8601String();
    }

    public static function cleanTitle(BdgsBlabsReview $review): string
    {
        $title = self::anonymize($review->title);

        return $title !== '' ? $title : 'Review';
    }

    public static function cleanBody(BdgsBlabsReview $review): string
    {
        return self::anonymize($review->review_text);
    }

    /**
     * @return array<string, mixed>
     */
    public static function toCarouselArray(BdgsBlabsReview $review): array
    {
        return [
            'marketplace_review_id' => $review->marketplace_review_id,
            'review_id' => $review->review_id,
            'review_date' => self::displayDate($review),
            'title' => self::cleanTitle($review),
            'review_text' => self::cleanBody($review),
            'overall_rating' => (int) $review->overall_rating,
            'service_rating' => (int) $review->service_rating,
            'responsiveness_rating' => (int) $review->responsiveness_rating,
            'expertise_rating' => (int) $review->expertise_rating,
            'results_rating' => (int) $review->results_rating,
            'communication_rating' => (int) $review->communication_rating,
            'verify_link' => self::verifyLink($review),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function toSchemaReview(BdgsBlabsReview $review): array
    {
        return [
            '@type' => 'Review',
            'url' => self::verifyLink($review),
            'datePublished' => self::schemaDate($review),
            'name' => self::cleanTitle($review),
            'reviewBody' => self::cleanBody($review),
            'reviewRating' => [
                '@type' => 'Rating',
                'ratingValue' => (string) max(1, min(5, (int) $review->overall_rating)),
                'bestRating' => '5',
                'worstRating' => '1',
            ],
            'author' => [
                '@type' => 'Person',
                'name' => self::SUBMITTER_LABEL,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Brilliant Directories Marketplace',
                'url' => 'https://marketplace.brilliantdirectories.com',
            ],
        ];
    }

    /**
     * @param  iterable<BdgsBlabsReview>  $reviews
     * @return array<string, mixed>
     */
    public static function professionalServiceGraph(iterable $reviews, int $reviewCount): array
    {
        $reviewItems = [];
        foreach ($reviews as $review) {
            $reviewItems[] = self::toSchemaReview($review);
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            '@id' => 'https://bdgrowthsuite.com/#organization',
            'name' => 'BD Growth Suite by Business Labs',
            'url' => 'https://bdgrowthsuite.com',
            'logo' => 'https://bdgrowthsuite.com/images/brand/logo.png',
            'description' => 'Expert Brilliant Directories developers and partners. BD Growth Suite by Business Labs.',
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '5.0',
                'reviewCount' => (string) $reviewCount,
                'bestRating' => '5',
                'worstRating' => '1',
            ],
            'review' => $reviewItems,
        ];
    }
}
