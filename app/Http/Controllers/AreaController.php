<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AreaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $areas = Area::paginate();

        return view('area.index', compact('areas'))
            ->with('i', ($request->input('page', 1) - 1) * $areas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $area = new Area();

        return view('area.create', compact('area'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AreaRequest $request): RedirectResponse
    {
        Area::create($request->validated());

        return Redirect::route('areas.index')
            ->with('success', 'Area created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $area = Area::find($id);

        return view('area.show', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $area = Area::find($id);

        return view('area.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AreaRequest $request, Area $area): RedirectResponse
    {
        $area->update($request->validated());

        return Redirect::route('areas.index')
            ->with('success', 'Area updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Area::find($id)->delete();

        return Redirect::route('areas.index')
            ->with('success', 'Area deleted successfully');
    }
}
