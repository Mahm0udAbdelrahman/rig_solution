<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function downloadDatabase()
    {
        abort_unless((bool) Auth::user()->is_super_admin, 403);

        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $dbConfig = config('database.connections.' . config('database.default'));
        $dbName = $dbConfig['database'] ?? env('DB_DATABASE', 'database');
        $fileName = sprintf('%s_backup_%s.sql', $dbName, date('Y-m-d_H-i-s'));

        return response()->streamDownload(function () use ($dbName) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "-- Database Backup: " . $dbName . "\n");
            fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "SET time_zone = \"+00:00\";\n\n");

            $tables = DB::select('SHOW TABLES');
            $dbKey = 'Tables_in_' . $dbName;
            $pdo = DB::connection()->getPdo();

            foreach ($tables as $tableObj) {
                $tableName = null;
                if (isset($tableObj->$dbKey)) {
                    $tableName = $tableObj->$dbKey;
                } else {
                    $vars = get_object_vars($tableObj);
                    $tableName = reset($vars);
                }

                if (!$tableName) {
                    continue;
                }

                fwrite($handle, "\n-- --------------------------------------------------------\n");
                fwrite($handle, "-- Table structure for table `$tableName`\n");
                fwrite($handle, "-- --------------------------------------------------------\n\n");
                fwrite($handle, "DROP TABLE IF EXISTS `$tableName`;\n");

                $createTableRes = DB::select("SHOW CREATE TABLE `$tableName`");
                if (!empty($createTableRes)) {
                    $createRow = (array) $createTableRes[0];
                    $createSql = $createRow['Create Table'] ?? ($createRow['create table'] ?? null);
                    if ($createSql) {
                        fwrite($handle, $createSql . ";\n\n");
                    }
                }

                fwrite($handle, "-- Dumping data for table `$tableName`\n\n");

                $stmt = $pdo->query("SELECT * FROM `$tableName`");
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $keys = array_map(function ($k) {
                        return "`" . str_replace("`", "``", $k) . "`";
                    }, array_keys($row));

                    $values = array_map(function ($v) use ($pdo) {
                        if ($v === null) {
                            return 'NULL';
                        }
                        return $pdo->quote($v);
                    }, array_values($row));

                    $insertSql = sprintf(
                        "INSERT INTO `%s` (%s) VALUES (%s);\n",
                        $tableName,
                        implode(', ', $keys),
                        implode(', ', $values)
                    );

                    fwrite($handle, $insertSql);
                }

                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}

