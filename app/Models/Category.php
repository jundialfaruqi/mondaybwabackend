<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //Digunakan untuk memungkinkan fitur soft delete pada model ini. Dengan menggunakan SoftDeletes, data tidak akan dihapus secara permanen dari database, melainkan akan ditandai sebagai dihapus dengan kolom deleted_at. Ini memungkinkan kamu untuk "mengembalikan" data yang terhapus jika dibutuhkan.
    use SoftDeletes;

    //fillable adalah properti yang digunakan untuk menentukan atribut mana yang dapat diisi secara massal (mass assignable). Dengan mendefinisikan atribut-atribut ini dalam $fillable, kamu dapat melindungi model dari serangan mass assignment, di mana pengguna jahat dapat mencoba mengisi atribut yang tidak seharusnya mereka miliki aksesnya.
    protected $fillable = ['name', 'photo', 'tagline'];

    //Fungsi ini mendefinisikan relasi one-to-many (hasMany) antara model Category dan model Product. Artinya, setiap kategori (Category) bisa memiliki banyak produk (Product) yang terkait dengannya. Dengan relasi ini, kamu bisa mengakses semua produk yang termasuk dalam kategori tertentu dengan menggunakan $category->products.
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    //Accessor ini digunakan untuk mengubah nilai atribut photo sebelum dikembalikan. Jika nilai photo kosong (null atau string kosong), maka accessor akan mengembalikan null. Namun, jika ada nilai pada photo, accessor akan mengubahnya menjadi URL lengkap menggunakan fungsi url() dan Storage::url(). Storage::url($value) menghasilkan URL relatif dari file yang disimpan di storage, dan url() mengubahnya menjadi URL lengkap yang dapat diakses dari browser.
    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return url(\Illuminate\Support\Facades\Storage::url($value));
    }
}
