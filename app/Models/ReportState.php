<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportState extends Model
{
    use HasFactory;

    protected $connection = 'companies_house_mx';
    protected $table = 'report_state';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'report_id',
        'state_id',
        'amount',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'report_id' => 'integer',
        'state_id' => 'integer',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the report this state-report relationship belongs to
     */
    public function report()
    {
        return $this->belongsTo(ReportMx::class, 'report_id');
    }

    /**
     * Get the state this report-state relationship belongs to
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
