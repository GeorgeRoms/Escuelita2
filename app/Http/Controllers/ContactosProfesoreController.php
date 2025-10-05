<?php

namespace App\Http\Controllers;

use App\Models\ContactosProfesore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ContactosProfesoreRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ContactosProfesoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $contactosProfesores = ContactosProfesore::paginate();

        return view('contactos-profesore.index', compact('contactosProfesores'))
            ->with('i', ($request->input('page', 1) - 1) * $contactosProfesores->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contactosProfesore = new ContactosProfesore();

        return view('contactos-profesore.create', compact('contactosProfesore'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactosProfesoreRequest $request): RedirectResponse
    {
        ContactosProfesore::create($request->validated());

        return Redirect::route('contactos-profesores.index')
            ->with('success', 'ContactosProfesore created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $contactosProfesore = ContactosProfesore::find($id);

        return view('contactos-profesore.show', compact('contactosProfesore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $contactosProfesore = ContactosProfesore::find($id);

        return view('contactos-profesore.edit', compact('contactosProfesore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactosProfesoreRequest $request, ContactosProfesore $contactosProfesore): RedirectResponse
    {
        $contactosProfesore->update($request->validated());

        return Redirect::route('contactos-profesores.index')
            ->with('success', 'ContactosProfesore updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        ContactosProfesore::find($id)->delete();

        return Redirect::route('contactos-profesores.index')
            ->with('success', 'ContactosProfesore deleted successfully');
    }
}
