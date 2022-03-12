<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropZone extends Model
{
    use HasFactory;

    protected $table = "crud_dropzone";

    protected $guarded = ["id"];
}
