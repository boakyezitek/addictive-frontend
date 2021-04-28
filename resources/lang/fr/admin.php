<?php

return [
    'admin' => 'Administrateur',
    'admins' => 'Administrateurs',

    // Crud
    'index' => 'Liste des Administrateurs',
    'create' => 'Nouvel Administrateur',

    'plans' => [
        'platforms' => [
            'ios' => 'iOS',
            'android' => 'Android',
            'web' => 'Web'
        ],
        'intervals' => [
            'day' => 'Jour',
            'week' => 'Semaine',
            'monthly' => 'Mois',
            'yearly' => 'Année'
        ]
    ],

    'subscriptions' => [
        'statuses' => [
            'canceled' => 'Annulé',
            'paused' => 'Mis en pause',
            'waiting_confirmation' => 'En attente de confirmation',
            'in_progress' => 'En cours',
            'refund' => 'Remboursé'
        ]
    ],

    'credit_packs' => [
        'platforms' => [
            'ios' => 'iOS',
            'android' => 'Android',
            'web' => 'Web'
        ],
    ],

    'credit_purchases' => [
        'statuses' => [
            'success' => 'Succès',
            'failed' => 'Échoué',
            'refund' => 'Remboursé'
        ]
    ],

    'orders' => [
        'statuses' => [
            'success' => 'Succès',
            'failed' => 'Échoué',
            'waiting_confirmation' => 'En attente',
            'refund' => 'Remboursé'
        ]
    ],

    'transactions' => [
        'types' => [
            'credit_purchases' => 'Achat de crédit',
            'subscriptions' => 'Abonnement'
        ]
    ],

    'events' => [
        'updated' => 'Maj',
        'created' => 'Création',
        'deleted' => 'Suppression'
    ]
];
