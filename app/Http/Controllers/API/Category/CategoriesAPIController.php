<?php

namespace App\Http\Controllers\API\Category;

use App\Exports\Category\CategoriesExport;
use App\Http\Requests\Category\CategoriesRequest;
use App\Http\Requests\Category\ProductByCategoryRequest;
use App\Http\Requests\Category\SubcategoryRequest;
use App\Http\Resources\DataTrueResource;
use App\Models\Category\Category;
use App\Http\Resources\Category\CategoriesCollection;
use App\Http\Resources\Category\CategoriesResource;
use App\Models\Product\Product;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;

class CategoriesAPIController extends Controller
{
    public function index(Request $request)
    {
        if($request->get('is_light',false)) {
            $category = new Category();
            $query = User::commonFunctionMethod(Category::select($category->light),$request,true);
        } else {
            $query = User::commonFunctionMethod(Category::class, $request);
        }
        return new CategoriesCollection(CategoriesResource::collection($query), CategoriesResource::class);
    }

    public function show(Category $category)
    {
        return new CategoriesResource($category->load([]));
    }

    public function store(CategoriesRequest $request)
    {
        $request['step'] = (int)$request->parent_id + 1;
        $request['parent_id'] = (int)$request->parent_id;
        return new CategoriesResource(Category::create($request->all()));
    }

    public function update(CategoriesRequest $request, Category $category)
    {
        $category['step'] = (int)$request->parent_id + 1;
        $request['parent_id'] = (int)$request->parent_id;
        $category->update($request->all());
        return new CategoriesResource($category);
    }

    /**
     * This method is used to delete role
     *
     * @param Request $request
     * @param Category $category
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Category $category)
    {
        return Category::deleteAPI($request, $category);
    }

    /**
     * Delete User multiple
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAll(Request $request)
    {
        return Category::deleteAll($request);
    }

    /**
     * Export Users Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new CategoriesExport($request), 'Categories_' . config('constants.file.name') . '.csv');
    }

    /**
     * Export Users Data
     * @param Request $request
     * @return JsonResponse
     */
    public function subcategories(SubcategoryRequest $request) {
        $subCategories = ((int)$request->id != 0) ? Category::find($request->id)->subCategories : [];
        return response()->json(['data' => $subCategories]);
    }

    public function parentCategories() {
        return response()->json(['data' => Category::select(['id', 'name', 'parent_id'])->where(['parent_id' => 0])->get()]);
    }

    public function productsByCategory(ProductByCategoryRequest $request) {
        $availabilty = trim($request->get('filter')['availabilty']);
        if($availabilty != "") {
            $product = Product::select(['id', 'name'])->whereIn('category_id', $request->id)->where('available_status', $availabilty)->get();
            return response()->json(['data' => $product]);
        }
        return response()->json(['data' => Product::select(['id', 'name'])->whereIn('category_id', $request->id)->get()]);
    }
}
