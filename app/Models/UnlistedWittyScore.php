<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnlistedWittyScore extends Model
{
    protected $table      = 'unlisted_witty_scores';
    protected $primaryKey = 'UL_WS_ID';
    public    $timestamps = false;

    protected $fillable = [
        'UL_WS_FINCODE',
        'UL_WS_FINANCIAL_HEALTH',
        'UL_WS_VALUATION',
        'UL_WS_GROWTH_POTENTIAL',
        'UL_WS_IPO_PROBABILITY',
        'UL_WS_LIQUIDITY_SAFETY',
        'UL_WS_ACTIVE',
        'UL_WS_INSERT_TIME',
        'UL_WS_UPDATE_TIME',
    ];

    // Fixed weights from the WittyScore methodology page — kept in one place
    // so the admin-entered pillar scores and the public-facing formula can
    // never drift apart.
    public const WEIGHTS = [
        'UL_WS_FINANCIAL_HEALTH' => 0.30,
        'UL_WS_VALUATION'        => 0.20,
        'UL_WS_GROWTH_POTENTIAL' => 0.20,
        'UL_WS_IPO_PROBABILITY'  => 0.15,
        'UL_WS_LIQUIDITY_SAFETY' => 0.15,
    ];

    public function overall(): ?float
    {
        $total  = 0;
        $weight = 0;

        foreach (self::WEIGHTS as $field => $w) {
            if ($this->$field === null) {
                continue;
            }
            $total  += (float) $this->$field * $w;
            $weight += $w;
        }

        return $weight > 0 ? round($total / $weight, 1) : null;
    }
}
