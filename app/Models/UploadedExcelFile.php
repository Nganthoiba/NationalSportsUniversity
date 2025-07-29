<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
class UploadedExcelFile extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'uploaded_excel_files';

}
