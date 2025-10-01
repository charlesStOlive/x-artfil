<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'cms_pages';
    
    protected $fillable = [
        'titre',
        'meta_data',
        'contents',
        'statics',
        'key_word',
        'slug',
        'status',
        'is_homepage',
        'is_in_header',
        'is_in_footer',
        'published_at',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'contents' => 'array',
        'statics' => 'array',
        'is_homepage' => 'boolean',
        'is_in_header' => 'boolean',
        'is_in_footer' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Définir cette page comme page d'accueil (exclut les autres)
     */
    public function setAsHomepage(): void
    {
        if ($this->is_homepage) {
            // Retirer le statut homepage de toutes les autres pages
            static::where('id', '!=', $this->id)
                ->update(['is_homepage' => false]);
        }
    }

    /**
     * Boot method pour gérer les événements du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($page) {
            if ($page->isDirty('is_homepage') && $page->is_homepage) {
                // Si on définit is_homepage à true, retirer des autres
                static::where('id', '!=', $page->id)
                    ->update(['is_homepage' => false]);
            }
        });
    }
}
