<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationDocumentModel extends Model
{
    use HasFactory;

    protected $table = 'verification_documents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'verification_id',
        'name',
        'path',
        'type',
        'size'
    ];

    // Relationships
    public function verification()
    {
        return $this->belongsTo(VerificationModel::class, 'verification_id', 'id');
    }
} 