<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament;

use Closure;
use Crumbls\SubscriptionsFilament\Resources\FeatureResource\FeatureResource;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\PlanResource;
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\SubscriptionResource;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;

class SubscriptionsPlugin implements Plugin
{
    /**
     * @var array<class-string, array{label?: Closure|string, url?: Closure|string|null, icon?: string|null, typeLabel?: string|null}>
     */
    protected array $subscriberTypes = [];

    /**
     * @var array<string, bool>
     */
    protected array $resources = [
        'plans' => true,
        'subscriptions' => true,
        'features' => true,
    ];

    public function getId(): string
    {
        return 'subscriptions';
    }

    public function register(Panel $panel): void
    {
        $resources = array_filter([
            $this->resources['plans'] ? PlanResource::class : null,
            $this->resources['subscriptions'] ? SubscriptionResource::class : null,
            $this->resources['features'] ? FeatureResource::class : null,
        ]);

        if ($resources !== []) {
            $panel->resources(array_values($resources));
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static;
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = Filament::getCurrentPanel()->getPlugin('subscriptions');

        return $plugin;
    }

    public function withoutPlans(bool $disabled = true): static
    {
        $this->resources['plans'] = ! $disabled;

        return $this;
    }

    public function withoutSubscriptions(bool $disabled = true): static
    {
        $this->resources['subscriptions'] = ! $disabled;

        return $this;
    }

    public function withoutFeatures(bool $disabled = true): static
    {
        $this->resources['features'] = ! $disabled;

        return $this;
    }

    /**
     * Disable any combination of resources by id.
     *
     * @param  array<int, 'plans'|'subscriptions'|'features'>  $resources
     */
    public function withoutResources(array $resources): static
    {
        foreach ($resources as $resource) {
            if (array_key_exists($resource, $this->resources)) {
                $this->resources[$resource] = false;
            }
        }

        return $this;
    }

    /**
     * Register subscriber types so subscription tables can render real names,
     * link back to your resources, and offer a friendly type filter.
     *
     * Each entry may be:
     *  - a Closure/string: treated as the label resolver
     *  - an array: ['label' => ..., 'url' => ..., 'icon' => ..., 'typeLabel' => ...]
     *
     * @param  array<class-string, Closure|string|array{label?: Closure|string, url?: Closure|string|null, icon?: string|null, typeLabel?: string|null}>  $types
     */
    public function subscriberTypes(array $types): static
    {
        foreach ($types as $class => $config) {
            $this->subscriberTypes[$class] = is_array($config) ? $config : ['label' => $config];
        }

        return $this;
    }

    /**
     * @return array<class-string, array{label?: Closure|string, url?: Closure|string|null, icon?: string|null, typeLabel?: string|null}>
     */
    public function getSubscriberTypes(): array
    {
        return $this->subscriberTypes;
    }

    public function resolveSubscriberLabel(?Model $subscriber): string
    {
        if ($subscriber === null) {
            return '—';
        }

        $config = $this->subscriberTypes[$subscriber::class] ?? null;
        $label = $config['label'] ?? null;

        if ($label instanceof Closure) {
            return (string) $label($subscriber);
        }

        if (is_string($label) && $label !== '') {
            return (string) ($subscriber->getAttribute($label) ?? "{$this->resolveTypeLabel($subscriber::class)} #{$subscriber->getKey()}");
        }

        return "{$this->resolveTypeLabel($subscriber::class)} #{$subscriber->getKey()}";
    }

    public function resolveSubscriberUrl(?Model $subscriber): ?string
    {
        if ($subscriber === null) {
            return null;
        }

        $url = $this->subscriberTypes[$subscriber::class]['url'] ?? null;

        if ($url instanceof Closure) {
            $resolved = $url($subscriber);

            return is_string($resolved) ? $resolved : null;
        }

        return is_string($url) ? $url : null;
    }

    public function resolveSubscriberIcon(string $type): ?string
    {
        return $this->subscriberTypes[$type]['icon'] ?? null;
    }

    public function resolveTypeLabel(string $type): string
    {
        $config = $this->subscriberTypes[$type] ?? null;

        if (is_string($config['typeLabel'] ?? null) && $config['typeLabel'] !== '') {
            return $config['typeLabel'];
        }

        return class_basename($type);
    }

    /**
     * @return array<class-string, string>
     */
    public function getSubscriberTypeOptions(): array
    {
        $options = [];

        foreach (array_keys($this->subscriberTypes) as $type) {
            $options[$type] = $this->resolveTypeLabel($type);
        }

        return $options;
    }
}
