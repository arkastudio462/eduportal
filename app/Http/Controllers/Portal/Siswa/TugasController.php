<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Notifications\TugasSubmissionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TugasController extends Controller
{
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        $semuaTugas = collect();
        $submissions = collect();

        if ($siswa) {
            $semuaTugas = Tugas::with(['mapel', 'guru.user'])
                ->where('kelas_id', $siswa->kelas_id)
                ->latest()
                ->get();

            $submissions = TugasSubmission::where('siswa_id', $siswa->id)
                ->get()
                ->keyBy('tugas_id');
        }

        return view('portal-siswa.tugas', compact('semuaTugas', 'submissions', 'siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'catatan' => 'nullable|string|max:500',
            'file' => 'nullable|file|max:10240',
        ]);

        $siswa = Siswa::where('user_id', Auth::id())->first();

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas_submissions', 'public');
        }

        $existing = TugasSubmission::where('tugas_id', $validated['tugas_id'])
            ->where('siswa_id', $siswa->id)
            ->first();

        $isNew = false;
        if ($existing) {
            $updateData = ['catatan' => $validated['catatan']];
            if ($filePath) {
                $updateData['file'] = $filePath;
            }
            $existing->update($updateData);
        } else {
            TugasSubmission::create([
                'tugas_id' => $validated['tugas_id'],
                'siswa_id' => $siswa->id,
                'catatan' => $validated['catatan'],
                'file' => $filePath,
            ]);
            $isNew = true;
        }

        if ($isNew) {
            $tugas = Tugas::with('guru.user')->find($validated['tugas_id']);
            if ($tugas && $tugas->guru && $tugas->guru->user) {
                Notification::send($tugas->guru->user, new TugasSubmissionNotification(
                    $tugas->judul,
                    $siswa->user->name,
                    $tugas->id,
                ));
            }
        }

        return redirect()->route('portal-siswa.tugas')->with('success', 'Tugas berhasil dikumpulkan.');
    }
}
