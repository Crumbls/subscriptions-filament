<?php

declare(strict_types=1);

return [

    'navigation' => [
        'group' => 'Subscriptions',
    ],

    'resources' => [
        'plan' => [
            'label' => 'Plan',
            'plural_label' => 'Plans',
        ],
        'feature' => [
            'label' => 'Feature',
            'plural_label' => 'Features',
        ],
        'subscription' => [
            'label' => 'Subscription',
            'plural_label' => 'Subscriptions',
        ],
    ],

    'common' => [
        'unlimited' => 'Unlimited',
        'never' => 'Never',
        'no_subscriber_types' => 'No subscriber types registered. Pass them via SubscriptionsPlugin::make()->subscriberTypes([...]).',
    ],

    'plan' => [
        'sections' => [
            'details' => 'Plan Details',
            'status' => 'Status',
            'pricing' => 'Pricing',
        ],
        'fields' => [
            'name' => 'Name',
            'description' => 'Description',
            'slug' => 'Slug',
            'is_active' => 'Active',
            'active_subscribers_limit' => 'Subscriber Limit',
            'sort_order' => 'Sort Order',
            'currency' => 'Currency',
            'price' => 'Price',
            'signup_fee' => 'Signup Fee',
            'invoice_period' => 'Invoice Period',
            'invoice_interval' => 'Invoice Interval',
            'trial_period' => 'Trial Period',
            'trial_interval' => 'Trial Interval',
            'grace_period' => 'Grace Period',
            'grace_interval' => 'Grace Interval',
        ],
        'columns' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'price' => 'Price',
            'billing_cycle' => 'Billing Cycle',
            'subscribers' => 'Subscribers',
            'active' => 'Active',
            'created_at' => 'Created',
        ],
        'filters' => [
            'active' => 'Active',
        ],
    ],

    'feature' => [
        'sections' => [
            'details' => 'Feature Details',
            'reset_cycle' => 'Reset Cycle',
        ],
        'fields' => [
            'name' => 'Name',
            'description' => 'Description',
            'slug' => 'Slug',
            'slug_helper' => 'Auto-generated from name if left blank.',
            'resettable_period' => 'Reset Every',
            'resettable_interval' => 'Reset Interval',
        ],
        'columns' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'plans' => 'Plans',
            'resets' => 'Resets',
            'created_at' => 'Created',
        ],
    ],

    'subscription' => [
        'fields' => [
            'plan' => 'Plan',
            'slug' => 'Slug',
            'subscriber' => 'Subscriber',
            'subscriber_type' => 'Subscriber Type',
            'subscriber_id' => 'Subscriber ID',
            'name' => 'Name',
            'description' => 'Description',
            'starts_at' => 'Starts At',
            'ends_at' => 'Ends At',
            'trial_ends_at' => 'Trial Ends At',
            'canceled_at' => 'Canceled At',
            'created_at' => 'Created',
        ],
        'columns' => [
            'subscriber' => 'Subscriber',
            'subscriber_type' => 'Subscriber Type',
            'plan' => 'Plan',
            'slug' => 'Slug',
            'starts_at' => 'Starts At',
            'ends_at' => 'Ends At',
            'status' => 'Status',
            'canceled_at' => 'Canceled At',
            'created_at' => 'Created',
        ],
        'placeholders' => [
            'no_trial' => 'No trial',
            'not_canceled' => 'Not canceled',
            'no_usage' => 'No feature usage recorded.',
        ],
        'view' => [
            'sections' => [
                'details' => 'Subscription',
                'usage' => 'Feature Usage',
            ],
            'usage_state' => ':used used',
            'usage_state_with_reset' => ':used used (resets :date)',
            'feature_fallback' => 'Feature #:id',
        ],
        'statuses' => [
            'active' => 'Active',
            'trial' => 'Trial',
            'grace' => 'Grace',
            'canceled' => 'Canceled',
            'ended' => 'Ended',
        ],
        'actions' => [
            'cancel' => [
                'label' => 'Cancel',
                'header_label' => 'Cancel Subscription',
                'modal_description' => 'Cancel this subscription immediately?',
            ],
            'reactivate' => [
                'label' => 'Reactivate',
            ],
            'renew' => [
                'label' => 'Renew',
            ],
        ],
    ],

    'relation_managers' => [
        'features' => [
            'columns' => [
                'name' => 'Name',
                'resets' => 'Resets',
                'value' => 'Value',
            ],
            'fields' => [
                'value' => 'Value',
                'value_helper' => 'Numeric limit, or "true"/"false" for boolean features.',
            ],
        ],
        'subscriptions' => [
            'title' => 'Subscriptions',
        ],
    ],

];
