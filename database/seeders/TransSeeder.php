<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'tran_id' => 1,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 5000.00,
                'ccy_id' => 1, // USD
                'discription' => 'Initial customer deposit - Account opening',
                'user_id' => 1,
                'done_date' => Carbon::now()->subDays(15),
                'approved_by' => 2,
                'tran_type' => 1, // Deposit
            ],
            [
                'tran_id' => 2,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->subDays(14)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 25000.00,
                'ccy_id' => 1, // USD
                'discription' => 'Loan disbursement - Personal loan contract #LN001',
                'user_id' => 1,
                'done_date' => Carbon::now()->subDays(14),
                'approved_by' => 3,
                'tran_type' => 2, // Loan disbursement
            ],
            [
                'tran_id' => 3,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->subDays(13)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 1500.00,
                'ccy_id' => 1, // USD
                'discription' => 'Customer withdrawal - ATM transaction',
                'user_id' => 2,
                'done_date' => Carbon::now()->subDays(13),
                'approved_by' => 1,
                'tran_type' => 3, // Withdrawal
            ],
            [
                'tran_id' => 4,
                'branch_id' => 2,
                'tran_date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 850.50,
                'ccy_id' => 1, // USD
                'discription' => 'Loan payment - Monthly installment payment',
                'user_id' => 3,
                'done_date' => Carbon::now()->subDays(12),
                'approved_by' => 2,
                'tran_type' => 4, // Loan payment
            ],
            [
                'tran_id' => 5,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->subDays(11)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 200000.00,
                'ccy_id' => 2, // KHR
                'discription' => 'Large cash deposit - Business account funding',
                'user_id' => 1,
                'done_date' => Carbon::now()->subDays(11),
                'approved_by' => 3,
                'tran_type' => 1, // Deposit
            ],
            [
                'tran_id' => 6,
                'branch_id' => 2,
                'tran_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 750.00,
                'ccy_id' => 1, // USD
                'discription' => 'Interest payment - Savings account interest credit',
                'user_id' => 2,
                'done_date' => Carbon::now()->subDays(10),
                'approved_by' => 1,
                'tran_type' => 5, // Interest payment
            ],
            [
                'tran_id' => 7,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->subDays(9)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 15000.00,
                'ccy_id' => 1, // USD
                'discription' => 'Fixed deposit placement - 12 month term',
                'user_id' => 4,
                'done_date' => Carbon::now()->subDays(9),
                'approved_by' => 2,
                'tran_type' => 6, // Fixed deposit
            ],
            [
                'tran_id' => 8,
                'branch_id' => 3,
                'tran_date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 320.75,
                'ccy_id' => 1, // USD
                'discription' => 'Service fee collection - Account maintenance charge',
                'user_id' => 3,
                'done_date' => Carbon::now()->subDays(8),
                'approved_by' => 1,
                'tran_type' => 7, // Service fee
            ],
            [
                'tran_id' => 9,
                'branch_id' => 2,
                'tran_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 3500.00,
                'ccy_id' => 1, // USD
                'discription' => 'Wire transfer outgoing - International remittance',
                'user_id' => 5,
                'done_date' => Carbon::now()->subDays(7),
                'approved_by' => 3,
                'tran_type' => 8, // Wire transfer
            ],
            [
                'tran_id' => 10,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 125000.00,
                'ccy_id' => 2, // KHR
                'discription' => 'Currency exchange - USD to KHR conversion',
                'user_id' => 1,
                'done_date' => Carbon::now()->subDays(6),
                'approved_by' => 2,
                'tran_type' => 9, // Currency exchange
            ],
            [
                'tran_id' => 11,
                'branch_id' => 3,
                'tran_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 1200.00,
                'ccy_id' => 1, // USD
                'discription' => 'Loan payment - Principal and interest payment',
                'user_id' => 2,
                'done_date' => Carbon::now()->subDays(5),
                'approved_by' => 1,
                'tran_type' => 4, // Loan payment
            ],
            [
                'tran_id' => 12,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 8000.00,
                'ccy_id' => 1, // USD
                'discription' => 'New loan disbursement - Business expansion loan',
                'user_id' => 4,
                'done_date' => Carbon::now()->subDays(4),
                'approved_by' => 3,
                'tran_type' => 2, // Loan disbursement
            ],
            [
                'tran_id' => 13,
                'branch_id' => 2,
                'tran_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 450.25,
                'ccy_id' => 1, // USD
                'discription' => 'Check deposit - Customer business income',
                'user_id' => 3,
                'done_date' => Carbon::now()->subDays(3),
                'approved_by' => 2,
                'tran_type' => 1, // Deposit
            ],
            [
                'tran_id' => 14,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 95.00,
                'ccy_id' => 1, // USD
                'discription' => 'Penalty fee - Late loan payment charge',
                'user_id' => 1,
                'done_date' => Carbon::now()->subDays(2),
                'approved_by' => 2,
                'tran_type' => 10, // Penalty fee
            ],
            [
                'tran_id' => 15,
                'branch_id' => 3,
                'tran_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 2200.00,
                'ccy_id' => 1, // USD
                'discription' => 'Fixed deposit maturity - Principal and interest payout',
                'user_id' => 5,
                'done_date' => Carbon::now()->subDays(1),
                'approved_by' => 1,
                'tran_type' => 11, // FD maturity
            ],
            [
                'tran_id' => 16,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 600.00,
                'ccy_id' => 1, // USD
                'discription' => 'Counter withdrawal - Customer cash request',
                'user_id' => 2,
                'done_date' => Carbon::now(),
                'approved_by' => 3,
                'tran_type' => 3, // Withdrawal
            ],
            [
                'tran_id' => 17,
                'branch_id' => 2,
                'tran_date' => Carbon::now()->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 75000.00,
                'ccy_id' => 2, // KHR
                'discription' => 'Bulk cash deposit - Daily business collection',
                'user_id' => 4,
                'done_date' => Carbon::now(),
                'approved_by' => 1,
                'tran_type' => 1, // Deposit
            ],
            [
                'tran_id' => 18,
                'branch_id' => 3,
                'tran_date' => Carbon::now()->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 1850.50,
                'ccy_id' => 1, // USD
                'discription' => 'Loan installment - Scheduled monthly payment',
                'user_id' => 3,
                'done_date' => Carbon::now(),
                'approved_by' => 2,
                'tran_type' => 4, // Loan payment
            ],
            [
                'tran_id' => 19,
                'branch_id' => 1,
                'tran_date' => Carbon::now()->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 250.00,
                'ccy_id' => 1, // USD
                'discription' => 'Account closing fee - Customer account termination',
                'user_id' => 1,
                'done_date' => Carbon::now(),
                'approved_by' => 3,
                'tran_type' => 7, // Service fee
            ],
            [
                'tran_id' => 20,
                'branch_id' => 2,
                'tran_date' => Carbon::now()->format('Y-m-d'),
                'gl_map_id' => null,
                'amount' => 5500.00,
                'ccy_id' => 1, // USD
                'discription' => 'Wire transfer incoming - International payment received',
                'user_id' => 5,
                'done_date' => Carbon::now(),
                'approved_by' => 1,
                'tran_type' => 12, // Incoming wire
            ],
        ];

        // Insert the transactions
        DB::table('trans')->insert($transactions);

        $this->command->info('Transactions seeder completed successfully!');
        $this->command->info('Created ' . count($transactions) . ' transaction records');

        // Display summary statistics
        $transactionTypes = [
            1 => 'Deposits',
            2 => 'Loan Disbursements',
            3 => 'Withdrawals',
            4 => 'Loan Payments',
            5 => 'Interest Payments',
            6 => 'Fixed Deposits',
            7 => 'Service Fees',
            8 => 'Wire Transfers Out',
            9 => 'Currency Exchange',
            10 => 'Penalty Fees',
            11 => 'FD Maturity',
            12 => 'Wire Transfers In'
        ];

        $summary = collect($transactions)->groupBy('tran_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total_amount' => $group->sum('amount')
            ];
        });

        $this->command->info("Summary by Transaction Type:");
        foreach ($summary as $type => $stats) {
            $typeName = $transactionTypes[$type] ?? "Type {$type}";
            $this->command->info("- {$typeName}: {$stats['count']} transactions, Total: \${$stats['total_amount']}");
        }

        // Branch summary
        $branchSummary = collect($transactions)->groupBy('branch_id')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total_amount' => $group->sum('amount')
            ];
        });

        $this->command->info("Summary by Branch:");
        foreach ($branchSummary as $branchId => $stats) {
            $this->command->info("- Branch {$branchId}: {$stats['count']} transactions, Total: \${$stats['total_amount']}");
        }
    }
}
