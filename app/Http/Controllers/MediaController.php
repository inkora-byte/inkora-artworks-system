<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    /**
     * Stream a media file to an authenticated, authorized user.
     *
     * This is the "controlled route" Step 7's file-upload security
     * standard calls for. Private-disk media (design source files,
     * Milestone 0.10) has no public URL at all — this Controller is the
     * only legitimate way to ever view or download one. Generic and
     * polymorphic on purpose: as later sprints attach media to more
     * models (Custom Order reference images, etc.), this one Controller
     * continues to serve all of them, rather than each module building
     * its own copy of the same streaming logic.
     */
    public function show(Media $media): StreamedResponse
    {
        $this->authorizeMediaAccess($media);

        $disk = Storage::disk($media->disk);
        $path = $media->id.'/'.$media->file_name;

        if (! $disk->exists($path)) {
            abort(404);
        }

        return $disk->response($path, $media->file_name);
    }

    /**
     * Authorize access to a media file via its owning model's own
     * 'update' Policy check — if you're allowed to manage the parent
     * record, you're allowed to view its attached files.
     */
    private function authorizeMediaAccess(Media $media): void
    {
        $model = $media->model;

        if ($model === null) {
            abort(404);
        }

        $this->authorize('update', $model);
    }
}
