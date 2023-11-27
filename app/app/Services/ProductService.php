<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function updateProduct($id, array $data)
    {
        $product = Product::find($id);
        if (!$product) {
            return null;
        }
        $product->update($data);
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return null;
        }
        return $product->delete();
    }

    public function getAllProducts()
    {
        return Product::all();
    }

    public function getProductByName($name)
    {
        return Product::with('photos')->where('name', 'like', '%' . $name . '%')->get();;
    }

    public function getProductById($id)
    {
        return Product::with('photos')->find($id);
    }
}
