<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\SalaryFluctuation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalaryFluctuationReportController extends Controller
{
    public function index()
    {
        $search = request('search');

        $users = User::where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $users->appends(request()->query());

        $totalSalary = DB::table(DB::raw('(SELECT DISTINCT master_user_uuid, salary FROM salary_fluctuations) as distinct_salaries'))
            ->select(DB::raw('SUM(salary) as total_salary'))
            ->value('total_salary');

        $currentYear = Carbon::now()->year;

        $defaultMonthlySalaries = collect([
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ]);

        $monthlySalaries = SalaryFluctuation::selectRaw('MONTH(created_at) as month, SUM(salary) as total_salary')
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $monthlySalaries = $monthlySalaries->mapWithKeys(function ($item) {
            return [Carbon::create()->month($item->month)->format('F') => $item->total_salary];
        });


        $monthlySalaries = $defaultMonthlySalaries->merge($monthlySalaries);

        return view('backoffice.pages.hr.salary-fluctuation.index', compact(
            'search',
            'users',
            'totalSalary',
            'monthlySalaries'
        ));
    }
}
