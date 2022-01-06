<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find(mixed $id)
 * @property integer $id
 * @property integer $parent_id
 * @property integer $type
 * @property string $name
 * @property integer $level
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Category extends Model
{
    use HasFactory, SoftDeletes;
}
