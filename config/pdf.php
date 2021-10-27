<?php

return [
	'mode'                  => 'utf-8',
    'orientation' => 'L',
	'format'                => 'A5',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('temp/'),
    'font_path' => base_path('public/fonts/'),
    'font_data' => [
           'cairo' => [
             'R'  => 'Cairo-Light.ttf',    
             'B'  => 'Cairo-Light.ttf',          
             'I'  => 'Cairo-Light.ttf',    
             'BI' => 'Cairo-Light.ttf',
             'useOTL' => 0xFF,
             'useKashida' => 75,
        ],
         'lobster' => [
             'R'  => 'lobster-Regular.ttf',    
             'B'  => 'lobster-Regular.ttf',          
             'I'  => 'lobster-Regular.ttf',    
             'BI' => 'lobster-Regular.ttf',
             'useOTL' => 0xFF,
             'useKashida' => 75,
        ],
         'caveat' => [
             'R'  => 'Caveat-Regular.ttf',    
             'B'  => 'Caveat-Bold.ttf',          
             'I'  => 'Caveat-Medium.ttf',    
             'BI' => 'Caveat-SemiBold.ttf',  
             'useOTL' => 0xFF,
             'useKashida' => 75,
        ]
        ,
         'patrickhand' => [
             'R'  => 'PatrickHand-Regular.ttf',    
             'B'  => 'PatrickHand-Regular.ttf',          
             'I'  => 'PatrickHand-Regular.ttf',    
             'BI' => 'PatrickHand-Regular.ttf', 
             'useOTL' => 0xFF,
             'useKashida' => 75,
        ]
        ,
         'sacramento' => [
             'R'  => 'sacramento-Regular.ttf',            
             'B'  => 'sacramento-Regular.ttf',            
             'I'  => 'sacramento-Regular.ttf',            
             'BI'  => 'sacramento-Regular.ttf'
        ]
        ,
         'archivonarrow' => [
             'R'  => 'ArchivoNarrow-Medium.ttf',            
             'B'  => 'ArchivoNarrow-Medium.ttf',            
             'I'  => 'ArchivoNarrow-Medium.ttf',            
             'BI'  => 'ArchivoNarrow-Medium.ttf',
             'useOTL' => 0xFF,
             'useKashida' => 75,
        ]
        ,
         'tajawal' => [
             'R'  => 'Tajawal-Regular.ttf',            
             'B'  => 'Tajawal-Bold.ttf',            
             'I'  => 'Tajawal-Light.ttf',            
             'BI'  => 'Tajawal-Medium.ttf',
             'useOTL' => 0xFF,
             'useKashida' => 75,
        ]
        
        
    ]
];
