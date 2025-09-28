<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'phone_number', 'address', 'root'];

    public function loans()
{
    return $this->hasMany(Loan::class);
}
public function sales()
    {
        return $this->hasMany(Sale::class); // Adjust the model name if necessary
    }

}
