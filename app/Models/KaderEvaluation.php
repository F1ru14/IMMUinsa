<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $kader_id
 * @property int $fasilitator_id
 * @property string $note
 * @property string $category
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $kader
 * @property-read User $fasilitator
 */
class KaderEvaluation extends Model
{
    /** @use HasFactory<Factory<static>> */
    use HasFactory;

    protected $fillable = [
        'kader_id',
        'fasilitator_id',
        'note',
        'category',
    ];

    /**
     * Get the kader who received this evaluation.
     *
     * @return BelongsTo<User, $this>
     */
    public function kader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    /**
     * Get the fasilitator who wrote this evaluation.
     *
     * @return BelongsTo<User, $this>
     */
    public function fasilitator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fasilitator_id');
    }
}
