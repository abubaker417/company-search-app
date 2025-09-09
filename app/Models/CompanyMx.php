<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMx extends Model
{
    use HasFactory;

    protected $connection = 'companies_house_mx';
    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'state_id',
        'slug',
        'name',
        'brand_name',
        'address',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'state_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the state this company belongs to
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * Get reports available for this company based on its state
     */
    // public function reports()
    // {
    //     return $this->hasManyThrough(
    //         ReportState::class,
    //         State::class,
    //         'id', // Foreign key on states table
    //         'state_id', // Foreign key on report_state table
    //         'state_id', // Local key on companies table
    //         'id' // Local key on states table
    //     )->join('reports', 'report_state.report_id', '=', 'reports.id')
    //      ->select('reports.*', 'report_state.amount as price');
    // }

    /**
     * Get the country code for this company
     */
    public function getCountryAttribute()
    {
        return 'MX';
    }
}
