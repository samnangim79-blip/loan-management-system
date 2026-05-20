# Group Seeder Usage Examples

## Basic Seeding

The GroupSeeder has been created and includes:

### Seeded Data

-   **8 loan groups** with different types (Small Business, Agriculture, Women Empowerment, etc.)
-   **26 group details** linking groups to loan contracts
-   Groups are associated with existing loan contracts (LN25-0010 through LN25-0035)

### Running the Seeder

```bash
# Run only the Group seeder
php artisan db:seed --class=GroupSeeder

# Run all seeders (includes Group seeder)
php artisan db:seed
```

## Using the Group Factory

The GroupFactory allows you to generate additional test data:

### Basic Factory Usage

```php
// In tinker or your tests
use App\Models\Group;

// Create a single group
$group = Group::factory()->create();

// Create multiple groups
$groups = Group::factory()->count(5)->create();

// Create recent groups (issued within last 30 days)
$recentGroups = Group::factory()->recent()->count(3)->create();

// Create groups issued between specific dates
$oldGroups = Group::factory()->issuedBetween('2024-01-01', '2024-06-30')->count(2)->create();
```

### Testing Factory

```bash
# Test the factory in tinker
php artisan tinker
>>> use App\Models\Group;
>>> Group::factory()->create();
>>> Group::factory()->recent()->count(3)->create();
```

## Database Structure

### Groups Table

-   `group_id` - Primary key
-   `group_name` - Group name (max 50 chars)
-   `date_issue` - Group creation/issue date
-   `added_by` - User who created the group
-   `added_date` - Creation date
-   `updated_by` - User who last updated (nullable)
-   `updated_date` - Last update date (nullable)

### Group Details Table

-   `group_detail_id` - Primary key
-   `group_id` - Foreign key to groups table
-   `contract_no` - Loan contract number (max 15 chars)

## Sample Groups Created

1. **Small Business Group A** - 3 contracts
2. **Agriculture Cooperative Group** - 4 contracts
3. **Women Empowerment Group** - 5 contracts
4. **Youth Entrepreneur Group** - 2 contracts
5. **Rural Development Group B** - 3 contracts
6. **Market Vendor Association** - 4 contracts
7. **Handicraft Producer Group** - 2 contracts
8. **Rice Farmer Collective** - 3 contracts

## Verification

```bash
# Check group count
php artisan tinker --execute="echo 'Groups: ' . App\Models\Group::count();"

# View groups with details
php artisan tinker --execute="use App\Models\Group; Group::with('details')->get()->each(function(\$group) { echo \$group->group_name . ' - ' . \$group->details->count() . ' contracts' . PHP_EOL; });"
```

The groups page at http://127.0.0.1:8000/groups should now display all the seeded groups with proper DataTable functionality.
