<?php

namespace App\Models\Product;

use App\Http\Resources\DataTrueResource;
use App\Http\Resources\Product\ProductsResource;
use App\Models\Brand\Brand;
use App\Models\Catalogue\Catalogue;
use App\Models\Category\Category;
use App\Models\User\ProductUser;
use App\Models\Voucher\ProductVoucher;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use App\Traits\CreatedbyUpdatedby;
use ZipArchive;
use Auth;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use Notifiable, Scopes, HasApiTokens, SoftDeletes, CreatedbyUpdatedby, UploadTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'category_id',
        'sub_category_id',
        'brand_id',
        'featured_image',
        'description',
        'point',
        'available_status'
    ];

    public $sortable = [
        'id', 'name', 'description', 'point'
    ];

    /**
     * @var array
     */
    public $foreign_sortable = [
        'category_id', 'brand_id', 'catalogue_id'
    ];

    /**
     * @var array
     */
    public $foreign_table = [
        'categories', 'brands', 'catalogues'
    ];

    /**
     * @var array
     */
    public $foreign_key = [
        'name', 'name', 'name'
    ];

    /**
     * @var array
     */
    public $foreign_method = [
        'category', 'brand', 'catalogues'
    ];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [
        'id', 'name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'category_id' => 'string',
        'sub_category_id' => 'string',
        'brand_id' => 'string',
        'featured_image' => 'string',
        'description' => 'string',
        'point' => 'string',
        'available_status' => 'string',
    ];

    /**
     * @param $value
     * @return mixed
     */
    public function getFeaturedImageAttribute($value)
    {
        if ($this->is_file_exists($value) || (config('s3.filesystem_driver') == 's3' && !is_null($value)))
            return \Illuminate\Support\Facades\Storage::url($value);
        else
            return asset(config('constants.image.product_default_img'));
    }

    /**
     * Insert Product
     * @param $query
     * @param $request
     * @return ProductsResource
     */
    public function scopeInsertProduct($query, $request)
    {
        $data = $request->all();
        $data['name'] = ucwords($request->name);
        $product = Product::create($data);

        $product->products()->attach(Auth::user()->id);

        if ($request['catalogue_id']) {
            //this executes the insert-query
            $product->catalogues()->attach($request['catalogue_id']);
        }

        $realPath = 'product/' . $product->id;
        if ($request->filled('featured_image_key')) {
            $resizeImages = $product->resizeImages($request->get('featured_image_key'), $realPath);

            $product->update([
                'featured_image' => $resizeImages['image'],
            ]);
        }

        if ($request->filled('upload_images')) {

            $realPath = $realPath . '/upload_images';
            foreach ($request->get('upload_images') as $key => $pImg) {

                $productImage = ProductImage::create([
                    'product_id' => $product->id,
                ]);
                $resizeImages = $product->resizeImages($pImg['key'], $realPath . '/' . $productImage->id);

                $productImage->update([
                    'filename' => $resizeImages['image']
                ]);
            }
        }
        return new ProductsResource($product);
    }

    /**
     * update product
     * @param $query
     * @param $request
     * @param $product
     * @return ProductsResource
     */
    public function scopeUpdateProduct($query, $request, $product)
    {
        $data = $request->all();
        $data['name'] = ucwords($request->name);
        $product->update($data);

        $realPath = 'product/' . $product->id;

        $product->catalogues()->detach();
        //this executes the insert-query
        $product->catalogues()->attach($data['catalogue_id']);
        return new ProductsResource($product);
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|JsonResponse
     */
    public function scopeDeleteAll($query, $request)
    {
        if (!empty($request->id)) {
            ProductUser::whereIn('product_id', $request->id)->delete();
            foreach ($request->id as $ids) {
                Storage::deleteDirectory('/product/' . $ids);
            }
            ProductImage::whereIn('product_id', $request->id)->delete();
            DB::table('carts')->whereIn('product_id', $request->id)->delete();
            DB::table('wishlists')->whereIn('product_id', $request->id)->delete();
            ProductVoucher::whereIn('product_id', $request->id)->delete();
            ProductCatalogue::whereIn('product_id', $request->id)->delete();
            Product::whereIn('id', $request->id)->delete();
            return new DataTrueResource(true);
        } else {
            return response()->json(['error' => config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * UploadZipFile
     * @param $query
     * @param $request
     * @return DataTrueResource|JsonResponse
     */
    public function scopeUploadZipFile($query, $request)
    {
        $zip = new ZipArchive;
        $res = $zip->open($request->file('file'));
        $path = 'app/public/';
        if ($res === TRUE) {
            $zip->extractTo(storage_path($path));
            $zip->close();

            if (Storage::disk('public')->exists('product')) {
                $directories = Storage::disk('public')->allFiles('product/');

                $i = 0;
                $lastProductId = 0;
                $product = null;

                foreach ($directories as $directory) {
                    $explodeDirectory = explode('/', $directory);

                    if ($lastProductId == 0 || $explodeDirectory[1] != $lastProductId) {
                        $imgCounter = 0;
                        $lastProductId = $explodeDirectory[1];
                        $product = Product::find($lastProductId);
                    }

                    $realPath = 'product/' . $product->id . '/';
                    if ($explodeDirectory[2] == 'upload_images' && $imgCounter < 4) {
                        $realPath = $realPath . 'upload_images' . '/';


                        $productImage = ProductImage::create([
                            'product_id' => $product->id,
                        ]);

                        $uploadImagePath = $realPath . $productImage->id . '/';

                        $this->uploadProductZipCode($uploadImagePath, $path, $directory, $explodeDirectory[3]);

                        $productImage->update([
                            'filename' => $uploadImagePath . $explodeDirectory[3]
                        ]);
                        $imgCounter++;
                    } else {
                        $this->uploadProductZipCode($realPath, $path, $directory, $explodeDirectory[2]);

                        $product->update([
                            'featured_image' => $realPath . $explodeDirectory[2],
                        ]);
                    }
                    $i++;
                }
            }

            Storage::disk('public')->deleteDirectory('product');
            return new DataTrueResource(true);
        } else {
            return response()->json(['error' => 'failed'], config('constants.validation_codes.unprocessable_entity'));
        }
    }
    /**
     * uploadProductZipCode
     *
     * @param  mixed $realPath
     * @param  mixed $path
     * @param  mixed $directory
     * @param  mixed $fileName
     * @return void
     */
    public function uploadProductZipCode($realPath, $path, $directory, $fileName)
    {
        $file = file_get_contents(storage_path($path . $directory));

        Storage::disk('s3')->put($realPath . $fileName, $file, 'public');
    }

    /**
     * UploadZipFile
     * @param $query
     * @param $request
     * @return DataTrueResource
     */
    public function scopeDeleteImage($query, $request)
    {
        if ($request->is_feature == "1") {
            // delete feature image
            $Product = Product::find($request->product_id);
            $path = 'product/' . $Product->id .  '/' . basename($Product->featured_image);
            $Product->deleteOne($Product->featured_image); // delete file in s3
            $Product->update(['featured_image' => null]);
        } else {
            // delete upload image
            $ProductImage = ProductImage::find($request->image_id);
            $path = 'product/' . $ProductImage->product_id . '/upload_images/' . $ProductImage->id . '/' . basename($ProductImage->filename);
            $ProductImage->deleteOne($path); // delete file in s3
            $ProductImage->delete();
        }
        return new DataTrueResource(true);
    }

    /**
     * UploadZipFile
     * @param $query
     * @param $request
     * @return JsonResponse
     */
    public function scopeUploadImage($query, $request)
    {
        $data = $request->all();

        $realPath = 'product/' . $data['product_id'];


        $response = [];
        if ($request->is_feature == "1") {
            // upload feature image
            $product = Product::find($data['product_id']);

            if ($request->filled('product_images')) {

                foreach ($request->get('product_images') as $key => $pImg) {
                    if ($key == 0) {

                        $resizeImages = $product->resizeImages($pImg['key'], $realPath);

                        $product->update([
                            'featured_image' => $resizeImages['image']
                        ]);
                    }
                }

                $response['id'] = $product->id;
                $response['featured_image'] = $product->featured_image;
            }

            return response()->json(['data' => ['upload_images' => $response]]);
        } else {
            // upload product image


            if ($request->filled('product_images')) {

                $realPath = $realPath . '/upload_images';
                foreach ($request->get('product_images') as $key => $pImg) {
                    $res = [];
                    $productImage = ProductImage::create([
                        'product_id' => $data['product_id'],
                    ]);

                    $resizeImages = $productImage->resizeImages($pImg['key'], $realPath . '/' . $productImage->id);

                    $productImage->update([
                        'filename' => $resizeImages['image']
                    ]);

                    $res['id'] = $productImage->id;
                    $res['filename'] = $productImage->filename;

                    $response[] = $res;
                }
            }

            return response()->json(['data' => ['upload_images' => $response]]);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, "product_user", "product_id", "user_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function catalogues()
    {
        return $this->belongsToMany(Catalogue::class, "product_catalogue", "product_id", "catalogue_id");
    }
}
