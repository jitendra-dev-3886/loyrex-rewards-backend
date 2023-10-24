<?php

namespace App\Http\Controllers\API\Product;

use App\Exports\Product\ProductExport;
use App\Http\Requests\Import\CsvRequest;
use App\Http\Requests\Product\DeleteImageRequest;
use App\Http\Requests\Product\UploadImageRequest;
use App\Http\Requests\Product\ZipFileRequest;
use App\Http\Resources\DataTrueResource;
use App\Imports\Product\ProductImport;
use App\Models\Product\Product;
use App\Http\Requests\Product\ProductsRequest;
use App\Http\Resources\Product\ProductsCollection;
use App\Http\Resources\Product\ProductsResource;
use App\Models\Product\ProductCatalogue;
use App\Models\Product\ProductImage;
use App\Models\User\ProductUser;
use App\Models\Voucher\ProductVoucher;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

/*
 * Product Controller
 * This controller handles the Roles of
 * index,
 * show,
 * store,
 * update,
 * destroy,
 * export Methods.
 */

class ProductsAPIController extends Controller
{
    use UploadTrait;

    /**
     * List All Users
     * @param Request $request
     * @return ProductsCollection
     */
    public function index(Request $request)
    {
        if ($request->get('is_light', false)) {
            $product = new Product();
            $query = User::commonFunctionMethod(Product::select($product->light), $request, true);
        } else {
            $query = User::commonFunctionMethod(Product::class, $request);
        }
        return new ProductsCollection(ProductsResource::collection($query), ProductsResource::class);
    }

    /**
     * Users detail
     * @param Product $product
     * @return ProductsResource
     */

    public function show(Product $product)
    {
        return new ProductsResource($product->load([]));
    }

    /**
     * Create a new Product instance after a valid Role.
     * @param ProductsRequest $request
     * @return ProductsResource
     */

    public function store(ProductsRequest $request)
    {
        return Product::insertProduct($request);
    }
    /**
     * Update Product
     * @param ProductsRequest $request
     * @param Product $product
     * @return ProductsResource
     */

    public function update(ProductsRequest $request, Product $product)
    {
        return Product::updateProduct($request, $product);
    }

    /**
     * Delete User multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Product::deleteAll($request);
    }

    /**
     * Delete Product
     *
     * @param Request $request
     * @param Product $product
     * @return DataTrueResource
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        ProductUser::where('product_id', $product->id)->delete();
        $product->product_images()->delete();
        Storage::deleteDirectory('/product/' . $product->id);
        DB::table('carts')->where('product_id', $product->id)->delete();
        DB::table('wishlists')->where('product_id', $product->id)->delete();
        ProductVoucher::where('product_id', $product->id)->delete();
        ProductCatalogue::where('product_id', $product->id)->delete();
        $product->delete();

        return new DataTrueResource($product);
    }

    /**
     * Delete gallery
     * @param DeleteImageRequest $request
     * @return DataTrueResource
     */
    public function deleteImage(DeleteImageRequest $request)
    {
        return Product::deleteImage($request);
    }

    /**
     * Delete gallery
     * @param UploadImageRequest $request
     * @return DataTrueResource
     */
    public function uploadImage(UploadImageRequest $request)
    {
        return Product::uploadImage($request);
    }

    /**
     * Export Users Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new ProductExport($request), 'Products_' . config('constants.file.name') . '.csv');
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(CsvRequest $request)
    {
        return User::importBulk($request, new ProductImport(), config('constants.models.product_model'), config('constants.import_dir_path.product_dir_path'));
    }


    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadzipfile(ZipFileRequest $request)
    {
        return Product::uploadZipFile($request);
    }

    /**
     * downloadProductZip
     *
     * @return void
     */
    public function downloadProductZip()
    {
        $data['downloadLink'] = Product::generateAssetsLink(storage_path('app/product.zip'));

        return response()->json(['data' => $data]);
    }
}
