<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    //
    protected $table = 'pinjaman';

    public function jenis_koleksi()
    {
        switch ($this->id) {
            case 1:
                $jenis = 'Buku';
                break;

            case 2:
                $jenis = 'Majalah';
                break;

            case 3:
                $jenis = 'Software';
                break;

            case 4:
                $jenis = 'Karya Ilmiah';
                break;

            default:
                $jenis = 'null';
                break;
        }
        return $jenis;
    }
}
