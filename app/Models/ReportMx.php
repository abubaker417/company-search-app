<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportMx extends Model
{
    use HasFactory;

    protected $connection = 'companies_house_mx';
    protected $table = 'reports';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'info',
        'order',
        'default',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'order' => 'integer',
        'default' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all states that have this report available
     */
    public function states()
    {
        return $this->belongsToMany(State::class, 'report_state', 'report_id', 'state_id')
                    ->withPivot('amount')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active reports
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to order reports by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
