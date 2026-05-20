# Laravel 12 Loan Management System - Database Seeders Documentation

## Overview

This document provides a comprehensive overview of the database seeders created for the Laravel 12 Loan Management System. All seeders have been successfully implemented and tested.

## Completed Seeders (All Successfully Running)

### 1. **Core System Data**

-   **UserSeeder**: 10 Laravel users for authentication
-   **CountrySeeder**: 10 countries with English and Khmer names
-   **Geographic Data**:
    -   **ProvincesTableSeeder**: Provincial data
    -   **DistrictsTableSeeder**: District data
    -   **CommunesTableSeeder**: Commune data
    -   **VillagesTableSeeder**: Village data

### 2. **Organizational Structure**

-   **BranchSeeder**: 5 branch locations
-   **StaffSeeder**: 8 staff members across different positions
-   **AccessProfileSeeder**: 7 access profiles with different permission levels
-   **ModuleSeeder**: 15 system modules for access control
-   **AccessProfileDetailSeeder**: Module access mappings for each profile
-   **UserLoginSeeder**: 8 user login credentials (legacy system)

### 3. **Financial System Setup**

-   **GlSeeder**: Complete General Ledger structure (4-level hierarchy)

    -   GL Level 1: 5 main categories (Assets, Liabilities, Equity, Income, Expenses)
    -   GL Level 2: 13 subcategories
    -   GL Level 3: 15 detailed categories
    -   GL Level 4: 10 specific accounts
    -   20+ GL accounts with USD/KHR variants
    -   8 GL mappings for common transactions

-   **CurrencySeeder**: Multiple currencies with exchange rates
-   **AccountTypesSeeder**: Various account types for different purposes
-   **ConfigSeeder**: System configuration settings

### 4. **Loan System Data**

-   **PurposeLoanSeeder**: Loan purposes (business, personal, agriculture, etc.)
-   **PaymentFrequencySeeder**: Payment schedules (weekly, monthly, quarterly, etc.)
-   **CollateralTypeSeeder**: Collateral types for loan security
-   **NationalitySeeder**: Nationality reference data

### 5. **Fixed Deposit System**

-   **FdSeeder**: Complete fixed deposit setup
    -   5 FD options (rollover, interest distribution, etc.)
    -   7 FD terms (1 month to 36 months) with varying interest rates
    -   5 sample FD certificates
    -   FD transaction status records
    -   Sample future deposits

### 6. **Sample Transactional Data**

-   **CustomerInfoSeeder**: 5 customer profiles with complete information
-   **AccountInfoSeeder**: 6 customer accounts of different types
-   **LoanScheduleSeeder**: 6 loan contracts with varying terms
-   **CollateralSeeder**: Collateral records linked to loan schedules
-   **CashMgtSeeder**: 10 cash management transactions showing cash flow
-   **PenddingCashTransferSeeder**: 15 pending cash transfers (8 pending, 7 received)
-   **TransSeeder**: 20 comprehensive transactions across 12 transaction types

## Database Statistics After Seeding

### User Data

-   **10 Laravel Users**: Full authentication ready
-   **8 User Logins**: Legacy system credentials
-   **8 Staff Members**: Across 3 branches
-   **7 Access Profiles**: Manager to Cashier levels

### Financial Data

-   **5 Branches**: Fully operational locations
-   **5+ Currencies**: Including USD, KHR with exchange rates
-   **20+ GL Accounts**: Complete chart of accounts
-   **8 GL Mappings**: For automated transaction posting

### Customer & Account Data

-   **5 Customers**: Complete profiles with addresses
-   **6 Accounts**: Various types (savings, current, loans)
-   **6 Loan Contracts**: Different amounts and terms
-   **Multiple Collaterals**: Secured loan collateral records

### Transaction Data

-   **35 Total Transactions**: Across all systems
    -   20 from TransSeeder (12 different types)
    -   15 from PenddingCashTransferSeeder
    -   10 from CashMgtSeeder
-   **$472,067 Total Transaction Volume**
-   **12 Transaction Types**: Deposits, loans, withdrawals, fees, etc.

### Fixed Deposit Data

-   **5 FD Certificates**: Various terms and amounts
-   **7 FD Term Options**: 1 month to 36 months
-   **5 Maturity Options**: Different rollover strategies

## Login Credentials for Testing

### Laravel Users (for main authentication)

-   **Admin**: admin@loanmgt.com / admin123
-   **Demo**: demo@loanmgt.com / demo123
-   **Manager**: jmanager@loanmgt.com / password123
-   **Loan Officer**: sloan@loanmgt.com / password123
-   **Teller**: mteller@loanmgt.com / password123

### Legacy User Logins (for internal system)

-   **Manager**: jmanager / password123
-   **Supervisor**: dsupervisor / password123
-   **Loan Officer**: sloan / password123
-   **Teller**: mteller / password123
-   **Cashier**: lcashier / password123

## Access Profile Capabilities

| Profile        | Deposit Limit | Withdrawal Limit | Loan Limit  | Non-Cash Limit |
| -------------- | ------------- | ---------------- | ----------- | -------------- |
| Manager        | $1,000,000    | $500,000         | $10,000,000 | $200,000       |
| Supervisor     | $500,000      | $250,000         | $5,000,000  | $100,000       |
| Loan Officer   | $200,000      | $100,000         | $2,000,000  | $50,000        |
| Teller         | $100,000      | $50,000          | $0          | $25,000        |
| Cashier        | $50,000       | $25,000          | $0          | $10,000        |
| Credit Analyst | $0            | $0               | $3,000,000  | $0             |
| Admin Officer  | $75,000       | $37,500          | $500,000    | $15,000        |

## Module Access Matrix

Each profile has access to different system modules:

-   **Manager**: Full access to all 15 modules
-   **Supervisor**: 13 modules (excluding system configuration)
-   **Loan Officer**: 7 modules (customer, account, loan, collateral, interest, reports, audit)
-   **Teller**: 5 modules (customer, account, cash, deposit, passbook, audit)
-   **Cashier**: 5 modules (cash, deposit, cheque, passbook, audit)
-   **Credit Analyst**: 6 modules (customer, loan, collateral, interest, reports, audit)
-   **Admin Officer**: 7 modules (customer, account, cheque, passbook, reports, user, config, audit)

## Testing Scenarios Available

### 1. **User Authentication & Authorization**

-   Test different user roles and permissions
-   Module access control verification
-   Cash limit enforcement testing

### 2. **Customer Management**

-   Customer registration and profile management
-   Address verification with geographic data
-   Customer search and filtering

### 3. **Account Operations**

-   Account opening for different types
-   Balance inquiries and transaction history
-   Account status management

### 4. **Loan Processing**

-   Loan application and approval workflow
-   Collateral management and verification
-   Payment schedule generation and tracking

### 5. **Cash Operations**

-   Cash transfers between branches
-   Cash management and reconciliation
-   Currency exchange operations

### 6. **Fixed Deposits**

-   FD certificate issuance
-   Interest calculation and maturity handling
-   Rollover and withdrawal processing

### 7. **Financial Reporting**

-   Transaction reporting across all types
-   Cash flow analysis
-   Loan portfolio analysis
-   Balance sheet and P&L data

### 8. **Operational Testing**

-   Multi-branch operations
-   Multi-currency transactions
-   Automated GL posting verification
-   Audit trail functionality

## Database Seeder Execution

To run all seeders:

```bash
# Fresh migration with all seeders
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=SpecificSeederName

# Run without migration (if data exists)
php artisan db:seed
```

## Next Steps

The loan management system now has comprehensive test data for:

-   ✅ Complete user authentication and authorization
-   ✅ Full customer and account management
-   ✅ Operational loan processing workflows
-   ✅ Cash management and transfers
-   ✅ Fixed deposit operations
-   ✅ Financial transaction processing
-   ✅ Multi-branch and multi-currency support
-   ✅ Complete GL integration
-   ✅ Audit trails and reporting data

The system is ready for:

-   Feature development and testing
-   User interface development
-   Business workflow implementation
-   Financial reporting development
-   Performance testing with realistic data volumes
