<?php

namespace App\Http\Controllers;

use App\Models\KategoriTenant;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::query()
            ->leftJoin('kategori_tenant', 'tenant.id_kategori', '=', 'kategori_tenant.id_kategori')
            ->select('tenant.*', 'kategori_tenant.nama_kategori')
            ->orderBy('tenant.nama_tenant')
            ->paginate(15);

        return view('admin.tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        return view('admin.tenants.create', [
            'tenant' => new Tenant(),
            'kategori' => KategoriTenant::orderBy('nama_kategori')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('gambar_tenant')) {
            $data['gambar_tenant'] = $request->file('gambar_tenant')->store('tenants', 'uploads');
        }

        Tenant::create($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant berhasil ditambahkan.');
    }

    public function edit(Tenant $tenant): View
    {
        return view('admin.tenants.edit', [
            'tenant' => $tenant,
            'kategori' => KategoriTenant::orderBy('nama_kategori')->get(),
        ]);
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('gambar_tenant')) {
            if ($tenant->gambar_tenant) {
                Storage::disk('uploads')->delete($tenant->gambar_tenant);
            }

            $data['gambar_tenant'] = $request->file('gambar_tenant')->store('tenants', 'uploads');
        }

        $tenant->update($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant berhasil diperbarui.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        if ($tenant->gambar_tenant) {
            Storage::disk('uploads')->delete($tenant->gambar_tenant);
        }

        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'id_kategori' => ['required', Rule::exists('kategori_tenant', 'id_kategori')],
            'nama_tenant' => ['required', 'string', 'max:150'],
            'gambar_tenant' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'no_unit' => ['nullable', 'string', 'max:20'],
            'lantai' => ['nullable', 'integer', 'min:0', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:15'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
