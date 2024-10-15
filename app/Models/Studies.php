<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studies extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'studies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'college_id',
        'branch',
        'degree',
        'start',
        'end',
    ];

    /**
     * Get the user associated with the study.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the college associated with the study.
     */
    public function college()
    {
        return $this->belongsTo(User::class, 'college_id');
    }
}
