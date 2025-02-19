<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

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
