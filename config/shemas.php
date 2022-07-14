<?php 
return [
    'CASINO' => [
        'rating' => [
            'type' => 'number',
            'default' => 0
        ],
        'min_deposit' => [
            'type' => 'string',
            'default' => ''
        ],
        'min_payout' => [
            'type' => 'string',
            'default' => ''
        ],
        'faq' => [
            'type' => 'json',
            'default' => []
        ],
        'ref' => [
            'type' => 'json',
            'default' => []
        ],
        'exchange' => [
            'type' => 'json',
            'default' => []
        ],
        'events' => [
            'type' => 'json',
            'default' => []
        ],
        'slot_category' => [
            'type' => 'json',
            'default' => []
        ],
        'payment_methods' => [
            'type' => 'json',
            'default' => []
        ],
        'payment_out_methods' => [
            'type' => 'json',
            'default' => []
        ],
        'licenses' => [
            'type' => 'json',
            'default' => []
        ]
        ],
        'BONUS' => [
            'rating' => [
                'type' => 'number',
                'default' => 0
            ],
            'ref' => [
                'type' => 'json',
                'default' => []
            ]
        ],
        'GAME' => [
            'iframe' => [
                'type' => 'string',
                'default' => ''
            ]
        ],
        'POKER' => [
            'faq' => [
                'type' => 'json',
                'default' => []
            ],
        ],
        'BETTING' => [
            'rating' => [
                'type' => 'number',
                'default' => 0
            ],
            'ref' => [
                'type' => 'json',
                'default' => []
            ]
        ],
        'NEWS' => [
            'autor' => [
                'type' => 'string',
                'default' => ''
            ]
        ],
        'VENDOR' => [
            'rating' => [
                'type' => 'number',
                'default' => 0
            ]
        ]
];