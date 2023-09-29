<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price'];
}
