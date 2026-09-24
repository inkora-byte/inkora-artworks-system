<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\StockMovement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired on every single recorded stock movement, regardless of type or
 * direction. No listener exists until Sprint 11 (Notifications) — this
 * is intentional, per Step 6's established pattern: firing the event now
 * costs nothing (an event with no listener is a harmless no-op), and it
 * means later sprints wire up listeners against events that already
 * exist and already fire correctly, rather than adding both at once.
 */
class StockLevelChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly StockMovement $movement,
    ) {
    }
}
