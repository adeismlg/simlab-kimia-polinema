<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'hero_badge', 'hero_title', 'hero_subtitle',
        'about_title', 'about_text',
        'feature_1_icon', 'feature_1_title', 'feature_1_description',
        'feature_2_icon', 'feature_2_title', 'feature_2_description',
        'feature_3_icon', 'feature_3_title', 'feature_3_description',
        'feature_4_icon', 'feature_4_title', 'feature_4_description',
        'contact_email', 'contact_phone', 'contact_address', 'footer_text',
    ];

    /**
     * Selalu ada satu baris (id=1). Dibuat otomatis dengan nilai default
     * dari migration kalau belum ada — jadi landing page tidak pernah kosong.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    public function features(): array
    {
        return [
            ['icon' => $this->feature_1_icon, 'title' => $this->feature_1_title, 'description' => $this->feature_1_description],
            ['icon' => $this->feature_2_icon, 'title' => $this->feature_2_title, 'description' => $this->feature_2_description],
            ['icon' => $this->feature_3_icon, 'title' => $this->feature_3_title, 'description' => $this->feature_3_description],
            ['icon' => $this->feature_4_icon, 'title' => $this->feature_4_title, 'description' => $this->feature_4_description],
        ];
    }
}
