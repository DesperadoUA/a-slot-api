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
        ],
        'active_languages' => [
            'type' => 'json',
            'default' => []
        ],
        'convenience' => [
            'type' => 'number',
            'default' => 0
        ],
        'replenishment' => [
            'type' => 'number',
            'default' => 0
        ],
        'support' => [
            'type' => 'number',
            'default' => 0
        ],
        'actions' => [
            'type' => 'number',
            'default' => 0
        ],
        ],
    'BONUS' => [
        'rating' => [
                'type' => 'number',
                'default' => 0
            ],
        'ref' => [
                'type' => 'json',
                'default' => []
        ],
        'bonus_system' => [
            'type' => 'json',
            'default' => []
        ],
        'characters' => [
            'type' => 'json',
            'default' => []
        ],
        'value' => [
            'type' => 'string',
            'default' => ''
        ]
        ],
    'GAME' => [
            'iframe' => [
                'type' => 'string',
                'default' => ''
            ],
            'special_ref' => [
                'type' => 'json',
                'default' => []
            ],
            'characteristics' => [
                'type' => 'json',
                'default' => []
            ],
            'symbols' => [
                'type' => 'json',
                'default' => []
            ],
            'gallery' => [
                'type' => 'json',
                'default' => []
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
            ],
            'ref' => [
                'type' => 'json',
                'default' => []
            ],
        ],
    'VENDOR' => [
            'rating' => [
                'type' => 'number',
                'default' => 0
            ],
            'licenses' => [
                'type' => 'json',
                'default' => []
            ],
            'characteristics' => [
                'type' => 'json',
                'default' => []
            ]
    ],
    'SHARE' => [
        'faq' => [
            'type' => 'json',
            'default' => []
        ]
    ]
];