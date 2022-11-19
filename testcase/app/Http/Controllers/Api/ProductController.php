<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\AddProductJob;
use App\Jobs\UpdateProductJob;
use App\Jobs\DeleteProductJob;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Nette\Schema\ValidationException;

class ProductController extends Controller
{
    public function getProducts(){
        try {
            $products = Product::query()->get();
            return response(['status' => 'success', 'item' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['status' => 'error', 'message' => $queryException->getMessage()]);
        }
    }

    public function getProductById($id){
        try {
            $product = Product::query()->where('id',$id)->first();
            if ($product == null){
                return response(['message' => 'Product Not Found!']);
            }
            return response(['status' => 'success', 'item' => ['products' => $product]]);
        } catch (QueryException $queryException) {
            return response(['status' => 'error', 'message' => $queryException->getMessage()]);
        }
    }

    public function addProduct(Request $request){
        try {
            $validated_data = $request->validate([
                'sku_no' => 'required',
                'name' => 'required',
                'slug' => 'required',
                'quantity' => 'required',
                'price' => 'required'
            ]);
            AddProductJob::dispatch($validated_data);

            return response(['status' => 'success', 'message' => 'Product add process is queued..']);
        } catch (QueryException $queryException) {
            return response(['status' => 'error', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['status' => 'error', 'message' => $throwable->getMessage()]);
        }
    }

    public function updateProduct(Request $request,$id){
        try {
            $validated_data = $request->validate([
                'sku_no' => 'required',
                'name' => 'required',
                'slug' => 'required',
                'quantity' => 'required',
                'price' => 'required'
            ]);
            UpdateProductJob::dispatch($validated_data,$id);

            return response(['status' => 'success', 'message' => 'Product update process is queued.']);
        } catch (QueryException $queryException) {
            return response(['status' => 'error', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['status' => 'error', 'message' => $throwable->getMessage()]);
        }
    }

    public function deleteProduct($id){
        try {
            $item = Product::query()->where('id',$id)->first();
            if ($item == null){
                return response(['message' => 'Product Not Found!']);
            }else{
                DeleteProductJob::dispatch($id);
            }
            return response(['status' => 'success', 'message' => 'Product delete process is queued.']);
        } catch (QueryException $queryException) {
            return response(['status' => 'error', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['status' => 'error', 'message' => $throwable->getMessage()]);
        }
    }
}
