<?php

namespace App\Http\Controllers;

use App\Models\Kardex;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\KardexRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $kardexes = Kardex::paginate();

        return view('kardex.index', compact('kardexes'))
            ->with('i', ($request->input('page', 1) - 1) * $kardexes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kardex = new Kardex();

        return view('kardex.create', compact('kardex'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KardexRequest $request): RedirectResponse
    {
        Kardex::create($request->validated());

        return Redirect::route('kardexes.index')
            ->with('success', 'Kardex created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $kardex = Kardex::find($id);

        return view('kardex.show', compact('kardex'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $kardex = Kardex::find($id);

        return view('kardex.edit', compact('kardex'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KardexRequest $request, Kardex $kardex): RedirectResponse
    {
        $kardex->update($request->validated());

        return Redirect::route('kardexes.index')
            ->with('success', 'Kardex updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Kardex::find($id)->delete();

        return Redirect::route('kardexes.index')
            ->with('success', 'Kardex deleted successfully');
    }
}
