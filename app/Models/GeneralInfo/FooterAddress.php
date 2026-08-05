<?php

namespace App\Models\GeneralInfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_line_1',
        'address_line_2',
        'phone',
        'mobile',
        'email',
        'website',
    ];

    /**
     * Get the singleton FooterAddress instance or return a default model instance.
     *
     * @return static
     */
    public static function getFooterAddress()
    {
        $address = static::first();

        if (!$address) {
            $address = new static([
                'address_line_1' => 'Head Office: Block# 3053|Hamdy Ramadan street',
                'address_line_2' => '2nd Floor #2 |El-Mearag City|Maadi|Cairo|Egypt',
                'phone' => '+20 2 24477058',
            ]);
        }

        return $address;
    }

    /**
     * Get array of non-empty formatted HTML parts for single line rendering.
     *
     * @return array
     */
    public function getPartsHtml(): string
    {
        $parts = [];

        if (!empty(trim((string) $this->address_line_1))) {
            $parts[] = trim((string) $this->address_line_1);
        }

        if (!empty(trim((string) $this->address_line_2))) {
            $parts[] = trim((string) $this->address_line_2);
        }

        if (!empty(trim((string) $this->phone))) {
            $parts[] = '<i class="ft-phone"></i> : ' . e($this->phone);
        }

        if (!empty(trim((string) $this->mobile))) {
            $parts[] = '<i class="ft-smartphone"></i> : ' . e($this->mobile);
        }

        if (!empty(trim((string) $this->email))) {
            $parts[] = '<i class="ft-mail"></i> : ' . e($this->email);
        }

        if (!empty(trim((string) $this->website))) {
            $cleanWeb = str_replace(['http://', 'https://'], '', trim((string) $this->website));
            $url = 'http://' . $cleanWeb;
            $parts[] = 'Website: <a href="' . e($url) . '" target="_blank">' . e($cleanWeb) . '</a>';
        }

        return implode(' | ', $parts);
    }

    /**
     * Get array of non-empty plain text parts for PDF/Excel single line rendering.
     *
     * @return string
     */
    public function getPartsPlainText(): string
    {
        $parts = [];

        if (!empty(trim((string) $this->address_line_1))) {
            $parts[] = trim((string) $this->address_line_1);
        }

        if (!empty(trim((string) $this->address_line_2))) {
            $parts[] = trim((string) $this->address_line_2);
        }

        if (!empty(trim((string) $this->phone))) {
            $parts[] = trim((string) $this->phone);
        }

        if (!empty(trim((string) $this->mobile))) {
            $parts[] = trim((string) $this->mobile);
        }

        if (!empty(trim((string) $this->email))) {
            $parts[] = trim((string) $this->email);
        }

        if (!empty(trim((string) $this->website))) {
            $parts[] = trim((string) $this->website);
        }

        return implode(' | ', $parts);
    }
}
