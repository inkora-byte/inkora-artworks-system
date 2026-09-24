<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DesignStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Design extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * Note: `slug` is deliberately NOT listed here — it's auto-generated
     * from `name` on creation (see booted() below), never set directly
     * via mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'artist_name',
        'status',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => DesignStatus::class,
        ];
    }

    /**
     * Auto-generate a unique slug from `name` on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Design $design): void {
            if (empty($design->slug)) {
                $design->slug = static::generateUniqueSlug($design->name);
            }
        });
    }

    /**
     * Generate a slug from the given name, appending a numeric suffix if
     * needed to guarantee uniqueness against both active and
     * soft-deleted designs (the `slug` column's unique index doesn't
     * distinguish between them).
     */
    private static function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * The staff member who created this design record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Products built from this design. Added now that Product exists
     * (Milestone 1.2) — this is the exact relationship that lets one
     * artwork become an in-house sticker AND an outsourced acrylic
     * keychain, validated back in the Hybrid Fulfillment Impact
     * Analysis.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Register media collections for this model.
     *
     * 'source_files' — original artwork files (AI, PSD, high-res
     * PNG/PDF) staff upload. Explicitly bound to the 'private' disk
     * (Milestone 0.10) — source files are never meant to be publicly
     * downloadable, only accessible to authenticated staff.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('source_files')
            ->useDisk('private');
    }
}
