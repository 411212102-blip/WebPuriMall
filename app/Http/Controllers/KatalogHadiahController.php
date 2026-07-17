<?php

namespace App\Http\Controllers;

use App\Http\Requests\KatalogHadiahRequest;
use App\Models\KatalogHadiah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KatalogHadiahController extends Controller
{
    public function index(): View
    {
        $hadiah = KatalogHadiah::orderBy('poin_dibutuhkan')->paginate(15);

        return view('admin.hadiah.index', compact('hadiah'));
    }

    public function create(): View
    {
        return view('admin.hadiah.create', ['hadiah' => new KatalogHadiah()]);
    }

    public function store(KatalogHadiahRequest $request): RedirectResponse
    {
        KatalogHadiah::create($request->validated());

        return redirect()->route('admin.hadiah.index')->with('success', 'Hadiah berhasil ditambahkan.');
    }

    public function edit(KatalogHadiah $hadiah): View
    {
        return view('admin.hadiah.edit', compact('hadiah'));
    }

    public function update(KatalogHadiahRequest $request, KatalogHadiah $hadiah): RedirectResponse
    {
        $hadiah->update($request->validated());

        return redirect()->route('admin.hadiah.index')->with('success', 'Hadiah berhasil diperbarui.');
    }

    public function destroy(KatalogHadiah $hadiah): RedirectResponse
    {
        $hadiah->delete();

        return redirect()->route('admin.hadiah.index')->with('success', 'Hadiah berhasil dihapus.');
    }
}
