<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'whatsapp_message',
        'status'
    ];

    // Automatically generate slug and whatsapp message
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($item) {
            // Generate slug if empty
            if (empty($item->slug)) {
                $item->slug = \Illuminate\Support\Str::slug($item->name);
            }
            
            // Generate whatsapp message if empty
            if (empty($item->whatsapp_message)) {
                $item->whatsapp_message = "Hello! I want {$item->name} for \${$item->price}";
            }
        });
        
        static::updating(function ($item) {
            // Update slug if name changed
            if ($item->isDirty('name') && empty($item->slug)) {
                $item->slug = \Illuminate\Support\Str::slug($item->name);
            }
        });
    }

    // Scope for active items
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Get image URL with fallback
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-item.jpg'); // Create this default image
    }

    // Get WhatsApp link
    public function getWhatsappLinkAttribute()
    {
        $phone = env('WHATSAPP_NUMBER', '1234567890');
        $message = $this->whatsapp_message ?: "Hello! I want {$this->name} for \${$this->price}";
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }
}