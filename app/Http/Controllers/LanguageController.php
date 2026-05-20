<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LanguageController extends Controller
{
    /**
     * Switch application language
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request)
    {
        // Validate the request
        $request->validate([
            'locale' => 'required|string|max:5'
        ]);

        // Get locale from request
        $locale = $request->input('locale');

        // Get supported locales from config
        $supportedLocales = array_keys(config('app.supported_locales'));

        // Check if the requested locale is supported
        if (!in_array($locale, $supportedLocales)) {
            return redirect()->back()->with('error', 'Unsupported language');
        }

        // Set the application locale
        App::setLocale($locale);

        // Store the locale in session for persistence
        Session::put('locale', $locale);

        // Redirect back to the previous page
        return redirect()->back()->with('success', 'Language changed successfully');
    }

    /**
     * Get current language information
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function current()
    {
        $currentLocale = App::getLocale();
        $supportedLocales = config('app.supported_locales');

        return response()->json([
            'current_locale' => $currentLocale,
            'current_language' => $supportedLocales[$currentLocale] ?? null,
            'supported_locales' => $supportedLocales,
        ]);
    }

    /**
     * Get a valid user ID that exists in user_logins table
     */
    private function getValidUserId()
    {
        $authId = Auth::id();
        if ($authId && \App\Models\UserLogin::find($authId)) {
            return $authId;
        }
        return 1; // Default fallback user
    }

    /**
     * Display a listing of languages
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('languages.index');
    }

    /**
     * Get languages data for DataTables
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {
        $languages = Language::select('languages.*');

        return DataTables::of($languages)
            ->addColumn('status', function ($row) {
                $badge = $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';

                if ($row->is_default) {
                    $badge .= ' <span class="badge bg-primary ms-1">Default</span>';
                }

                return $badge;
            })
            ->addColumn('display', function ($row) {
                return $row->flag . ' ' . $row->name . ' (' . $row->native_name . ')';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group btn-group-sm" role="group">';
                $btn .= '<button type="button" class="btn btn-info view-btn" data-id="' . $row->language_id . '" title="View"><i class="fas fa-eye"></i></button>';
                $btn .= '<button type="button" class="btn btn-primary edit-btn" data-id="' . $row->language_id . '" title="Edit"><i class="fas fa-edit"></i></button>';

                // Don't allow deleting the default language
                if (!$row->is_default) {
                    $btn .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->language_id . '" title="Delete"><i class="fas fa-trash"></i></button>';
                }

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status', 'action', 'display'])
            ->make(true);
    }

    /**
     * Store a newly created language
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:5|unique:languages,code',
            'native_name' => 'nullable|string|max:50',
            'flag' => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
            'is_default' => 'required|boolean',
            'sort_order' => 'required|integer|min:0'
        ]);

        $validated['created_by'] = $this->getValidUserId();
        $validated['created_date'] = now();

        // If this language is set as default, unset other defaults
        if ($validated['is_default']) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        $language = Language::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Language created successfully',
            'data' => $language
        ]);
    }

    /**
     * Display the specified language
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $language = Language::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $language
        ]);
    }

    /**
     * Update the specified language
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:5|unique:languages,code,' . $id . ',language_id',
            'native_name' => 'nullable|string|max:50',
            'flag' => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
            'is_default' => 'required|boolean',
            'sort_order' => 'required|integer|min:0'
        ]);

        $validated['modify_by'] = $this->getValidUserId();
        $validated['modify_date'] = now();

        // If this language is set as default, unset other defaults
        if ($validated['is_default'] && !$language->is_default) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        // Prevent disabling the default language
        if ($language->is_default && !$validated['is_active']) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot disable the default language'
            ], 400);
        }

        $language->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Language updated successfully',
            'data' => $language
        ]);
    }

    /**
     * Remove the specified language
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $language = Language::findOrFail($id);

        // Prevent deleting the default language
        if ($language->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the default language'
            ], 400);
        }

        $language->delete();

        return response()->json([
            'success' => true,
            'message' => 'Language deleted successfully'
        ]);
    }

    /**
     * Get all active languages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $languages = Language::active()->ordered()->get();

        return response()->json($languages);
    }
}

