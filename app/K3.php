<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K3 extends Model
{

    protected $table = 'k3';
    protected $fillable = ['lat', 'long', 'keterangan', 'foto', 'id_pelapor', 'id_admin', 'sudah_diterima'];
}
