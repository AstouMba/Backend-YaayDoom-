<?php

namespace App\Application\Auth;

use App\Models\User;
use App\Services\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadProfessionalDocuments extends Service
{
    /**
     * @param array<int, UploadedFile> $documents
     */
    public function execute(array $documents, ?User $user = null): User
    {
        if (!$user) {
            $this->unauthorized();
        }

        if ($user->role !== 'professionnel') {
            $this->unprocessable('documents_only_for_professionnels');
        }

        $storedDocuments = is_array($user->verification_documents ?? null)
            ? $user->verification_documents
            : [];

        foreach ($documents as $document) {
            $folder = 'professionnels/' . $user->id . '/verification';
            $filename = Str::uuid()->toString() . '.' . $document->getClientOriginalExtension();
            $path = $document->storeAs($folder, $filename, 'public');

            $storedDocuments[] = [
                'name' => $document->getClientOriginalName(),
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'mime' => $document->getClientMimeType(),
                'size' => $document->getSize(),
                'uploaded_at' => now()->toISOString(),
            ];
        }

        $user->verification_documents = $storedDocuments;
        $user->save();

        return $user->fresh();
    }
}
