<?php

namespace App\Models;

use App\Enums\FocusArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property FocusArea $focus_area
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'focus_area',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'focus_area' => FocusArea::class,
            'order' => 'integer',
        ];
    }

    /**
     * Get completions for this task.
     *
     * @return HasMany<KaderTask, $this>
     */
    public function completions(): HasMany
    {
        return $this->hasMany(KaderTask::class);
    }
}
