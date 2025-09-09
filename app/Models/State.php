<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $connection = 'companies_house_mx';
    protected $table = 'states';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name'
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Get all companies in this state
     */
    // public function companies()
    // {
    //     return $this->hasMany(CompanyMx::class, 'state_id');
    // }

    /**
     * Get all reports available for this state
     */
    // public function reports()
    // {
    //     return $this->belongsToMany(ReportMx::class, 'report_state', 'state_id', 'report_id')
    //                 ->withPivot('amount')
    //                 ->withTimestamps();
    // }
}
