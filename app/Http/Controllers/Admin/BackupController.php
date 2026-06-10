<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        Storage::disk('local')->makeDirectory('backups');

        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(fn ($f) => str_ends_with($f, '.sqlite'))
            ->map(fn ($f) => [
                'name' => basename($f),
                'path' => $f,
                'size' => Storage::disk('local')->size($f),
                'last_modified' => Storage::disk('local')->lastModified($f),
            ])
            ->sortByDesc('last_modified')
            ->values();

        return view('admin.backup.index', compact('backups'));
    }

    public function create()
    {
        $exitCode = Artisan::call('db:backup');

        if ($exitCode === 0) {
            return redirect()->route('admin.backup')->with('success', 'Backup database berhasil dibuat.');
        }

        return redirect()->route('admin.backup')->with('error', 'Gagal membuat backup database.');
    }

    public function download($filename)
    {
        $path = 'backups/'.$filename;

        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->download($path);
    }

    public function destroy($filename)
    {
        $path = 'backups/'.$filename;

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }

        return redirect()->route('admin.backup')->with('success', 'Backup berhasil dihapus.');
    }

    public function restore($filename)
    {
        $backupPath = Storage::disk('local')->path('backups/'.$filename);

        if (! file_exists($backupPath)) {
            return redirect()->route('admin.backup')->with('error', 'File backup tidak ditemukan.');
        }

        $dbPath = database_path('database.sqlite');

        if (! is_writable($dbPath)) {
            return redirect()->route('admin.backup')->with('error', 'File database tidak dapat ditulis.');
        }

        if (copy($backupPath, $dbPath)) {
            return redirect()->route('admin.backup')->with('success', 'Database berhasil direstore dari backup.');
        }

        return redirect()->route('admin.backup')->with('error', 'Gagal me-restore database.');
    }
}
