<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TracerStudy;

class TracerStudyController extends Controller
{
    public function index()
    {
        $tracerStudy = TracerStudy::latest()->paginate(20);

        return view('admin.tracer-study', compact('tracerStudy'));
    }

    public function destroy(TracerStudy $tracerStudy)
    {
        $tracerStudy->delete();

        return redirect()->route('admin.tracer-study')->with('success', 'Data tracer study berhasil dihapus.');
    }
}
