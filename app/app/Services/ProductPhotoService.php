<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPhoto;

class ProductPhotoService
{
    public function createProductPhoto(array $data)
    {
        $productId = $data['product_id'];
        $product = Product::find($productId);

        if (!$product) {
            throw new \InvalidArgumentException('Produto não encontrado.');
        }

        return ProductPhoto::create($data);
    }

    public function updateProductPhoto($id, array $data)
    {
        $photo = ProductPhoto::find($id);
        if (!$photo) {
            return null;
        }
        $photo->update($data);
        return $photo;
    }

    public function deleteProductPhoto($id)
    {
        $photo = ProductPhoto::find($id);
        if (!$photo) {
            return null;
        }
        return $photo->delete();
    }

    public function getAllPhotosByProductId($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return false;
        }

        return ProductPhoto::where('product_id', $productId)->get();
    }
}
