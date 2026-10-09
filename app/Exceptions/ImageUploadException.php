<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use RuntimeException;

/**
 * Thrown by App\Helpers\ImageUpload with a message that is safe to show the
 * admin (never a server path). Renders itself, so controllers don't need a
 * try/catch: AJAX callers (TinyMCE upload handlers, the overview modal) get
 * JSON they already read as responseJSON.message; plain form posts go back
 * with the message as a validation error on $field.
 */
class ImageUploadException extends RuntimeException
{
    public string $field = 'image';

    public function forField(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $this->getMessage()], 422);
        }

        return back()->withInput()->withErrors([$this->field => $this->getMessage()]);
    }
}
