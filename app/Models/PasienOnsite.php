<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasienOnsite extends Model
{
    protected $connection = 'mysql2'; // database lain
    protected $table = 'pasien_onsite'; // table dalam database lain
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kode_puskesmas','username')->on('mysql');
    }
}
