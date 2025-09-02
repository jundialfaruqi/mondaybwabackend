<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Warehouse extends Model
{
    //Digunakan untuk memungkinkan fitur soft delete pada model ini. Dengan menggunakan SoftDeletes, data tidak akan dihapus secara permanen dari database, melainkan akan ditandai sebagai dihapus dengan kolom deleted_at. Ini memungkinkan kamu untuk "mengembalikan" data yang terhapus jika dibutuhkan.
    use SoftDeletes;

    //fillable adalah properti yang digunakan untuk menentukan atribut mana yang dapat diisi secara massal (mass assignable). Dengan mendefinisikan atribut-atribut ini dalam $fillable, kamu dapat melindungi model dari serangan mass assignment, di mana pengguna jahat dapat mencoba mengisi atribut yang tidak seharusnya mereka miliki aksesnya.
    protected $fillable = ['name', 'address', 'photo', 'phone'];

    //Fungsi ini mendefinisikan relasi many-to-many antara Warehouse dan Product. Relasi ini menggunakan tabel pivot warehouse_products untuk menyimpan hubungan antara warehouse dan produk. Selain itu, ada kolom stock yang menyimpan jumlah stok produk di setiap warehouse, yang juga bisa diakses melalui pivot. withPivot('stock') memastikan bahwa kolom stock di tabel pivot juga tersedia saat mengakses relasi ini. withTimestamps() menambahkan kolom created_at dan updated_at pada tabel pivot, jika kamu ingin melacak kapan relasi ini dibuat atau diperbarui.
    public function products()
    {
        return $this->belongsToMany(Product::class, 'warehouse_products')
            ->withPivot('stock')
            ->withTimestamps();
    }

    //Accessor ini digunakan untuk mengubah nilai atribut photo sebelum dikembalikan. Jika nilai photo kosong (null atau string kosong), maka accessor akan mengembalikan null. Namun, jika ada nilai pada photo, accessor akan mengubahnya menjadi URL lengkap menggunakan fungsi url() dan Storage::url(). Storage::url($value) menghasilkan URL relatif dari file yang disimpan di storage, dan url() mengubahnya menjadi URL lengkap yang dapat diakses dari browser.
    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return url(Storage::url($value));
    }
}
