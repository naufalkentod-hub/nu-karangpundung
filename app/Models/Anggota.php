<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model {
    use HasFactory;
    protected $table = 'anggotas';
    protected $fillable = ['nama', 'foto', 'jabatan', 'divisi', 'periode', 'no_hp', 'alamat', 'instagram', 'deskripsi'];
}