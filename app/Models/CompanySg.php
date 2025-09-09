<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySg extends Model
{
    use HasFactory;

    protected $connection = 'companies_house_sg';
    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'slug',
        'name',
        'former_names',
        'registration_number',
        'address',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all reports for Singapore companies
     * In Singapore, all companies have access to all reports
     */
    // public function reports()
    // {
    //     return $this->hasMany(ReportSg::class, 'id', 'id');
    // }

    /**
     * Get the country code for this company
     */
    public function getCountryAttribute()
    {
        return 'SG';
    }
}
