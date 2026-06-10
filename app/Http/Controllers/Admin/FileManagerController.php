<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $path = $request->query('path', '');
        $path = ltrim($path, '/');

        $disk = Storage::disk('public');

        if ($path && ! $disk->exists($path)) {
            return redirect()->route('admin.file-manager')->with('error', 'Direktori tidak ditemukan.');
        }

        $directories = [];
        $files = [];

        if ($path) {
            $parentDir = dirname($path);
            if ($parentDir === '.') {
                $parentDir = '';
            }
            $directories[] = [
                'name' => '..',
                'path' => $parentDir,
                'is_parent' => true,
            ];
        }

        foreach ($disk->directories($path) as $dir) {
            $directories[] = [
                'name' => basename($dir),
                'path' => $dir,
                'is_parent' => false,
            ];
        }

        foreach ($disk->files($path) as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);

            $files[] = [
                'name' => basename($file),
                'path' => $file,
                'size' => $disk->size($file),
                'last_modified' => $disk->lastModified($file),
                'is_image' => $isImage,
                'url' => $disk->url($file),
                'ext' => $ext,
            ];
        }

        usort($directories, fn ($a, $b) => strcasecmp($a['name'], $b['name']));
        usort($files, fn ($a, $b) => strcasecmp($a['name'], $b['name']));

        $currentPath = $path;
        $breadcrumbs = $this->breadcrumbs($path);

        return view('admin.file-manager.index', compact(
            'directories', 'files', 'currentPath', 'breadcrumbs'
        ));
    }

    public function upload(Request $request)
    {
        $path = $request->query('path', '');

        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'max:10240'],
        ]);

        $disk = Storage::disk('public');
        $uploaded = 0;

        foreach ($request->file('files') as $file) {
            $disk->putFileAs($path, $file, $file->getClientOriginalName());
            $uploaded++;
        }

        return redirect()->route('admin.file-manager', ['path' => $path])
            ->with('success', $uploaded.' file berhasil diupload.');
    }

    public function destroy(Request $request)
    {
        $filePath = $request->input('path') ?? $request->query('path');

        if (! $filePath) {
            return redirect()->route('admin.file-manager')->with('error', 'File/folder tidak ditemukan.');
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($filePath)) {
            return redirect()->route('admin.file-manager')->with('error', 'File/folder tidak ditemukan.');
        }

        if ($disk->directoryExists($filePath)) {
            $files = $disk->allFiles($filePath);
            if (! empty($files)) {
                return redirect()->route('admin.file-manager', ['path' => dirname($filePath) === '.' ? '' : dirname($filePath)])
                    ->with('error', 'Folder tidak kosong. Hapus isi folder terlebih dahulu.');
            }
            $disk->deleteDirectory($filePath);
            $message = 'Folder berhasil dihapus.';
        } else {
            $disk->delete($filePath);
            $message = 'File berhasil dihapus.';
        }

        $parentPath = dirname($filePath);
        if ($parentPath === '.') {
            $parentPath = '';
        }

        return redirect()->route('admin.file-manager', ['path' => $parentPath])
            ->with('success', $message);
    }

    public function bulkDestroy(Request $request)
    {
        $paths = $request->input('paths', []);

        if (empty($paths)) {
            return redirect()->route('admin.file-manager', ['path' => $request->query('path', '')])
                ->with('error', 'Tidak ada item yang dipilih.');
        }

        $disk = Storage::disk('public');
        $deleted = 0;
        $errors = [];

        foreach ($paths as $path) {
            if (! $disk->exists($path)) {
                $errors[] = basename($path).' (tidak ditemukan)';
                continue;
            }

            if ($disk->directoryExists($path)) {
                $files = $disk->allFiles($path);
                if (! empty($files)) {
                    $errors[] = basename($path).' (folder tidak kosong)';
                    continue;
                }
                $disk->deleteDirectory($path);
            } else {
                $disk->delete($path);
            }
            $deleted++;
        }

        $msg = $deleted.' item berhasil dihapus.';
        if (! empty($errors)) {
            $msg .= ' Gagal: '.implode(', ', $errors);
        }

        $parentPath = $request->query('path', '');
        if ($parentPath && dirname($parentPath) !== '.') {
            $parentPath = dirname($parentPath);
        } else {
            $parentPath = '';
        }

        return redirect()->route('admin.file-manager', ['path' => $parentPath])
            ->with('success', $msg);
    }

    public function createFolder(Request $request)
    {
        $currentPath = $request->query('path', '');

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_\- ]+$/'],
        ]);

        $disk = Storage::disk('public');
        $newPath = ($currentPath ? $currentPath.'/' : '').$request->name;

        if ($disk->exists($newPath)) {
            return redirect()->route('admin.file-manager', ['path' => $currentPath])
                ->with('error', 'Folder sudah ada.');
        }

        $disk->makeDirectory($newPath);

        return redirect()->route('admin.file-manager', ['path' => $currentPath])
            ->with('success', 'Folder berhasil dibuat.');
    }

    private function breadcrumbs(string $path): array
    {
        $crumbs = [['label' => 'Home', 'path' => '']];

        if (! $path) {
            return $crumbs;
        }

        $parts = explode('/', $path);
        $cumulative = '';

        foreach ($parts as $part) {
            $cumulative = $cumulative ? $cumulative.'/'.$part : $part;
            $crumbs[] = ['label' => $part, 'path' => $cumulative];
        }

        return $crumbs;
    }
}
