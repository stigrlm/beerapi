<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beer;

class BeerController extends Controller
{
    public function index()
    {
        return Beer::all();
    }

    public function show($id)
    {
        return Beer::findOrFail($id);
    }

    public function store(Request $request)
    {
        return Beer::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $beer = Beer::findOrFail($id);
        $beer->update($request->all());

        return $beer;
    }

    public function destroy(Request $request, $id)
    {
        $beer = Beer::findOrFail($id);
        $beer->delete();

        return response()->json([], 204);
    }
}
