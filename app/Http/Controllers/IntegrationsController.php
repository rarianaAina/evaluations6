<?php
namespace App\Http\Controllers;

use App\Models\Integration;
use App\Services\Storage\Authentication\DropboxAuthenticator;
use App\Services\Storage\Authentication\GoogleDriveAuthenticator;
use Illuminate\Http\Request;

class IntegrationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('is.demo');
        $this->middleware('user.is.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $billing_integration = Integration::whereApiType('billing')->first();
        $filesystem_integration = Integration::whereApiType('file')->first();

        return view('integrations.index')
        ->with('billing_integration', $billing_integration)
        ->with('filesystem_integration', $filesystem_integration)
        ->with('google_drive_auth_url', null)
        ->with('dropbox_auth_url', null);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'api_type' => 'required|string',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
        ]);

        $input = $request->all();

        // Vérifier si une intégration du même type existe déjà
        $existing = Integration::where('api_type', $request->api_type)->first();

        if ($existing) {
            $existing->update($input);
        } else {
            Integration::create($input);
        }

        return redirect()->route('integrations.index')->with('success', 'Intégration mise à jour avec succès.');
    }

}
