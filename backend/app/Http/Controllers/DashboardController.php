<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user();
        
        // Target month (default to current month)
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // 1. Summary Cards (Total Income, Total Expense, Balance) for the month
        $monthlyTransactions = $user->transactions()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->get();

        $totalIncome = $monthlyTransactions->where('type', 'income')->sum('amount');
        $totalExpense = $monthlyTransactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // 2. Pie Chart: Expenses grouped by Category (for the month)
        $expenseByCategory = $user->transactions()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $user->id)
            ->where('transactions.type', 'expense')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->select('categories.name', 'categories.color', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->get();

        // 3. Bar Chart: Last 6 months Income vs Expense
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        
        $recentTransactions = $user->transactions()
            ->where('transaction_date', '>=', $sixMonthsAgo->format('Y-m-d'))
            ->get();

        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $targetDate = Carbon::now()->subMonths($i);
            
            $monthData = $recentTransactions->filter(function ($t) use ($targetDate) {
                $tDate = Carbon::parse($t->transaction_date);
                return $tDate->year === $targetDate->year && $tDate->month === $targetDate->month;
            });

            $monthlyTrend[] = [
                'month_label' => $targetDate->format('M Y'), // e.g., May 2026
                'income' => $monthData->where('type', 'income')->sum('amount'),
                'expense' => $monthData->where('type', 'expense')->sum('amount'),
            ];
        }

        // 4. Latest 5 transactions
        $latestTransactions = $user->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->take(5)
            ->get();

        return response()->json([
            'summary' => [
                'income' => $totalIncome,
                'expense' => $totalExpense,
                'balance' => $balance
            ],
            'expense_by_category' => $expenseByCategory,
            'monthly_trend' => $monthlyTrend,
            'latest_transactions' => $latestTransactions
        ]);
    }
}
