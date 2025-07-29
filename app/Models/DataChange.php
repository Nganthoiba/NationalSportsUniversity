<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class DataChange extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'data_change_requests';

    // `_id` acts as primary key in MongoDB
    protected $primaryKey = '_id';
    public $incrementing = false; // UUIDs are not auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'registration_no',
        'records_to_be_changed',
        'reason_of_change',
        'requested_by',
        'date_of_request',
        'status',
        'reviewed_by',
        'date_of_review',
        'old_student_data',
        'reason_if_cancelled', //there must be a reason if the request is cancelled.
        // 'new_student_data',
    ];

    protected $casts = [
        'records_to_be_changed' => 'array',
        'date_of_request' => 'datetime',
        'old_student_data' => 'array',
        // 'new_student_data' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->_id) {
                $model->_id = (string) Str::uuid(); // assign UUID to _id
            }
        });
    }

    // Optional: access request_id as alias for _id
    public function getRequestIdAttribute()
    {
        return $this->_id;
    }
}
