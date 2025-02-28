# Filament User Panel

This package is inspired by [Filament Breezy](https://github.com/jeffgreco13/filament-breezy) but does not include 2FA functionality.

## Installation

To install the package, run the following command:

```sh
composer require filamerce/filament-user-panel
```

## Register Plugin

To register the plugin, use the following code:

```php
use Filamerce\FilamentUserProfile\UserProfilePlugin;

$panel->plugins([
    UserProfilePlugin::make()
]);
```

## Options

### Register User Menu Item

Control whether the plugin should automatically register the user menu item:

```php
UserProfilePlugin::make()
    ->registerUserMenu(false);
```

### Custom Profile Page

Replace the entire `ProfilePage` with your own component:

```php
UserProfilePlugin::make()
    ->profilePage(MyProfileComponent::class);
```

### Custom Profile Components

Take control over the registered components and their order:

```php
use Filamerce\FilamentUserProfile\Livewire\PersonalInfo;
use Filamerce\FilamentUserProfile\Livewire\UpdatePassword;

UserProfilePlugin::make()
    ->profileComponents([
        'personal_info' => PersonalInfo::class,
        'update_password' => UpdatePassword::class,
    ]);
```
### Replace Profile Component

```php
use My\Component\PersonalInfo;

UserProfilePlugin::make()
    ->replaceProfileComponent('personal_info', PersonalInfo::class);
```

### Register New Profile Component

```php
use My\Component\SomeComponent;

UserProfilePlugin::make()
    ->replaceProfileComponent('some_component', SomeComponent::class);
```

### Remove Profile Component

```php
use My\Component\SomeComponent;

UserProfilePlugin::make()
    ->removeProfileComponent('personal_info');
```

## Laravel Sanctum

Laravel Sanctum is detected automatically, and a component to manage Sanctum tokens is displayed in the profile. You can control the available abilities with the following code:

```php
UserProfilePlugin::make()
    ->sanctumAbilities(['read', 'write']);
```

By default, all tokens are registered with all abilities (`[*]`).
