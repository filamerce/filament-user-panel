<?php

namespace Filamerce\FilamentUserProfile;

use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filamerce\FilamentUserProfile\Livewire\BrowserSessions;
use Filamerce\FilamentUserProfile\Livewire\PersonalInfo;
use Filamerce\FilamentUserProfile\Livewire\SanctumTokens;
use Filamerce\FilamentUserProfile\Livewire\UpdatePassword;
use Filamerce\FilamentUserProfile\Pages\MyProfilePage;
use Livewire\Livewire;

class UserProfilePlugin implements Plugin
{
    use EvaluatesClosures;

    // NEW
    protected string $profilePage = MyProfilePage::class;

    protected string $slug = 'my-profile';

    protected array $profileComponents = [
        'personal_info' => PersonalInfo::class,
        'update_password' => UpdatePassword::class,
        'browser_sessions' => BrowserSessions::class,
        'sanctum_tokens' => SanctumTokens::class,
    ];

    protected bool $registerUserMenu = true;

    protected string $userMenuLabel = 'My Profile';

    protected bool $hasAvatars = false;

    protected array $sanctumAbilities = [];

    public function getId(): string
    {
        return 'filament-user-profile';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([$this->getProfilePage()]);

    }

    public function profilePage(string $page): static
    {
        $this->profilePage = $page;

        return $this;
    }

    public function getProfilePage()
    {
        return $this->profilePage;
    }

    public function profileComponents(array $components): static
    {
        $this->profileComponents = $components;

        return $this;
    }

    public function getProfileComponents(): array
    {
        return $this->profileComponents;
    }

    public function replaceProfileComponent(string $key, string $component): static
    {
        $this->profileComponents[$key] = $component;

        return $this;
    }

    public function removeProfileComponent(string $key): static
    {
        unset($this->profileComponents[$key]);

        return $this;
    }

    public function registerProfileComponent(string $key, string $component): static
    {
        $this->profileComponents[$key] = $component;

        return $this;
    }

    public function sanctumAbilities(array $abilities): static
    {
        $this->sanctumAbilities = $abilities;

        return $this;
    }

    public function getSanctumAbilities(): array
    {
        return collect($this->sanctumAbilities)->mapWithKeys(function ($item, $key) {
            $key = is_string($key) ? $key : strtolower($item);

            return [$key => $item];
        })->toArray();
    }

    public function boot(Panel $panel): void
    {
        $this->userMenuRegistration();

        $this->getRegisteredMyProfileComponents()->each(
            fn (string $component, string $key) => Livewire::component($key, $component)
        );
    }

    public function registerUserMenu(bool $condition = true, string $label = 'My Profile')
    {
        $this->registerUserMenu = $condition;
        $this->userMenuLabel = $label;

        return $this;
    }

    private function userMenuRegistration()
    {
        if ($this->registerUserMenu) {
            if (Filament::getCurrentPanel()->hasTenancy()) {
                // @phpstan-ignore-next-line
                $tenantId = request()->route()->parameter('tenant');
                if ($tenantId && $tenant = app(Filament::getCurrentPanel()->getTenantModel())::where(Filament::getCurrentPanel()->getTenantSlugAttribute() ?? 'id', $tenantId)->first()) {
                    Filament::getCurrentPanel()->userMenuItems([
                        'account' => MenuItem::make()->url($this->getProfilePage()::getUrl(panel: Filament::getCurrentPanel()->getId(), tenant: $tenant))->label($this->userMenuLabel),
                    ]);
                }
            } else {
                Filament::getCurrentPanel()->userMenuItems([
                    'account' => MenuItem::make()->url($this->getProfilePage()::getUrl())->label($this->userMenuLabel),
                ]);
            }
        }
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function getRegisteredMyProfileComponents()
    {
        $components = collect($this->getProfileComponents())
            ->each(
                fn (string $component, $key) => Livewire::component($key, $component)
            )
            ->filter(
                fn (string $component) => $component::canView()
            )
            ->sortBy(
                fn (string $component) => $component::getSort()
            );

        return $components;
    }
}
