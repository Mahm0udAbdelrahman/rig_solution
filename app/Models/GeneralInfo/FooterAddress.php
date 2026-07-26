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
                'mobile' => '+20 1032703368',
                'email' => 'rse@rigsolutionz.com',
                'website' => 'www.rigsolutionz.com',
            ]);
        }

        return $address;
    }
}
