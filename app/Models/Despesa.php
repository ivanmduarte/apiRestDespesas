<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Despesa extends Model
{
    use HasFactory;

    protected $fillable = ["descricao","data","valor","usuario"];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
