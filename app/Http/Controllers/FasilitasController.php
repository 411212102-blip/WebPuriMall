<?php

namespace App\Http\Controllers;

use App\Http\Requests\FasilitasRequest;
use App\Models\Fasilitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function index(): View
    {
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->paginate(15);

        return view('admin.fasilitas.index', compact('fasilitas'));
    }

    public function create(): View
    {
        return view('admin.fasilitas.create', ['fasilitas' => new Fasilitas()]);
    }

    public function store(FasilitasRequest $request): RedirectResponse
    {
        Fasilitas::create($request->validated());

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Fasilitas $fasilitas): View
    {
        return view('admin.fasilitas.edit', ['fasilitas' => $fasilitas]);
    }

    public function update(FasilitasRequest $request, Fasilitas $fasilitas): RedirectResponse
    {
        $fasilitas->update($request->validated());

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilitas): RedirectResponse
    {
        $fasilitas->delete();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
