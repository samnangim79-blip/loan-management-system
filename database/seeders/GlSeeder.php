<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert GL Level 1 accounts
        DB::table('gl_l1s')->insert([
            [
                'l1_id' => 1,
                'l1_desc' => 'Assets',
                'drcr' => 'DR',
            ],
            [
                'l1_id' => 2,
                'l1_desc' => 'Liabilities',
                'drcr' => 'CR',
            ],
            [
                'l1_id' => 3,
                'l1_desc' => 'Equity',
                'drcr' => 'CR',
            ],
            [
                'l1_id' => 4,
                'l1_desc' => 'Income',
                'drcr' => 'CR',
            ],
            [
                'l1_id' => 5,
                'l1_desc' => 'Expenses',
                'drcr' => 'DR',
            ],
        ]);

        // Insert GL Level 2 accounts
        DB::table('gl_l2s')->insert([
            // Assets
            [
                'l2_id' => 11,
                'l2_desc' => 'Current Assets',
                'l1_id' => 1,
            ],
            [
                'l2_id' => 12,
                'l2_desc' => 'Fixed Assets',
                'l1_id' => 1,
            ],
            [
                'l2_id' => 13,
                'l2_desc' => 'Other Assets',
                'l1_id' => 1,
            ],
            // Liabilities
            [
                'l2_id' => 21,
                'l2_desc' => 'Current Liabilities',
                'l1_id' => 2,
            ],
            [
                'l2_id' => 22,
                'l2_desc' => 'Long-term Liabilities',
                'l1_id' => 2,
            ],
            // Equity
            [
                'l2_id' => 31,
                'l2_desc' => 'Share Capital',
                'l1_id' => 3,
            ],
            [
                'l2_id' => 32,
                'l2_desc' => 'Retained Earnings',
                'l1_id' => 3,
            ],
            // Income
            [
                'l2_id' => 41,
                'l2_desc' => 'Interest Income',
                'l1_id' => 4,
            ],
            [
                'l2_id' => 42,
                'l2_desc' => 'Fee Income',
                'l1_id' => 4,
            ],
            [
                'l2_id' => 43,
                'l2_desc' => 'Other Income',
                'l1_id' => 4,
            ],
            // Expenses
            [
                'l2_id' => 51,
                'l2_desc' => 'Interest Expense',
                'l1_id' => 5,
            ],
            [
                'l2_id' => 52,
                'l2_desc' => 'Operating Expenses',
                'l1_id' => 5,
            ],
            [
                'l2_id' => 53,
                'l2_desc' => 'Administrative Expenses',
                'l1_id' => 5,
            ],
        ]);

        // Insert GL Level 3 accounts
        DB::table('gl_l3s')->insert([
            // Current Assets
            [
                'l3_id' => 111,
                'l3_desc' => 'Cash and Cash Equivalents',
                'l2_id' => 11,
            ],
            [
                'l3_id' => 112,
                'l3_desc' => 'Customer Deposits',
                'l2_id' => 11,
            ],
            [
                'l3_id' => 113,
                'l3_desc' => 'Loans and Advances',
                'l2_id' => 11,
            ],
            // Fixed Assets
            [
                'l3_id' => 121,
                'l3_desc' => 'Property and Equipment',
                'l2_id' => 12,
            ],
            [
                'l3_id' => 122,
                'l3_desc' => 'Furniture and Fixtures',
                'l2_id' => 12,
            ],
            // Current Liabilities
            [
                'l3_id' => 211,
                'l3_desc' => 'Customer Savings',
                'l2_id' => 21,
            ],
            [
                'l3_id' => 212,
                'l3_desc' => 'Term Deposits',
                'l2_id' => 21,
            ],
            [
                'l3_id' => 213,
                'l3_desc' => 'Accrued Interest Payable',
                'l2_id' => 21,
            ],
            // Interest Income
            [
                'l3_id' => 411,
                'l3_desc' => 'Loan Interest Income',
                'l2_id' => 41,
            ],
            [
                'l3_id' => 412,
                'l3_desc' => 'Investment Income',
                'l2_id' => 41,
            ],
            // Fee Income
            [
                'l3_id' => 421,
                'l3_desc' => 'Service Fees',
                'l2_id' => 42,
            ],
            [
                'l3_id' => 422,
                'l3_desc' => 'Processing Fees',
                'l2_id' => 42,
            ],
            // Interest Expense
            [
                'l3_id' => 511,
                'l3_desc' => 'Deposit Interest Expense',
                'l2_id' => 51,
            ],
            // Operating Expenses
            [
                'l3_id' => 521,
                'l3_desc' => 'Staff Salaries',
                'l2_id' => 52,
            ],
            [
                'l3_id' => 522,
                'l3_desc' => 'Office Rent',
                'l2_id' => 52,
            ],
            [
                'l3_id' => 523,
                'l3_desc' => 'Utilities',
                'l2_id' => 52,
            ],
        ]);

        // Insert GL Level 4 accounts
        DB::table('gl_l4s')->insert([
            // Cash and Cash Equivalents
            [
                'l4_id' => 1111,
                'l4_desc' => 'Cash on Hand',
                'l3_id' => 111,
            ],
            [
                'l4_id' => 1112,
                'l4_desc' => 'Bank Deposits',
                'l3_id' => 111,
            ],
            // Loans and Advances
            [
                'l4_id' => 1131,
                'l4_desc' => 'Personal Loans',
                'l3_id' => 113,
            ],
            [
                'l4_id' => 1132,
                'l4_desc' => 'Business Loans',
                'l3_id' => 113,
            ],
            [
                'l4_id' => 1133,
                'l4_desc' => 'Microfinance Loans',
                'l3_id' => 113,
            ],
            // Customer Savings
            [
                'l4_id' => 2111,
                'l4_desc' => 'Regular Savings',
                'l3_id' => 211,
            ],
            [
                'l4_id' => 2112,
                'l4_desc' => 'Current Accounts',
                'l3_id' => 211,
            ],
            // Term Deposits
            [
                'l4_id' => 2121,
                'l4_desc' => '3 Month Term Deposits',
                'l3_id' => 212,
            ],
            [
                'l4_id' => 2122,
                'l4_desc' => '6 Month Term Deposits',
                'l3_id' => 212,
            ],
            [
                'l4_id' => 2123,
                'l4_desc' => '12 Month Term Deposits',
                'l3_id' => 212,
            ],
        ]);

        // Insert main GL accounts
        DB::table('gls')->insert([
            // Cash Accounts
            [
                'gl_id' => 1001,
                'gl_code' => '1111-001',
                'gl_name' => 'Cash on Hand - USD',
                'gl_name_kh' => 'សាច់ប្រាក់នៅក្នុងដៃ - USD',
                'l4_id' => 1111,
            ],
            [
                'gl_id' => 1002,
                'gl_code' => '1111-002',
                'gl_name' => 'Cash on Hand - KHR',
                'gl_name_kh' => 'សាច់ប្រាក់នៅក្នុងដៃ - KHR',
                'l4_id' => 1111,
            ],
            [
                'gl_id' => 1003,
                'gl_code' => '1112-001',
                'gl_name' => 'Bank Account - USD',
                'gl_name_kh' => 'គណនីធនាគារ - USD',
                'l4_id' => 1112,
            ],
            [
                'gl_id' => 1004,
                'gl_code' => '1112-002',
                'gl_name' => 'Bank Account - KHR',
                'gl_name_kh' => 'គណនីធនាគារ - KHR',
                'l4_id' => 1112,
            ],

            // Loan Accounts
            [
                'gl_id' => 1101,
                'gl_code' => '1131-001',
                'gl_name' => 'Personal Loans',
                'gl_name_kh' => 'ប្រាក់កម្ចីផ្ទាល់ខ្លួន',
                'l4_id' => 1131,
            ],
            [
                'gl_id' => 1102,
                'gl_code' => '1132-001',
                'gl_name' => 'Business Loans',
                'gl_name_kh' => 'ប្រាក់កម្ចីអាជីវកម្ម',
                'l4_id' => 1132,
            ],
            [
                'gl_id' => 1103,
                'gl_code' => '1133-001',
                'gl_name' => 'Microfinance Loans',
                'gl_name_kh' => 'ប្រាក់កម្ចីមីក្រូ',
                'l4_id' => 1133,
            ],

            // Deposit Accounts
            [
                'gl_id' => 2001,
                'gl_code' => '2111-001',
                'gl_name' => 'Savings Deposits - USD',
                'gl_name_kh' => 'ប្រាក់បញ្ញើសន្សំ - USD',
                'l4_id' => 2111,
            ],
            [
                'gl_id' => 2002,
                'gl_code' => '2111-002',
                'gl_name' => 'Savings Deposits - KHR',
                'gl_name_kh' => 'ប្រាក់បញ្ញើសន្សំ - KHR',
                'l4_id' => 2111,
            ],
            [
                'gl_id' => 2003,
                'gl_code' => '2112-001',
                'gl_name' => 'Current Accounts - USD',
                'gl_name_kh' => 'គណនីចរន្ត - USD',
                'l4_id' => 2112,
            ],
            [
                'gl_id' => 2004,
                'gl_code' => '2112-002',
                'gl_name' => 'Current Accounts - KHR',
                'gl_name_kh' => 'គណនីចរន្ត - KHR',
                'l4_id' => 2112,
            ],

            // Term Deposits
            [
                'gl_id' => 2101,
                'gl_code' => '2121-001',
                'gl_name' => '3 Month Term Deposits',
                'gl_name_kh' => 'ប្រាក់បញ្ញើ ៣ ខែ',
                'l4_id' => 2121,
            ],
            [
                'gl_id' => 2102,
                'gl_code' => '2122-001',
                'gl_name' => '6 Month Term Deposits',
                'gl_name_kh' => 'ប្រាក់បញ្ញើ ៦ ខែ',
                'l4_id' => 2122,
            ],
            [
                'gl_id' => 2103,
                'gl_code' => '2123-001',
                'gl_name' => '12 Month Term Deposits',
                'gl_name_kh' => 'ប្រាក់បញ្ញើ ១២ ខែ',
                'l4_id' => 2123,
            ],

            // Income Accounts
            [
                'gl_id' => 4001,
                'gl_code' => '4111-001',
                'gl_name' => 'Loan Interest Income',
                'gl_name_kh' => 'ប្រាក់ចំណេញពីការកម្ចី',
                'l4_id' => null,
            ],
            [
                'gl_id' => 4002,
                'gl_code' => '4211-001',
                'gl_name' => 'Service Fee Income',
                'gl_name_kh' => 'ប្រាក់ចំណូលពីសេវាកម្ម',
                'l4_id' => null,
            ],
            [
                'gl_id' => 4003,
                'gl_code' => '4212-001',
                'gl_name' => 'Processing Fee Income',
                'gl_name_kh' => 'ប្រាក់ចំណូលពីការដំណើរការ',
                'l4_id' => null,
            ],

            // Expense Accounts
            [
                'gl_id' => 5001,
                'gl_code' => '5111-001',
                'gl_name' => 'Interest Expense on Deposits',
                'gl_name_kh' => 'ការចំណាយប្រាក់ចំណេញលើប្រាក់បញ្ញើ',
                'l4_id' => null,
            ],
            [
                'gl_id' => 5002,
                'gl_code' => '5211-001',
                'gl_name' => 'Staff Salaries',
                'gl_name_kh' => 'ប្រាក់ខែបុគ្គលិក',
                'l4_id' => null,
            ],
            [
                'gl_id' => 5003,
                'gl_code' => '5212-001',
                'gl_name' => 'Office Rent',
                'gl_name_kh' => 'ការជួលការិយាល័យ',
                'l4_id' => null,
            ],
        ]);

        // Insert GL Mappings for common transactions
        DB::table('gl_maps')->insert([
            [
                'gl_map_id' => 1,
                'short_code' => 'WDEP',
                'tran_desc' => 'Withdrawal from Deposit Account',
                'debit_gl_id' => 1001, // Cash on Hand
                'credit_gl_id' => 2001, // Savings Deposits
                'created_by' => 1,
            ],
            [
                'gl_map_id' => 2,
                'short_code' => 'DDEP',
                'tran_desc' => 'Deposit to Account',
                'debit_gl_id' => 2001, // Savings Deposits
                'credit_gl_id' => 1001, // Cash on Hand
                'created_by' => 1,
            ],
            [
                'gl_map_id' => 3,
                'short_code' => 'LOAN',
                'tran_desc' => 'Loan Disbursement',
                'debit_gl_id' => 1101, // Personal Loans
                'credit_gl_id' => 1001, // Cash on Hand
                'created_by' => 1,
            ],
            [
                'gl_map_id' => 4,
                'short_code' => 'LPAY',
                'tran_desc' => 'Loan Payment',
                'debit_gl_id' => 1001, // Cash on Hand
                'credit_gl_id' => 1101, // Personal Loans
                'created_by' => 1,
            ],
            [
                'gl_map_id' => 5,
                'short_code' => 'FEES',
                'tran_desc' => 'Service Fee Collection',
                'debit_gl_id' => 1001, // Cash on Hand
                'credit_gl_id' => 4002, // Service Fee Income
                'created_by' => 1,
            ],
            [
                'gl_map_id' => 6,
                'short_code' => 'INTC',
                'tran_desc' => 'Interest Income Collection',
                'debit_gl_id' => 1001, // Cash on Hand
                'credit_gl_id' => 4001, // Loan Interest Income
                'created_by' => 1,
            ],
            [
                'gl_map_id' => 7,
                'short_code' => 'INTP',
                'tran_desc' => 'Interest Payment on Deposits',
                'debit_gl_id' => 5001, // Interest Expense
                'credit_gl_id' => 2001, // Savings Deposits
                'created_by' => 1,
            ],
            [
                'gl_map_id' => 8,
                'short_code' => 'XFER',
                'tran_desc' => 'Account Transfer',
                'debit_gl_id' => 2003, // Current Accounts
                'credit_gl_id' => 2001, // Savings Deposits
                'created_by' => 1,
            ],
        ]);
    }
}
