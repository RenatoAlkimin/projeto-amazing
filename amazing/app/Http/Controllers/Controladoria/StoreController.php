<?php

namespace App\Http\Controllers\Controladoria;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function index(string $scope, Request $request)
    {
        $stores = Store::query()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('modules.controladoria.stores.index', compact('stores'));
    }

    public function create(string $scope, Request $request)
    {
        return view('modules.controladoria.stores.create');
    }

    public function store(string $scope, Request $request)
    {
        $data = $request->validate([
            'scope_slug' => [
                'required',
                'string',
                'max:64',
                'regex:/^[a-z0-9_-]{1,64}$/i',
                Rule::unique('stores', 'scope_slug'),
            ],
            'name' => ['required', 'string', 'max:120'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        $data['status'] = $data['status'] ?? 'active';

        Store::query()->create($data);

        return redirect()
            ->route('controladoria.stores.index', ['scope' => $scope])
            ->with('success', 'Loja criada com sucesso.');
    }
}
