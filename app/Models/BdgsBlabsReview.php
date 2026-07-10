<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BdgsBlabsReview extends Model
{
    protected $table = 'bdgs_blabs_reviews';

    protected $primaryKey = 'review_id';

    public $incrementing = true;

    protected $fillable = [
        'marketplace_review_id',
        'overall_rating',
        'service_rating',
        'responsiveness_rating',
        'expertise_rating',
        'results_rating',
        'communication_rating',
        'title',
        'review_text',
        'review_date',
        'verify_link',
        'submitter_label',
        'pmp_pid',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'review_date' => 'date',
            'is_published' => 'boolean',
            'overall_rating' => 'integer',
            'service_rating' => 'integer',
            'responsiveness_rating' => 'integer',
            'expertise_rating' => 'integer',
            'results_rating' => 'integer',
            'communication_rating' => 'integer',
            'pmp_pid' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('review_date');
    }
}
