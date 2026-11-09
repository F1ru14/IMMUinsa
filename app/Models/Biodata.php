<?php

namespace App\Models;

use App\Enums\Komisariat;
use Database\Factories\BiodataFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property string $phone_number
 * @property string $address
 * @property Carbon $date_of_birth
 * @property string $gender
 * @property string|null $avatar
 * @property string|null $institution
 * @property string|null $expertise
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class Biodata extends Model
{
    /** @use HasFactory<BiodataFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'address',
        'date_of_birth',
        'gender',
        'avatar',
        'institution',
        'expertise',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Get the human-readable komisariat name.
     *
     * Falls back to the stored value for legacy free-text data.
     */
    public function institutionLabel(): ?string
    {
        if (blank($this->institution)) {
            return null;
        }

        return Komisariat::tryFrom($this->institution)?->label() ?? $this->institution;
    }

    /**
     * Get the user that owns the biodata.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
