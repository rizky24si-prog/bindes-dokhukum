<?php

namespace App\Traits;

use App\Models\Media;

trait HasMedia
{
    /**
     * Relationship dengan media (berdasarkan tabel dan ID)
     */
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', $this->primaryKey)
                    ->where('ref_table', $this->getTable())
                    ->orderBy('sort_order')
                    ->orderBy('created_at');
    }

    /**
     * Get primary media (first by sort_order)
     */
    public function getPrimaryMediaAttribute()
    {
        return $this->media()->first();
    }

    /**
     * Get media URL (untuk primary media)
     */
    public function getMediaUrlAttribute()
    {
        return $this->primaryMedia?->full_url;
    }

    /**
     * Get media thumbnail URL (untuk foto)
     */
    public function getFotoThumbnailAttribute()
    {
        $primaryMedia = $this->primaryMedia;
        
        if (!$primaryMedia) {
            return asset('images/default-avatar.png');
        }
        
        if ($primaryMedia->isImage()) {
            return $primaryMedia->full_url;
        }
        
        return asset('images/default-avatar.png');
    }

    /**
     * Attach media to model
     */
    public function attachMedia(Media $media, string $caption = null, int $sortOrder = 0)
    {
        $media->update([
            'ref_table' => $this->getTable(),
            'ref_id' => $this->{$this->primaryKey},
            'caption' => $caption ?? $media->caption,
            'sort_order' => $sortOrder
        ]);
        
        return $media;
    }

    /**
     * Detach media from model
     */
    public function detachMedia(Media $media = null)
    {
        if ($media) {
            $media->update([
                'ref_table' => null,
                'ref_id' => null
            ]);
        } else {
            // Detach all media
            $this->media()->update([
                'ref_table' => null,
                'ref_id' => null
            ]);
        }
    }

    /**
     * Delete associated media and file
     */
    public function deleteMedia(Media $media = null)
    {
        $mediaService = app(\App\Services\MediaService::class);
        
        if ($media) {
            $mediaService->delete($media);
        } else {
            // Delete all media
            $this->media->each(function ($media) use ($mediaService) {
                $mediaService->delete($media);
            });
        }
    }

    /**
     * Upload file untuk model ini
     */
    public function uploadFile(UploadedFile $file, string $caption = null, int $sortOrder = 0): Media
    {
        $mediaService = app(\App\Services\MediaService::class);
        
        $media = $mediaService->uploadForReference(
            file: $file,
            refTable: $this->getTable(),
            refId: $this->{$this->primaryKey},
            caption: $caption,
            sortOrder: $sortOrder
        );
        
        return $media;
    }

    /**
     * Update file (replace)
     */
    public function updateFile(UploadedFile $file, Media $media = null, string $caption = null): Media
    {
        $mediaService = app(\App\Services\MediaService::class);
        
        if ($media) {
            // Update existing media
            return $mediaService->updateForReference(
                media: $media,
                file: $file,
                caption: $caption
            );
        }
        
        // Create new media
        return $this->uploadFile($file, $caption);
    }

    /**
     * Check if model has media
     */
    public function hasMedia(): bool
    {
        return $this->media()->exists();
    }

    /**
     * Get file info (for primary media)
     */
    public function getFileInfoAttribute()
    {
        $primaryMedia = $this->primaryMedia;
        
        if (!$primaryMedia) return null;
        
        return [
            'name' => $primaryMedia->file_name,
            'caption' => $primaryMedia->caption,
            'type' => $primaryMedia->mime_type,
            'size' => $primaryMedia->file_size,
            'size_formatted' => $this->formatFileSize($primaryMedia->file_size),
            'url' => $primaryMedia->full_url,
            'is_image' => $primaryMedia->isImage(),
            'is_pdf' => $primaryMedia->isPdf(),
            'extension' => $primaryMedia->getExtensionAttribute(),
            'sort_order' => $primaryMedia->sort_order
        ];
    }

    /**
     * Format file size
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    /**
     * Get all media files info
     */
    public function getAllMediaInfoAttribute()
    {
        return $this->media->map(function ($media) {
            return [
                'media_id' => $media->media_id,
                'file_name' => $media->file_name,
                'caption' => $media->caption,
                'type' => $media->mime_type,
                'size' => $media->file_size,
                'size_formatted' => $this->formatFileSize($media->file_size),
                'url' => $media->full_url,
                'is_image' => $media->isImage(),
                'is_pdf' => $media->isPdf(),
                'extension' => $media->getExtensionAttribute(),
                'sort_order' => $media->sort_order,
                'created_at' => $media->created_at->format('d/m/Y H:i')
            ];
        });
    }
}