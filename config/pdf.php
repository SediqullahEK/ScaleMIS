<?php

return [
    'mode'                     => 'utf-8', // Enable UTF-8 mode
    'format'                   => 'A4',
    'default_font_size'        => '12',
    'default_font'             => 'Vazir', // Use a font that supports Persian
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 10,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'L',
    'title'                    => 'Laravel mPDF',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'show_watermark_image'     => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => '',
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'custom_font_dir'          => storage_path('fonts/'), // Directory for custom fonts
    'custom_font_data'         => [
        'Vazir' => [
            'R'  => 'Vazir-Regular.ttf', // Regular font
            'B'  => 'Vazir-Bold.ttf',   // Bold font
        ],
    ],
    'auto_language_detection'  => true, // Enable auto language detection
    'temp_dir'                 => storage_path('app'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
];
