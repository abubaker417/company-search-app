<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSg extends Model
{
    use HasFactory;

    protected $connection = 'companies_house_sg';
    protected $table = 'reports';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'amount',
        'info',
        'is_active',
        'created_at',
        'updated_at',
        'default',
        'order'
    ];

    protected $casts = [
        'id' => 'integer',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'default' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to get only active reports
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order reports by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
