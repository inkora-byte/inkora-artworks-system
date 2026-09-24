<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    // Note: `value` is intentionally NOT cast here — it's stored as text
    // and can represent string/integer/decimal/boolean/json depending on
    // the `type` column. Type casting happens in SettingService, not on
    // the Model, since it's a contextual decision (based on `type`), not
    // a fixed column-to-type mapping Eloquent's cast system expects.
}
