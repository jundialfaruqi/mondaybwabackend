<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantProductRequest;
use App\Http\Requests\MerchantProductUpdateRequest;
use App\Services\MerchantProductService;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class MerchantProductController extends Controller
{
    //

    private MerchantProductService $merchantProductService;

    public function __construct(MerchantProductService $merchantProductService)
    {
        $this->merchantProductService = $merchantProductService;
    }

    public function store(MerchantProductRequest $request, int $merchant)
    {
        $validated = $request->validate();

        $validated['merchant_id'] = $merchant;

        $merchantProduct = $this->merchantProductService->assignProductToMerchant($validated);

        return response()->json([
            'message' => 'Product assigned to merchant successfully',
            'data' => $merchantProduct,
        ], 201);
    }

    public function update(MerchantProductUpdateRequest $request, int $merchantId, int $porductId)
    {
        $validated = $request->validate();

        $merchantProduct = $this->merchantProductService->updateStock(
            $merchantId,
            $porductId,
            $validated['stock'],
            $validated['warehouse_id']
        );

        return response()->json([
            'message' => 'Stock updated successfully',
            'data' => $merchantProduct,
        ]);
    }

    public function destroy(int $merchant, int $product)
    {
        $this->merchantProductService->removeProductFromMerchant($merchant, $product);

        return response()->json([
            'message' => 'Product detach from merchant successfully',
        ]);
    }
}
