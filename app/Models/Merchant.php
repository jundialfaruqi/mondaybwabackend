<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Merchant extends Model
{
    //Digunakan untuk memungkinkan fitur soft delete pada model ini. Dengan menggunakan SoftDeletes, data tidak akan dihapus secara permanen dari database, melainkan akan ditandai sebagai dihapus dengan kolom deleted_at. Ini memungkinkan kamu untuk "mengembalikan" data yang terhapus jika dibutuhkan.
    use SoftDeletes;

    //fillable adalah properti yang digunakan untuk menentukan atribut mana yang dapat diisi secara massal (mass assignable). Dengan mendefinisikan atribut-atribut ini dalam $fillable, kamu dapat melindungi model dari serangan mass assignment, di mana pengguna jahat dapat mencoba mengisi atribut yang tidak seharusnya mereka miliki aksesnya.
    protected $fillable = ['name', 'address', 'photo', 'phone', 'keeper_id'];

    //belongsTo digunakan untuk mendefinisikan relasi one-to-many atau inverse relationship. Dalam kasus ini, Merchant belongs to (milik) satu User yang diidentifikasi oleh kolom keeper_id. Relasi ini berarti setiap Merchant terkait dengan satu User yang memiliki peran sebagai keeper (penjaga atau pemilik). Biasanya, keeper_id akan berisi ID dari pengguna yang bertanggung jawab atas merchant ini.
    public function keeper()
    {
        return $this->belongsTo(User::class, 'keeper_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'merchant_products')
            ->withPivot('stock')
            ->withPivot('warehouse_id')
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getPhotoAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return url(Storage::url($value));
    }
}
