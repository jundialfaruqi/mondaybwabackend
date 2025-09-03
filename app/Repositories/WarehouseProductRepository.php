<?php

namespace App\Repositories;

use App\Models\WarehouseProduct;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class WarehouseProductRepository
{

  public function getByWarehouseAndProduct(int $warehouseId, int $productId): ?WarehouseProduct
  {
    return WarehouseProduct::where('warehouse_id', $warehouseId)
      ->where('product_id', $productId)
      ->first();
  }

  public function updateStock(int $warehouseId, int $productId, int $stock): WarehouseProduct
  {
    $warehouseProduct = $this->getByWarehouseAndProduct($warehouseId, $productId);

    if (!$warehouseProduct) {
      throw ValidationException::withMessages([
        'product_id' => ['Product not found for this Warehouse']
      ]);
    }

    $warehouseProduct->update(['stock' => $stock]);

    return $warehouseProduct;
  }
}
