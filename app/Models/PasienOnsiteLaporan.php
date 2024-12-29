<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasienOnsiteLaporan extends Model
{
    protected $table = 'pasien_onsite_laporans';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kode_puskesmas','username');
    }
}
