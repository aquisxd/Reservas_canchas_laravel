<?php
// app/Http/Controllers/CourtController.php
namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::active()->paginate(9);
        return view('courts.index', compact('courts'));
    }

    public function show(Court $court)
    {
        $court->load(['reservations' => function($query) {
            $query->upcoming()->orderBy('reservation_date', 'asc');
        }]);

        return view('courts.show', compact('court'));
    }

    public function create()
    {
        // Verificar si es admin
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('courts.create');
    }

    public function store(Request $request)
    {
        // Verificar si es admin
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'surface_type' => 'required|in:clay,hard,grass,synthetic',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courts', 'public');
        }

        Court::create($data);

        return redirect()->route('courts.index')
            ->with('success', 'Cancha creada exitosamente.');
    }

    public function edit(Court $court)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('courts.edit', compact('court'));
    }

    public function update(Request $request, Court $court)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'surface_type' => 'required|in:clay,hard,grass,synthetic',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courts', 'public');
        }

        $court->update($data);

        return redirect()->route('courts.index')
            ->with('success', 'Cancha actualizada exitosamente.');
    }

    public function destroy(Court $court)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $court->delete();

        return redirect()->route('courts.index')
            ->with('success', 'Cancha eliminada exitosamente.');
    }
}
