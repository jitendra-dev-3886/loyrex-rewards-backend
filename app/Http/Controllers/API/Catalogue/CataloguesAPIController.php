<?php

namespace App\Http\Controllers\API\Catalogue;

use App\Exports\Catalogue\CatalogueExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogue\CatalogueRequest;
use App\Http\Resources\Catalogue\CatalogueCollection;
use App\Http\Resources\Catalogue\CatalogueResource;
use App\Models\Catalogue\Catalogue;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;


class CataloguesAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CatalogueCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)) {
            $catalogue = new Catalogue();
            $query = User::commonFunctionMethod(Catalogue::select($catalogue->light),$request,true);
        } else {
            $query = User::commonFunctionMethod(Catalogue::class, $request);
        }
        return new CatalogueCollection(CatalogueResource::collection($query), CatalogueResource::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CatalogueRequest $request
     * @return CatalogueResource
     */
    public function store(CatalogueRequest $request)
    {
        return new CatalogueResource(Catalogue::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param Catalogue $catalogue
     * @return CatalogueResource
     */
    public function show(Catalogue $catalogue)
    {
        return new CatalogueResource($catalogue->load([]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CatalogueRequest $request
     * @param Catalogue $catalogue
     * @return CatalogueResource
     */
    public function update(CatalogueRequest $request, Catalogue $catalogue)
    {
        $catalogue->update($request->all());
        return new CatalogueResource($catalogue);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Catalogue $catalogue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Catalogue $catalogue)
    {
        return Catalogue::deleteAPI($request, $catalogue);
    }

    /**
     * Delete Catalogue multiple
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAll(Request $request)
    {
        return Catalogue::deleteAll($request);
    }
    /**
     * Export Catalogue Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new CatalogueExport($request), 'Catalogues_' . config('constants.file.name') . '.csv');
    }
}
