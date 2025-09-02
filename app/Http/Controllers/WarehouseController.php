<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    // Deklarasi properti class dengan tipe WarehouseService
    // Properti ini akan menampung instance dari WarehouseService
    private WarehouseService $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        // Constructor Dependency Injection
        // Parameter $warehouseService otomatis di-inject oleh Laravel
        // sesuai binding yang ada di service container

        // Simpan instance $warehouseService ke properti class
        // sehingga bisa digunakan di method-method lain
        $this->warehouseService = $warehouseService;
    }

    // Method index() biasanya dipakai di controller Laravel
    // untuk menampilkan daftar (list) data resource
    public function index()
    {
        // Tentukan field yang ingin diambil dari data warehouse
        // Dalam hal ini: hanya 'id', 'name', dan 'photo'
        $fields = ['id', 'name', 'photo'];

        // Ambil semua data warehouse melalui WarehouseService
        // Jika $fields kosong/null, maka gunakan ['*'] untuk ambil semua kolom
        $warehouses = $this->warehouseService->getAll($fields ?: ['*']);

        // Kembalikan response dalam bentuk JSON
        // Data warehouse dibungkus dengan WarehouseResource collection
        // agar format output lebih terkontrol dan konsisten
        return response()->json(WarehouseResource::collection($warehouses));
    }

    // Method show() biasanya digunakan untuk menampilkan detail 1 data (resource) berdasarkan ID
    public function show(int $id)
    {
        try {
            // Tentukan field (kolom) yang ingin ditampilkan
            // Di sini: id, name, photo, dan phone
            $fields = ['id', 'name', 'photo', 'phone'];

            // Ambil data warehouse dari service berdasarkan ID dan field yang ditentukan
            // Jika ID tidak ditemukan, biasanya akan memicu exception ModelNotFoundException
            $warehouse = $this->warehouseService->getById($id, $fields);

            // Kembalikan response dalam bentuk JSON
            // Data warehouse dibungkus dengan WarehouseResource agar formatnya lebih terstruktur
            return response()->json(new WarehouseResource($warehouse));
        } catch (ModelNotFoundException $e) {
            // Jika data warehouse dengan ID yang diminta tidak ditemukan
            // Tangkap error ModelNotFoundException dan kembalikan response JSON dengan pesan error
            // Sertakan juga HTTP status code 404 (Not Found)
            return response()->json([
                'message' => 'Warehouse not found'
            ], 404);
        }
    }

    public function store(WarehouseRequest $request)
    {
        $warehouse = $this->warehouseService->create($request->validate());

        return response()->json(new WarehouseResource($warehouse), 201);
    }

    public function update(WarehouseRequest $request, int $id)
    {
        try {
            $warehouse = $this->warehouseService->update($id, $request->validated());

            return response()->json(new WarehouseResource($warehouse));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Warehouse not found',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->warehouseService->delete($id);
            return response()->json([
                'message' => 'Warehouse deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Warehouse not found',
            ], 404);
        }
    }
}
