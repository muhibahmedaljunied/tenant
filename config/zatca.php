<?php

return [
    'letters' => [
        'min' => 3,
        'max' => 255,
    ],
    'numbers' => [
        'min' => 1,
        'max' => 1000000,
    ],
    'passwords' => [
        'min' => 6,
        'max' => 12,
    ],
    'files' => [
        'min' => 10,
        'max' => 80000,
    ],
    'business_categories' => ['IT', 'Food', 'Film Festivals'],
    'invoices_issuing_types' => [
        '1100', // 1100 for together
        '0100', // 0100 for simplified
        '1000' // 1000 for standard
    ],
    'invoices_documents_types' => [
        'simplified', // B
        'standard',
    ],
    'invoices_purpose_types' => [
        '388', // INVOICE
        '383', // DEBIT_NOTE
        '381'  // CREDIT_NOTE
    ],
    'countries_iso_codes' => [
        'SA',
    ]
];
