<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Pages;

use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\SubscriptionResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewSubscription extends ViewRecord
{
    protected static string $resource = SubscriptionResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Subscription')
                ->columns(3)
                ->schema([
                    TextEntry::make('plan.name')
                        ->label('Plan'),

                    TextEntry::make('slug'),

                    TextEntry::make('subscriber_type')
                        ->label('Subscriber Type')
                        ->formatStateUsing(fn (string $state) => class_basename($state)),

                    TextEntry::make('subscriber_id')
                        ->label('Subscriber ID'),

                    TextEntry::make('starts_at')
                        ->dateTime(),

                    TextEntry::make('ends_at')
                        ->dateTime(),

                    TextEntry::make('trial_ends_at')
                        ->dateTime()
                        ->placeholder('No trial'),

                    TextEntry::make('canceled_at')
                        ->dateTime()
                        ->placeholder('Not canceled'),

                    TextEntry::make('created_at')
                        ->dateTime(),
                ]),

            Section::make('Feature Usage')
                ->schema(function ($record) {
                    $entries = [];
                    foreach ($record->usage()->with('feature')->get() as $usage) {
                        $featureName = $usage->feature?->name ?? "Feature #{$usage->feature_id}";
                        $entries[] = TextEntry::make("usage_{$usage->id}")
                            ->label($featureName)
                            ->getStateUsing(fn () => "{$usage->used} used" . ($usage->valid_until ? " (resets {$usage->valid_until->format('M j, Y')})" : ''));
                    }

                    return $entries ?: [
                        TextEntry::make('no_usage')
                            ->label('')
                            ->getStateUsing(fn () => 'No feature usage recorded.'),
                    ];
                }),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancel')
                ->label('Cancel Subscription')
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('Cancel this subscription immediately?')
                ->action(fn () => $this->record->cancel(immediately: true))
                ->visible(fn () => $this->record->active() && ! $this->record->canceled()),

            Action::make('reactivate')
                ->label('Reactivate')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn () => $this->record->reactivate())
                ->visible(fn () => $this->record->pendingCancellation()),

            Action::make('renew')
                ->label('Renew')
                ->color('warning')
                ->requiresConfirmation()
                ->action(fn () => $this->record->renew())
                ->visible(fn () => $this->record->ended() && ! $this->record->canceled()),
        ];
    }
}
