<?php

namespace App\Traits;

use App\Models\Media;

trait HasMedia
{
    /**
     * Relationship dengan media
     */
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'media_id');
    }

    /**
     * Get media URL
     */
    public function getMediaUrlAttribute()
    {
        return $this->media?->full_url;
    }

    /**
     * Get media thumbnail URL (untuk foto)
     */
    public function getFotoThumbnailAttribute()
    {
        if (!$this->media) {
            return asset('images/default-avatar.png');
        }
        
        if ($this->media->isImage()) {
            return $this->media_url;
        }
        
        return asset('images/default-avatar.png');
    }

    /**
     * Attach media to model
     */
    public function attachMedia(Media $media)
    {
        $this->media_id = $media->media_id;
        $this->save();
        
        // Update model_id di media jika belum ada
        if (!$media->model_id) {
            $media->update([
                'model_id' => $this->{$this->primaryKey},
                'model_type' => get_class($this)
            ]);
        }
    }

    /**
     * Detach media from model
     */
    public function detachMedia()
    {
        if ($this->media) {
            // Reset model reference di media
            $this->media->update([
                'model_id' => null,
                'model_type' => null
            ]);
        }
        
        $this->media_id = null;
        $this->save();
    }

    /**
     * Delete associated media and file
     */
    public function deleteMedia()
    {
        if ($this->media) {
            $mediaService = app(\App\Services\MediaService::class);
            $mediaService->delete($this->media);
            $this->detachMedia();
        }
    }

    /**
     * Update media (ganti file)
     */
    public function updateMedia(UploadedFile $file, string $collection = null): Media
    {
        $mediaService = app(\App\Services\MediaService::class);
        
        if ($this->media) {
            // Update existing media
            $media = $mediaService->updateForModel(
                media: $this->media,
                file: $file,
                modelType: get_class($this),
                modelId: $this->{$this->primaryKey},
                collection: $collection
            );
        } else {
            // Create new media
            $media = $mediaService->uploadForModel(
                file: $file,
                modelType: get_class($this),
                modelId: $this->{$this->primaryKey},
                collection: $collection
            );
            $this->attachMedia($media);
        }
        
        return $media;
    }

    /**
     * Check if model has media
     */
    public function hasMedia(): bool
    {
        return !is_null($this->media_id);
    }

    /**
     * Get file info
     */
    public function getFileInfoAttribute()
    {
        if (!$this->media) return null;
        
        return [
            'name' => $this->media->file_name,
            'type' => $this->media->mime_type,
            'size' => $this->media->file_size,
            'size_formatted' => $this->formatFileSize($this->media->file_size),
            'url' => $this->media->full_url,
            'is_image' => $this->media->isImage(),
            'is_pdf' => $this->media->isPdf(),
            'extension' => $this->media->getExtensionAttribute()
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
}