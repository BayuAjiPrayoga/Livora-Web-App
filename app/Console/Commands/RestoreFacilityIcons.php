<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RestoreFacilityIcons extends Command
{
    protected $signature = 'fix:icons';
    protected $description = 'Generate placeholder SVG icons for facilities';

    public function handle()
    {
        $this->info('Restoring facility icons...');

        $icons = [
            'facility-icon-1.svg',
            'facility-icon-2.svg',
            'facility-icon-3.svg',
            'facility-icon-4.svg',
            'facility-icon-5.svg',
            'facility-icon-6.svg',
            'facility-icon-7.svg',
            'facility-icon-8.svg',
            'facility-icon-9.svg',
            'facility-icon-10.svg',
            'facility-icon-11.svg',
            'facility-icon-12.svg',
            'facility-icon-13.svg',
            'facility-icon-14.svg',
            'facility-icon-15.svg'
        ];

        // Ensure directory exists
        if (!Storage::disk('public')->exists('')) {
            Storage::disk('public')->makeDirectory('');
        }

        foreach ($icons as $index => $filename) {
            // Create a simple SVG placeholder
            // Random dull color
            $color = '#' . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $number = $index + 1;

            $svgContent = <<<SVG
<svg width="64" height="64" xmlns="http://www.w3.org/2000/svg">
 <g>
  <rect fill="{$color}" height="64" width="64" rx="10" ry="10"/>
  <text font-family="Arial" font-size="24" font-weight="bold" fill="#ffffff" stroke="null" text-anchor="middle" x="32" y="40">{$number}</text>
 </g>
</svg>
SVG;

            Storage::disk('public')->put($filename, $svgContent);
            $this->info("Generated {$filename}");
        }

        $this->info('All icons restored directly to storage!');
    }
}
