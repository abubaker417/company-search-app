<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySg;
use App\Models\CompanyMx;
use App\Models\ReportSg;
use App\Models\ReportMx;
use App\Models\State;
use App\Models\ReportState;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display the search page
     */
    public function index()
    {
        return view('companies.search');
    }

    /**
     * Search companies across both databases
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $results = [];

        if (strlen($query) >= 2) {
            // Search Singapore companies
            $sgCompanies = CompanySg::where('name', 'LIKE', "%{$query}%")
                ->orWhere('registration_number', 'LIKE', "%{$query}%")
                ->limit(50)
                ->get()
                ->map(function ($company) {
                    return [
                        'id' => $company->id,
                        'name' => $company->name,
                        'registration_number' => $company->registration_number,
                        'address' => $company->address,
                        'country' => 'SG',
                        'slug' => $company->slug,
                        'url' => route('companies.show', ['country' => 'SG', 'id' => $company->id])
                    ];
                });

            // Search Mexico companies
            $mxCompanies = CompanyMx::with('state')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('slug', 'LIKE', "%{$query}%")
                ->limit(50)
                ->get()
                ->map(function ($company) {
                    return [
                        'id' => $company->id,
                        'name' => $company->name,
                        'registration_number' => $company->slug, // Using slug as identifier
                        'address' => $company->address,
                        'country' => 'MX',
                        'slug' => $company->slug,
                        'state' => $company->state ? $company->state->name : null,
                        'url' => route('companies.show', ['country' => 'MX', 'id' => $company->id])
                    ];
                });

            $results = $sgCompanies->concat($mxCompanies)->take(100);
        }

        if ($request->ajax()) {
            return response()->json($results);
        }

        return view('companies.search', compact('results', 'query'));
    }

    /**
     * Show company details
     */
    public function show($country, $id)
    {
        if ($country === 'SG') {
            $company = CompanySg::findOrFail($id);
            $reports = ReportSg::active()->ordered()->get();
            
            return view('companies.show', compact('company', 'reports', 'country'));
        } elseif ($country === 'MX') {
            $company = CompanyMx::with('state')->findOrFail($id);
            
            // Get reports available for this company's state
            $reports = collect();
            if ($company->state) {
                $reports = ReportState::with('report')
                    ->where('state_id', $company->state_id)
                    ->get()
                    ->map(function ($reportState) {
                        $report = $reportState->report;
                        $report->price = $reportState->amount;
                        return $report;
                    });
            }
            
            return view('companies.show', compact('company', 'reports', 'country'));
        }

        abort(404);
    }

    /**
     * Get company reports for AJAX requests
     */
    public function getReports($country, $id)
    {
        if ($country === 'SG') {
            $company = CompanySg::findOrFail($id);
            $reports = ReportSg::active()->ordered()->get();
            
            return response()->json([
                'company' => $company,
                'reports' => $reports
            ]);
        } elseif ($country === 'MX') {
            $company = CompanyMx::with('state')->findOrFail($id);
            
            $reports = collect();
            if ($company->state) {
                $reports = ReportState::with('report')
                    ->where('state_id', $company->state_id)
                    ->get()
                    ->map(function ($reportState) {
                        $report = $reportState->report;
                        $report->price = $reportState->amount;
                        return $report;
                    });
            }
            
            return response()->json([
                'company' => $company,
                'reports' => $reports
            ]);
        }

        return response()->json(['error' => 'Invalid country'], 404);
    }
}
