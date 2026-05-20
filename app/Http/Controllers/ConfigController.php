<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\PublicHoliday;
use App\Models\NonWorkingDay;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\DataTables;

class ConfigController extends Controller
{
  // System Config
  public function index()
  {
    $configs = Config::all();
    return view('config.index', compact('configs'));
  }

  public function updateConfig(Request $request, $id)
  {
    $config = Config::findOrFail($id);

    $validated = $request->validate([
      'config_value' => 'required|string|max:255',
      'remark' => 'nullable|string|max:255'
    ]);

    $config->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Configuration updated successfully',
      'data' => $config
    ]);
  }

  public function storeConfig(Request $request)
  {
    $validated = $request->validate([
      'config_id' => 'required|integer|unique:configs,CONFIG_ID',
      'config_name' => 'required|string|max:255',
      'config_value' => 'required|string|max:255',
      'remark' => 'nullable|string|max:255'
    ]);

    $config = Config::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Configuration created successfully',
      'data' => $config
    ]);
  }

  // Public Holidays
  public function holidaysIndex()
  {
    return view('config.holidays');
  }

  public function getHolidaysData()
  {
    $holidays = PublicHoliday::select('public_holidays.*');

    return DataTables::of($holidays)
      ->addIndexColumn()
      ->addColumn('repeat_text', function ($row) {
        $repeats = ['m' => 'Monthly', 'y' => 'Yearly'];
        return $repeats[$row->repeat] ?? 'None';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->holiday_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->holiday_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function storeHoliday(Request $request)
  {
    $validated = $request->validate([
      'holiday_date' => 'required|date',
      'repeat' => 'nullable|in:m,y',
      'description' => 'nullable|string|max:250'
    ]);

    $holiday = PublicHoliday::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Public holiday created successfully',
      'data' => $holiday
    ]);
  }

  public function updateHoliday(Request $request, $id)
  {
    $holiday = PublicHoliday::findOrFail($id);

    $validated = $request->validate([
      'holiday_date' => 'required|date',
      'repeat' => 'nullable|in:m,y',
      'description' => 'nullable|string|max:250'
    ]);

    $holiday->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Public holiday updated successfully',
      'data' => $holiday
    ]);
  }

  public function showHoliday($id)
  {
    $holiday = PublicHoliday::findOrFail($id);
    return response()->json($holiday);
  }

  public function destroyHoliday($id)
  {
    $holiday = PublicHoliday::findOrFail($id);
    $holiday->delete();

    return response()->json([
      'success' => true,
      'message' => 'Public holiday deleted successfully'
    ]);
  }

  // Non-Working Days
  public function nonWorkingDaysIndex()
  {
    $days = NonWorkingDay::all();
    return view('config.non-working-days', compact('days'));
  }

  public function updateNonWorkingDays(Request $request)
  {
    $validated = $request->validate([
      'days' => 'array',
      'days.*' => 'string|max:50'
    ]);

    // Clear existing and insert new
    NonWorkingDay::truncate();

    foreach ($validated['days'] ?? [] as $day) {
      NonWorkingDay::create(['non_work_day' => $day]);
    }

    return response()->json([
      'success' => true,
      'message' => 'Non-working days updated successfully'
    ]);
  }

  // Modules
  public function modulesIndex()
  {
    return view('config.modules');
  }

  public function getModulesData()
  {
    $modules = Module::select('modules.*');

    return DataTables::of($modules)
      ->addColumn('type_text', function ($row) {
        $types = [1 => 'All', 2 => 'Branch', 3 => 'Head Office'];
        return $types[$row->type] ?? 'Unknown';
      })
      ->addColumn('status_badge', function ($row) {
        return $row->status == 0
          ? '<span class="badge bg-success">Active</span>'
          : '<span class="badge bg-danger">Inactive</span>';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->module_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['status_badge', 'action'])
      ->make(true);
  }

  public function storeModule(Request $request)
  {
    $validated = $request->validate([
      'module_id' => 'required|integer|unique:modules,MODULE_ID',
      'module' => 'required|string|max:50',
      'control_name' => 'required|string|max:25',
      'url' => 'nullable|string|max:50',
      'type' => 'required|in:1,2,3',
      'status' => 'required|in:0,1'
    ]);

    $module = Module::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Module created successfully',
      'data' => $module
    ]);
  }

  public function updateModule(Request $request, $id)
  {
    $module = Module::findOrFail($id);

    $validated = $request->validate([
      'module' => 'required|string|max:50',
      'control_name' => 'required|string|max:25',
      'url' => 'nullable|string|max:50',
      'type' => 'required|in:1,2,3',
      'status' => 'required|in:0,1'
    ]);

    $module->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Module updated successfully',
      'data' => $module
    ]);
  }

  // Get all configs
  public function allConfigs()
  {
    $configs = Config::all();
    return response()->json([
      'success' => true,
      'data' => $configs
    ]);
  }

  // Save all configs at once
  public function saveAllConfigs(Request $request)
  {
    $validated = $request->validate([
      'settings' => 'required|array',
      'settings.*.name' => 'required|string|max:255',
      'settings.*.value' => 'nullable|string|max:255'
    ]);

    $updated = 0;
    $created = 0;

    foreach ($validated['settings'] as $setting) {
      $config = Config::where('config_name', $setting['name'])->first();

      if ($config) {
        $config->update(['config_value' => $setting['value']]);
        $updated++;
      } else {
        // Get next available CONFIG_ID
        $maxId = Config::max('config_id') ?? 0;
        Config::create([
          'config_id' => $maxId + 1,
          'config_name' => $setting['name'],
          'config_value' => $setting['value']
        ]);
        $created++;
      }
    }

    return response()->json([
      'success' => true,
      'message' => "Settings saved successfully. Updated: {$updated}, Created: {$created}"
    ]);
  }

  // Show specific config by name
  public function showConfig($name)
  {
    $config = Config::where('config_name', $name)->first();

    if (!$config) {
      return response()->json([
        'success' => false,
        'message' => 'Configuration not found'
      ], 404);
    }

    return response()->json([
      'success' => true,
      'data' => $config
    ]);
  }

  // Save OAuth credentials to .env file
  public function saveOAuthCredentials(Request $request)
  {
    $validated = $request->validate([
      'credentials' => 'required|array',
      'credentials.*' => 'nullable|string|max:500'
    ]);

    $envPath = base_path('.env');

    if (!file_exists($envPath)) {
      return response()->json([
        'success' => false,
        'message' => '.env file not found'
      ], 500);
    }

    try {
      $envContent = file_get_contents($envPath);
      $allowedKeys = [
        'google_client_id',
        'google_client_secret',
        'github_client_id',
        'github_client_secret',
        'twitter_client_id',
        'twitter_client_secret',
        'telegram_bot_name',
        'telegram_bot_token'
      ];

      foreach ($validated['credentials'] as $key => $value) {
        // Only allow specific OAuth keys for security
        if (!in_array($key, $allowedKeys)) {
          continue;
        }

        $value = $value ?? '';

        // Escape special characters in value
        $escapedValue = str_replace('"', '\\"', $value);

        // Check if key exists in .env
        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $envContent)) {
          // Update existing key
          $envContent = preg_replace($pattern, "{$key}=\"{$escapedValue}\"", $envContent);
        } else {
          // Add new key (append to OAuth section or end of file)
          $envContent .= "\n{$key}=\"{$escapedValue}\"";
        }
      }

      // Write back to .env file
      file_put_contents($envPath, $envContent);

      // Clear config cache
      Artisan::call('config:clear');

      return response()->json([
        'success' => true,
        'message' => 'OAuth credentials saved successfully. Please refresh to see updated status.'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to save OAuth credentials: ' . $e->getMessage()
      ], 500);
    }
  }
}
