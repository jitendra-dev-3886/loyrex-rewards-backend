<?php

namespace App\Http\Controllers\API\Brand;

use App\Exports\Brand\BrandsExport;
use App\Http\Requests\Brand\BrandsRequest;
use App\Models\Brand\Brand;
use App\Http\Resources\Brand\BrandsCollection;
use App\Http\Resources\Brand\BrandsResource;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BrandsAPIController extends Controller
{
    public function index(Request $request)
    {
        if($request->get('is_light',false)) {
            $brand = new Brand();
            $query = User::commonFunctionMethod(Brand::select($brand->light),$request,true);
        } else {
            $query = User::commonFunctionMethod(Brand::class, $request);
        }
        return new BrandsCollection(BrandsResource::collection($query), BrandsResource::class);
    }

    public function show(Brand $brand)
    {
        return new BrandsResource($brand->load([]));
    }

    public function store(BrandsRequest $request)
    {
        return new BrandsResource(Brand::create($request->all()));
    }

    public function update(BrandsRequest $request, Brand $brand)
    {
        $brand->update($request->all());
        return new BrandsResource($brand);
    }

    public function destroy(Request $request, Brand $brand)
    {
        return Brand::deleteAPI($request, $brand);
    }

    /**
     * Delete User multiple
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAll(Request $request)
    {
        return Brand::deleteAll($request);
    }

    /**
     * Export Users Data
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new BrandsExport($request), 'Brands_' . config('constants.file.name') . '.csv');
    }
}
