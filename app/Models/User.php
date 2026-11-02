<?php

namespace App\Models;

use App\Enums\FocusArea;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string|null $email
 * @property Role $role
 * @property bool $is_active
 * @property FocusArea|null $focus_area
 * @property int|null $fasilitator_id
 * @property int|null $mentor_id
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Biodata|null $biodata
 * @property-read User|null $fasilitator
 * @property-read User|null $mentor
 */
#[Fillable(['name', 'username', 'email', 'password', 'role', 'is_active', 'focus_area', 'fasilitator_id', 'mentor_id'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'role' => Role::class,
            'is_active' => 'boolean',
            'focus_area' => FocusArea::class,
        ];
    }

    /**
     * Get the user's biodata.
     *
     * @return HasOne<Biodata, $this>
     */
    public function biodata(): HasOne
    {
        return $this->hasOne(Biodata::class);
    }

    /**
     * Get the assigned fasilitator.
     *
     * @return BelongsTo<User, $this>
     */
    public function fasilitator(): BelongsTo
    {
        return $this->belongsTo(self::class, 'fasilitator_id');
    }

    /**
     * Get the assigned mentor.
     *
     * @return BelongsTo<User, $this>
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'mentor_id');
    }

    /**
     * Get the kader binaan (for fasilitators).
     *
     * @return HasMany<User, $this>
     */
    public function kaderBinaan(): HasMany
    {
        return $this->hasMany(self::class, 'fasilitator_id');
    }

    /**
     * Get the mentee binaan (for mentors).
     *
     * @return HasMany<User, $this>
     */
    public function menteeBinaan(): HasMany
    {
        return $this->hasMany(self::class, 'mentor_id');
    }

    /**
     * Get evaluations received (for kader).
     *
     * @return HasMany<KaderEvaluation, $this>
     */
    public function evaluationsReceived(): HasMany
    {
        return $this->hasMany(KaderEvaluation::class, 'kader_id');
    }

    /**
     * Get evaluations given (for fasilitators).
     *
     * @return HasMany<KaderEvaluation, $this>
     */
    public function evaluationsGiven(): HasMany
    {
        return $this->hasMany(KaderEvaluation::class, 'fasilitator_id');
    }

    /**
     * Get the completed tasks.
     *
     * @return HasMany<KaderTask, $this>
     */
    public function completedTasks(): HasMany
    {
        return $this->hasMany(KaderTask::class, 'user_id');
    }

    /**
     * Check if the user has completed a specific task.
     */
    public function hasCompletedTask(int $taskId): bool
    {
        return $this->completedTasks()->where('task_id', $taskId)->exists();
    }

    /**
     * Count completed tasks.
     */
    public function completedTasksCount(): int
    {
        return $this->completedTasks()->count();
    }

    /**
     * Determine if selecting a mentor is unlocked (requires 3 tasks).
     */
    public function canSelectMentor(): bool
    {
        return $this->completedTasksCount() >= 3;
    }

    /**
     * Check if user has chosen a focus area.
     */
    public function hasSelectedFocusArea(): bool
    {
        return $this->focus_area !== null;
    }

    /**
     * Check if user has selected a fasilitator.
     */
    public function hasSelectedFasilitator(): bool
    {
        return $this->fasilitator_id !== null;
    }

    /**
     * Check if user has selected a mentor.
     */
    public function hasSelectedMentor(): bool
    {
        return $this->mentor_id !== null;
    }

    /**
     * Check if the user has any of the specified roles.
     */
    public function hasRole(Role|string ...$roles): bool
    {
        foreach ($roles as $role) {
            $roleValue = $role instanceof Role ? $role->value : $role;
            if ($this->role->value === $roleValue) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user has completed their biodata.
     */
    public function hasCompletedBiodata(): bool
    {
        return $this->biodata()->exists();
    }
}
