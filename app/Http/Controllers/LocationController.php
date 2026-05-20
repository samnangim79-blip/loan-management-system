<?php
// app/Http/Controllers/LocationController.php
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Province;
use App\Models\District;
use App\Models\Commune;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    // ==========================================
    // COUNTRIES
    // ==========================================

    public function allCountries()
    {
        $countries = Country::select('country_id', 'country', 'country_kh')
            ->orderBy('country')
            ->get();

        return response()->json(['success' => true, 'data' => $countries]);
    }

    // ==========================================
    // PROVINCES
    // ==========================================

    public function provincesIndex()
    {
        return view('locations.provinces');
    }

    public function getProvincesData()
    {
        $provinces = Province::withCount(['districts']);

        return DataTables::of($provinces)
            ->addColumn('action', function ($row) {
                $actions = '<div class="btn-group btn-group-sm">';
                $actions .= '<button type="button" class="btn btn-info view-btn" data-id="' . $row->id . '" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button type="button" class="btn btn-primary edit-btn" data-id="' . $row->id . '" title="Edit"><i class="fas fa-edit"></i></button>';
                $actions .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="fas fa-trash"></i></button>';
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function allProvinces()
    {
        $provinces = Province::select('id', 'name_en', 'name_kh', 'country_id')
            ->orderBy('name_en')
            ->get();

        return response()->json(['success' => true, 'data' => $provinces]);
    }

    public function getProvinces()
    {
        $provinces = Province::select('id', 'name_en', 'name_kh')
            ->orderBy('name_en')
            ->get();

        return response()->json($provinces);
    }

    public function showProvince($id)
    {
        $province = Province::withCount(['districts'])->find($id);

        if (!$province) {
            return response()->json(['success' => false, 'message' => 'Province not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $province]);
    }

    public function storeProvince(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:100',
            'name_kh' => 'nullable|string|max:100',
            'country_id' => 'nullable|integer',
        ]);

        $province = Province::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Province created successfully',
            'data' => $province
        ]);
    }

    public function updateProvince(Request $request, $id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['success' => false, 'message' => 'Province not found'], 404);
        }

        $validated = $request->validate([
            'name_en' => 'required|string|max:100',
            'name_kh' => 'nullable|string|max:100',
            'country_id' => 'nullable|integer',
        ]);

        $province->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Province updated successfully',
            'data' => $province
        ]);
    }

    public function destroyProvince($id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json(['success' => false, 'message' => 'Province not found'], 404);
        }

        // Check if province has districts
        if ($province->districts()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete province with existing districts'
            ], 400);
        }

        $province->delete();

        return response()->json([
            'success' => true,
            'message' => 'Province deleted successfully'
        ]);
    }

    public function provincesByCountry($countryId)
    {
        $provinces = Province::where('country_id', $countryId)
            ->select('id', 'name_en', 'name_kh', 'country_id')
            ->orderBy('name_en')
            ->get();

        return response()->json(['success' => true, 'data' => $provinces]);
    }

    // ==========================================
    // DISTRICTS
    // ==========================================

    public function getDistrictsData(Request $request)
    {
        $districts = District::with('province');

        if ($request->has('province_id') && $request->province_id) {
            $districts->where('province_id', $request->province_id);
        }

        return DataTables::of($districts)
            ->addColumn('province_name', function ($row) {
                return $row->province ? $row->province->PROVINCE : '-';
            })
            ->addColumn('action', function ($row) {
                $actions = '<div class="btn-group btn-group-sm">';
                $actions .= '<button type="button" class="btn btn-info view-btn" data-id="' . $row->DISTRICT_ID . '" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button type="button" class="btn btn-primary edit-btn" data-id="' . $row->DISTRICT_ID . '" title="Edit"><i class="fas fa-edit"></i></button>';
                $actions .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->DISTRICT_ID . '" title="Delete"><i class="fas fa-trash"></i></button>';
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getDistricts($provinceId)
    {
        $districts = District::where('province_id', $provinceId)
            ->select('district_id', 'district', 'district_kh')
            ->orderBy('district')
            ->get();

        return response()->json($districts);
    }

    public function districtsByProvince($provinceId)
    {
        $districts = District::where('province_id', $provinceId)
            ->select('id', 'name_en', 'name_kh', 'province_id')
            ->orderBy('name_en')
            ->get();

        return response()->json(['success' => true, 'data' => $districts]);
    }

    public function showDistrict($id)
    {
        $district = District::with('province')->find($id);

        if (!$district) {
            return response()->json(['success' => false, 'message' => 'District not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $district]);
    }

    public function storeDistrict(Request $request)
    {
        $validated = $request->validate([
            'district' => 'required|string|max:100',
            'district_kh' => 'nullable|string|max:100',
            'province_id' => 'required|integer|exists:provinces,PROVINCE_ID',
        ]);

        $validated['created_by'] = Auth::id() ?? 1;
        $validated['created_date'] = now();

        $district = District::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'District created successfully',
            'data' => $district
        ]);
    }

    public function updateDistrict(Request $request, $id)
    {
        $district = District::find($id);

        if (!$district) {
            return response()->json(['success' => false, 'message' => 'District not found'], 404);
        }

        $validated = $request->validate([
            'district' => 'required|string|max:100',
            'district_kh' => 'nullable|string|max:100',
            'province_id' => 'required|integer|exists:provinces,PROVINCE_ID',
        ]);

        $validated['modify_by'] = Auth::id() ?? 1;
        $validated['modify_date'] = now();

        $district->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'District updated successfully',
            'data' => $district
        ]);
    }

    public function destroyDistrict($id)
    {
        $district = District::find($id);

        if (!$district) {
            return response()->json(['success' => false, 'message' => 'District not found'], 404);
        }

        // Check if district has communes
        if ($district->communes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete district with existing communes'
            ], 400);
        }

        $district->delete();

        return response()->json([
            'success' => true,
            'message' => 'District deleted successfully'
        ]);
    }

    // ==========================================
    // COMMUNES
    // ==========================================

    public function getCommunesData(Request $request)
    {
        $communes = Commune::with(['district.province']);

        if ($request->has('district_id') && $request->district_id) {
            $communes->where('district_id', $request->district_id);
        }

        return DataTables::of($communes)
            ->addColumn('district_name', function ($row) {
                return $row->district ? $row->district->DISTRICT : '-';
            })
            ->addColumn('province_name', function ($row) {
                return $row->district && $row->district->province ? $row->district->province->PROVINCE : '-';
            })
            ->addColumn('action', function ($row) {
                $actions = '<div class="btn-group btn-group-sm">';
                $actions .= '<button type="button" class="btn btn-info view-btn" data-id="' . $row->COMMUNE_ID . '" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button type="button" class="btn btn-primary edit-btn" data-id="' . $row->COMMUNE_ID . '" title="Edit"><i class="fas fa-edit"></i></button>';
                $actions .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->COMMUNE_ID . '" title="Delete"><i class="fas fa-trash"></i></button>';
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getCommunes($districtId)
    {
        $communes = Commune::where('district_id', $districtId)
            ->select('commune_id', 'commune', 'commune_kh')
            ->orderBy('commune')
            ->get();

        return response()->json($communes);
    }

    public function communesByDistrict($districtId)
    {
        $communes = Commune::where('district_id', $districtId)
            ->select('id', 'name_en', 'name_kh', 'district_id')
            ->orderBy('name_en')
            ->get();

        return response()->json(['success' => true, 'data' => $communes]);
    }

    public function showCommune($id)
    {
        $commune = Commune::with(['district.province'])->find($id);

        if (!$commune) {
            return response()->json(['success' => false, 'message' => 'Commune not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $commune]);
    }

    public function storeCommune(Request $request)
    {
        $validated = $request->validate([
            'commune' => 'required|string|max:100',
            'commune_kh' => 'nullable|string|max:100',
            'district_id' => 'required|integer|exists:districts,DISTRICT_ID',
        ]);

        $validated['created_by'] = Auth::id() ?? 1;
        $validated['created_date'] = now();

        $commune = Commune::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Commune created successfully',
            'data' => $commune
        ]);
    }

    public function updateCommune(Request $request, $id)
    {
        $commune = Commune::find($id);

        if (!$commune) {
            return response()->json(['success' => false, 'message' => 'Commune not found'], 404);
        }

        $validated = $request->validate([
            'commune' => 'required|string|max:100',
            'commune_kh' => 'nullable|string|max:100',
            'district_id' => 'required|integer|exists:districts,DISTRICT_ID',
        ]);

        $validated['modify_by'] = Auth::id() ?? 1;
        $validated['modify_date'] = now();

        $commune->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Commune updated successfully',
            'data' => $commune
        ]);
    }

    public function destroyCommune($id)
    {
        $commune = Commune::find($id);

        if (!$commune) {
            return response()->json(['success' => false, 'message' => 'Commune not found'], 404);
        }

        // Check if commune has villages
        if ($commune->villages()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete commune with existing villages'
            ], 400);
        }

        $commune->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commune deleted successfully'
        ]);
    }

    // ==========================================
    // VILLAGES
    // ==========================================

    public function getVillagesData(Request $request)
    {
        $villages = Village::with(['commune.district.province']);

        if ($request->has('commune_id') && $request->commune_id) {
            $villages->where('commune_id', $request->commune_id);
        }

        return DataTables::of($villages)
            ->addColumn('commune_name', function ($row) {
                return $row->commune ? $row->commune->COMMUNE : '-';
            })
            ->addColumn('district_name', function ($row) {
                return $row->commune && $row->commune->district ? $row->commune->district->DISTRICT : '-';
            })
            ->addColumn('province_name', function ($row) {
                return $row->commune && $row->commune->district && $row->commune->district->province
                    ? $row->commune->district->province->PROVINCE : '-';
            })
            ->addColumn('action', function ($row) {
                $actions = '<div class="btn-group btn-group-sm">';
                $actions .= '<button type="button" class="btn btn-info view-btn" data-id="' . $row->VILLAGE_ID . '" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button type="button" class="btn btn-primary edit-btn" data-id="' . $row->VILLAGE_ID . '" title="Edit"><i class="fas fa-edit"></i></button>';
                $actions .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->VILLAGE_ID . '" title="Delete"><i class="fas fa-trash"></i></button>';
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getVillages($communeId)
    {
        $villages = Village::where('commune_id', $communeId)
            ->select('village_id', 'village', 'village_kh')
            ->orderBy('village')
            ->get();

        return response()->json($villages);
    }

    public function villagesByCommune($communeId)
    {
        $villages = Village::where('commune_id', $communeId)
            ->select('id', 'name_en', 'name_kh', 'commune_id')
            ->orderBy('name_en')
            ->get();

        return response()->json(['success' => true, 'data' => $villages]);
    }

    public function searchVillages(Request $request)
    {
        $query = $request->get('q');

        $villages = Village::with(['commune.district.province'])
            ->where('name_en', 'like', "%{$query}%")
            ->orWhere('name_kh', 'like', "%{$query}%")
            ->limit(50)
            ->get()
            ->map(function ($village) {
                return [
                    'id' => $village->id,
                    'name_en' => $village->name_en,
                    'name_kh' => $village->name_kh,
                    'full_address' => $village->getFullAddress()
                ];
            });

        return response()->json($villages);
    }

    public function showVillage($id)
    {
        $village = Village::with(['commune.district.province'])->find($id);

        if (!$village) {
            return response()->json(['success' => false, 'message' => 'Village not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $village]);
    }

    public function storeVillage(Request $request)
    {
        $validated = $request->validate([
            'village' => 'required|string|max:100',
            'village_kh' => 'nullable|string|max:100',
            'commune_id' => 'required|integer|exists:communes,COMMUNE_ID',
        ]);

        $validated['created_by'] = Auth::id() ?? 1;
        $validated['created_date'] = now();

        $village = Village::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Village created successfully',
            'data' => $village
        ]);
    }

    public function updateVillage(Request $request, $id)
    {
        $village = Village::find($id);

        if (!$village) {
            return response()->json(['success' => false, 'message' => 'Village not found'], 404);
        }

        $validated = $request->validate([
            'village' => 'required|string|max:100',
            'village_kh' => 'nullable|string|max:100',
            'commune_id' => 'required|integer|exists:communes,COMMUNE_ID',
        ]);

        $validated['modify_by'] = Auth::id() ?? 1;
        $validated['modify_date'] = now();

        $village->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Village updated successfully',
            'data' => $village
        ]);
    }

    public function destroyVillage($id)
    {
        $village = Village::find($id);

        if (!$village) {
            return response()->json(['success' => false, 'message' => 'Village not found'], 404);
        }

        $village->delete();

        return response()->json([
            'success' => true,
            'message' => 'Village deleted successfully'
        ]);
    }
}
