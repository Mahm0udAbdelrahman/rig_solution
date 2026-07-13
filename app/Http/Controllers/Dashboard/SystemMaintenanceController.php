<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class SystemMaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_unless((bool) Auth::user()->is_super_admin, 403);

        return view('layouts.system.maintenance', [
            'page_name' => 'System Maintenance',
            'results' => session('maintenance_results', []),
        ]);
    }

    public function run(Request $request)
    {
        abort_unless((bool) Auth::user()->is_super_admin, 403);

        $request->validate([
            'command' => ['required', 'string'],
        ]);

        $command = $request->input('command');
        $results = [];

        switch ($command) {
            case 'migrate':
                $results = $this->runArtisanStep('migrate', ['--force' => true], 'php artisan migrate --force');
                break;
            case 'storage-link':
                $results = $this->runArtisanStep('storage:link', [], 'php artisan storage:link', true);
                break;
            case 'optimize-clear':
                $results = $this->runArtisanStep('optimize:clear', [], 'php artisan optimize:clear');
                break;
            case 'view-clear':
                $results = $this->runArtisanStep('view:clear', [], 'php artisan view:clear');
                break;
            case 'view-cache':
                $results = $this->runArtisanStep('view:cache', [], 'php artisan view:cache');
                break;
            case 'all-safe':
                $results = array_merge(
                    $this->runArtisanStep('migrate', ['--force' => true], 'php artisan migrate --force'),
                    $this->runArtisanStep('storage:link', [], 'php artisan storage:link', true),
                    $this->runArtisanStep('optimize:clear', [], 'php artisan optimize:clear'),
                    $this->runArtisanStep('view:cache', [], 'php artisan view:cache')
                );
                break;
            default:
                return back()->withErrors(['command' => 'Unsupported maintenance command.']);
        }

        return redirect()
            ->route('system.maintenance.index')
            ->with('maintenance_results', $results)
            ->with('success', 'Maintenance command completed.');
    }

    protected function runArtisanStep($command, array $parameters, $label, $skipIfStorageExists = false)
    {
        if ($skipIfStorageExists && $command === 'storage:link' && file_exists(public_path('storage'))) {
            return [[
                'label' => $label,
                'output' => 'The [public/storage] link already exists.',
            ]];
        }

        Artisan::call($command, $parameters);

        return [[
            'label' => $label,
            'output' => trim(Artisan::output()),
        ]];
    }
}
