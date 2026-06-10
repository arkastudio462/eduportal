<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakMessage;

class KontakController extends Controller
{
    public function index()
    {
        $messages = KontakMessage::latest()->paginate(20);

        return view('admin.kontak', compact('messages'));
    }

    public function show(KontakMessage $kontakMessage)
    {
        if (! $kontakMessage->dibaca) {
            $kontakMessage->update(['dibaca' => true]);
        }

        return response()->json($kontakMessage);
    }

    public function markRead(KontakMessage $kontakMessage)
    {
        $kontakMessage->update(['dibaca' => true]);

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    public function destroy(KontakMessage $kontakMessage)
    {
        $kontakMessage->delete();

        return redirect()->route('admin.kontak')->with('success', 'Pesan berhasil dihapus.');
    }
}
