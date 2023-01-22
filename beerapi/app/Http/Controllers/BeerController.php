<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beer;
use App\Http\Resources\BeerResource;

class BeerController extends Controller
{
    public function list(Request $request)
    {
        $input = $request->all();

        $nameFilter = $input['name_filter'] ?? null;
        $orderingColumns = $input['ordering_columns'] ?? null;

        $beersQuery = Beer::select('*');
        if ($nameFilter) {
            $beersQuery->where('name','LIKE','%'.$nameFilter.'%');
        }

        if ($orderingColumns)
        {
            $orderingValid = true;

            foreach($input['ordering_columns'] as $column => $direction)
            {
                if (in_array(strtolower($column), ['price', 'rating_avg']) == false)
                {
                    $orderingValid = false;
                    break;
                }
                if (in_array(strtolower($direction), ['desc', 'asc']) == false)
                {
                    $orderingValid = false;
                    break;
                }

                $beersQuery->orderBy($column, $direction);
            }

            if ($orderingValid == false) {
                return response()->json(['message' => 'Incorrect usage of ordering filter'], 404);
            }
        }
        $beersQuery->orderBy('id', 'asc');
        $beers = $beersQuery->get();

        return BeerResource::collection($beers);

    }

    public function show($id)
    {
        return new BeerResource(Beer::findOrFail($id));
    }

    public function store(Request $request)
    {
        return Beer::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $beer = Beer::findOrFail($id);
        $beer->update($request->all());

        return new BeerResource($beer);
    }

    public function destroy(Request $request, $id)
    {
        $beer = Beer::findOrFail($id);
        $beer->delete();

        return response()->json([], 204);
    }
}
