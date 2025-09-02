<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    //Digunakan untuk memungkinkan fitur soft delete pada model ini. Dengan menggunakan SoftDeletes, data tidak akan dihapus secara permanen dari database, melainkan akan ditandai sebagai dihapus dengan kolom deleted_at. Ini memungkinkan kamu untuk "mengembalikan" data yang terhapus jika dibutuhkan.
    use SoftDeletes;

    //fillable adalah properti yang digunakan untuk menentukan atribut mana yang dapat diisi secara massal (mass assignable). Dengan mendefinisikan atribut-atribut ini dalam $fillable, kamu dapat melindungi model dari serangan mass assignment, di mana pengguna jahat dapat mencoba mengisi atribut yang tidak seharusnya mereka miliki aksesnya.
    protected $fillable = ['name', 'thumbnail', 'about', 'price', 'category_id', 'is_popular'];

    //Ini adalah fungsi relasi one-to-many (belongsTo) antara model Product dan model Category. Setiap produk (Product) hanya dapat memiliki satu kategori (Category), dan relasi ini memungkinkan untuk mengakses kategori yang terkait dengan produk tersebut. Misalnya, $product->category akan memberi kamu objek Category yang terkait dengan produk ini.
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //Fungsi ini mendefinisikan relasi many-to-many antara Product dan Merchant. Relasi ini menggunakan tabel pivot merchant_products untuk menyimpan hubungan antara produk dan merchant. Selain itu, ada kolom stock yang menyimpan jumlah stok produk di setiap merchant, yang juga bisa diakses melalui pivot. withPivot('stock') memastikan bahwa kolom stock di tabel pivot juga tersedia saat mengakses relasi ini. withTimestamps() menambahkan kolom created_at dan updated_at pada tabel pivot, jika kamu ingin melacak kapan relasi ini dibuat atau diperbarui.
    public function merchants()
    {
        return $this->belongsToMany(Merchant::class, 'merchant_products')
            ->withPivot('stock')
            ->withTimestamps();
    }

    //Sama seperti merchants(), fungsi ini mendefinisikan relasi many-to-many antara Product dan Warehouse.Relasi ini menggunakan tabel pivot warehouse_products. Kolom stock pada pivot menyimpan jumlah stok produk di setiap warehouse. withTimestamps() menambahkan kolom created_at dan updated_at pada tabel pivot.
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_products')
            ->withPivot('stock')
            ->withTimestamps();
    }

    //Fungsi ini mendefinisikan relasi one-to-many (hasMany) antara model Product dan model TransactionProduct. Artinya, setiap produk (Product) bisa memiliki banyak transaksi yang melibatkan produk tersebut. Dengan relasi ini, kamu bisa mengakses transaksi yang melibatkan produk tertentu dengan menggunakan $product->transactions.
    public function transactions()
    {
        return $this->hasMany(TransactionProduct::class);
    }

    //Fungsi ini menghitung total stok produk di semua warehouse yang terkait dengan produk ini. Fungsi ini menggunakan relasi warehouses() untuk mengakses semua warehouse yang terkait, kemudian menjumlahkan nilai stok dari setiap warehouse menggunakan metode sum('stock'). Hasilnya adalah total stok produk di seluruh warehouse.
    public function getWarehouseProductStock()
    {
        return $this->warehouses()->sum('stock');
    }

    //Fungsi ini menghitung total stok produk di semua merchant yang terkait dengan produk ini. Fungsi ini menggunakan relasi merchants() untuk mengakses semua merchant yang terkait, kemudian menjumlahkan nilai stok dari setiap merchant menggunakan metode sum('stock'). Hasilnya adalah total stok produk di seluruh merchant.
    public function getMerchantProductStock()
    {
        return $this->merchants()->sum('stock');
    }

    //Accessor ini digunakan untuk mengubah nilai atribut thumbnail sebelum dikembalikan. Jika nilai thumbnail kosong (null atau string kosong), maka accessor akan mengembalikan null. Namun, jika ada nilai pada thumbnail, accessor akan mengubahnya menjadi URL lengkap menggunakan fungsi url() dan Storage::url(). Storage::url($value) menghasilkan URL relatif dari file yang disimpan di storage, dan url() mengubahnya menjadi URL lengkap yang dapat diakses dari browser.
    public function getThumbnailAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return url(Storage::url($value));
    }
}
