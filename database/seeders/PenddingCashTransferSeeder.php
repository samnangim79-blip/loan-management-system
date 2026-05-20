<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenddingCashTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transfers = [
            [
                'pendding_cash_transfer_id' => 1,
                'amount' => 1500.00,
                'in_ou' => 'i',
                'ccy_id' => 1, // USD
                'user_id' => 1,
                'sent_date' => Carbon::now()->subDays(3),
                'remark' => 'Transfer from Main Branch - Monthly Settlement',
                'status_id' => 0, // Pending
            ],
            [
                'pendding_cash_transfer_id' => 2,
                'amount' => 2500.50,
                'in_ou' => 'o',
                'ccy_id' => 1, // USD
                'user_id' => 2,
                'sent_date' => Carbon::now()->subDays(2),
                'remark' => 'Transfer to Regional Office - Operational Fund',
                'status_id' => 1, // Received
            ],
            [
                'pendding_cash_transfer_id' => 3,
                'amount' => 50000.00,
                'in_ou' => 'i',
                'ccy_id' => 2, // KHR (assuming)
                'user_id' => 1,
                'sent_date' => Carbon::now()->subDays(1),
                'remark' => 'Emergency fund transfer from headquarters',
                'status_id' => 0, // Pending
            ],
            [
                'pendding_cash_transfer_id' => 4,
                'amount' => 800.75,
                'in_ou' => 'o',
                'ccy_id' => 1, // USD
                'user_id' => 3,
                'sent_date' => Carbon::now()->subHours(6),
                'remark' => 'Daily cash transfer to vault',
                'status_id' => 0, // Pending
            ],
            [
                'pendding_cash_transfer_id' => 5,
                'amount' => 1200.00,
                'in_ou' => 'i',
                'ccy_id' => 1, // USD
                'user_id' => 2,
                'sent_date' => Carbon::now()->subWeek(),
                'remark' => 'Weekly collection from branch network',
                'status_id' => 1, // Received
            ],
            [
                'pendding_cash_transfer_id' => 6,
                'amount' => 300.25,
                'in_ou' => 'o',
                'ccy_id' => 1, // USD
                'user_id' => 4,
                'sent_date' => Carbon::now()->subDays(5),
                'remark' => 'Petty cash replenishment',
                'status_id' => 1, // Received
            ],
            [
                'pendding_cash_transfer_id' => 7,
                'amount' => 75000.00,
                'in_ou' => 'i',
                'ccy_id' => 2, // KHR
                'user_id' => 1,
                'sent_date' => Carbon::now()->subHours(2),
                'remark' => 'Large denomination currency exchange',
                'status_id' => 0, // Pending
            ],
            [
                'pendding_cash_transfer_id' => 8,
                'amount' => 950.00,
                'in_ou' => 'o',
                'ccy_id' => 1, // USD
                'user_id' => 5,
                'sent_date' => Carbon::now()->subDays(8),
                'remark' => 'ATM cash loading - Downtown branch',
                'status_id' => 1, // Received
            ],
            [
                'pendding_cash_transfer_id' => 9,
                'amount' => 2200.30,
                'in_ou' => 'i',
                'ccy_id' => 1, // USD
                'user_id' => 3,
                'sent_date' => Carbon::now()->subMinutes(30),
                'remark' => 'Urgent transfer - Customer large withdrawal prep',
                'status_id' => 0, // Pending
            ],
            [
                'pendding_cash_transfer_id' => 10,
                'amount' => 180.50,
                'in_ou' => 'o',
                'ccy_id' => 1, // USD
                'user_id' => 2,
                'sent_date' => Carbon::now()->subDays(10),
                'remark' => 'Small denomination currency request',
                'status_id' => 1, // Received
            ],
            [
                'pendding_cash_transfer_id' => 11,
                'amount' => 5000.00,
                'in_ou' => 'i',
                'ccy_id' => 1, // USD
                'user_id' => 1,
                'sent_date' => Carbon::now()->subHours(4),
                'remark' => 'End-of-day settlement transfer',
                'status_id' => 0, // Pending
            ],
            [
                'pendding_cash_transfer_id' => 12,
                'amount' => 35000.00,
                'in_ou' => 'o',
                'ccy_id' => 2, // KHR
                'user_id' => 4,
                'sent_date' => Carbon::now()->subDays(7),
                'remark' => 'Local currency distribution to branches',
                'status_id' => 1, // Received
            ],
            [
                'pendding_cash_transfer_id' => 13,
                'amount' => 1750.80,
                'in_ou' => 'i',
                'ccy_id' => 1, // USD
                'user_id' => 5,
                'sent_date' => Carbon::now()->subHours(8),
                'remark' => 'Inter-branch balance adjustment',
                'status_id' => 0, // Pending
            ],
            [
                'pendding_cash_transfer_id' => 14,
                'amount' => 420.00,
                'in_ou' => 'o',
                'ccy_id' => 1, // USD
                'user_id' => 3,
                'sent_date' => Carbon::now()->subDays(12),
                'remark' => 'Maintenance fund allocation',
                'status_id' => 1, // Received
            ],
            [
                'pendding_cash_transfer_id' => 15,
                'amount' => 8500.00,
                'in_ou' => 'i',
                'ccy_id' => 1, // USD
                'user_id' => 1,
                'sent_date' => Carbon::now()->subHours(1),
                'remark' => 'Weekend cash preparation - Multiple branches',
                'status_id' => 0, // Pending
            ],
        ];

        // Insert the transfers
        DB::table('pendding_cash_transfers')->insert($transfers);

        $this->command->info('Pending cash transfers seeder completed successfully!');
        $this->command->info('Created ' . count($transfers) . ' cash transfer records');

        // Display summary statistics
        $pendingCount = collect($transfers)->where('status_id', 0)->count();
        $receivedCount = collect($transfers)->where('status_id', 1)->count();
        $incomingCount = collect($transfers)->where('in_ou', 'i')->count();
        $outgoingCount = collect($transfers)->where('in_ou', 'o')->count();

        $this->command->info("Summary:");
        $this->command->info("- Pending transfers: {$pendingCount}");
        $this->command->info("- Received transfers: {$receivedCount}");
        $this->command->info("- Incoming transfers: {$incomingCount}");
        $this->command->info("- Outgoing transfers: {$outgoingCount}");
    }
}
