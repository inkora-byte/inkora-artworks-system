<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display the read-only list of roles and their permissions.
     *
     * Full role CRUD is deferred per the approved Sprint 0 scope
     * reduction (see Milestone 0.5 / RolePolicy) — roles are seeded, not
     * staff-managed, in Phase 1.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::with('permissions')->orderBy('name')->get();

        return view('roles.index', compact('roles'));
    }
}
