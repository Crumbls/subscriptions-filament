<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Pages;

use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\SubscriptionResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewSubscription extends ViewRecord
{
    protected static string $resource = SubscriptionResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('subscriptions-filament::subscriptions-filament.subscription.view.sections.details'))
                ->columns(3)
                ->schema([
                    TextEntry::make('plan.name')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.plan')),

                    TextEntry::make('slug')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.slug')),

                    TextEntry::make('subscriber_type')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.subscriber_type'))
                        ->formatStateUsing(fn (string $state): string => class_basename($state)),

                    TextEntry::make('subscriber_id')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.subscriber_id')),

                    TextEntry::make('starts_at')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.starts_at'))
                        ->dateTime(),

                    TextEntry::make('ends_at')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.ends_at'))
                        ->dateTime(),

                    TextEntry::make('trial_ends_at')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.trial_ends_at'))
                        ->dateTime()
                        ->placeholder(__('subscriptions-filament::subscriptions-filament.subscription.placeholders.no_trial')),

                    TextEntry::make('canceled_at')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.canceled_at'))
                        ->dateTime()
                        ->placeholder(__('subscriptions-filament::subscriptions-filament.subscription.placeholders.not_canceled')),

                    TextEntry::make('created_at')
                        ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.created_at'))
                        ->dateTime(),
                ]),

            Section::make(__('subscriptions-filament::subscriptions-filament.subscription.view.sections.usage'))
                ->schema(function ($record): array {
                    $entries = [];

                    foreach ($record->usage()->with('feature')->get() as $usage) {
                        $featureName = $usage->feature?->name ?? __(
                            'subscriptions-filament::subscriptions-filament.subscription.view.feature_fallback',
                            ['id' => $usage->feature_id],
                        );

                        $state = $usage->valid_until
                            ? __('subscriptions-filament::subscriptions-filament.subscription.view.usage_state_with_reset', [
                                'used' => $usage->used,
                                'date' => $usage->valid_until->format('M j, Y'),
                            ])
                            : __('subscriptions-filament::subscriptions-filament.subscription.view.usage_state', [
                                'used' => $usage->used,
                            ]);

                        $entries[] = TextEntry::make("usage_{$usage->id}")
                            ->label($featureName)
                            ->getStateUsing(fn (): string => $state);
                    }

                    return $entries ?: [
                        TextEntry::make('no_usage')
                            ->label('')
                            ->getStateUsing(fn (): string => __('subscriptions-filament::subscriptions-filament.subscription.placeholders.no_usage')),
                    ];
                }),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancel')
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.actions.cancel.header_label'))
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription(__('subscriptions-filament::subscriptions-filament.subscription.actions.cancel.modal_description'))
                ->action(fn () => $this->record->cancel(immediately: true))
                ->visible(fn (): bool => $this->record->active() && ! $this->record->canceled()),

            Action::make('reactivate')
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.actions.reactivate.label'))
                ->color('success')
                ->requiresConfirmation()
                ->action(fn () => $this->record->reactivate())
                ->visible(fn (): bool => $this->record->pendingCancellation()),

            Action::make('renew')
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.actions.renew.label'))
                ->color('warning')
                ->requiresConfirmation()
                ->action(fn () => $this->record->renew())
                ->visible(fn (): bool => $this->record->ended() && ! $this->record->canceled()),
        ];
    }
}
