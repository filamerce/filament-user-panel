<x-filament-user-profile::grid-section md=2 :title="__('filament-user-profile::default.profile.personal_info.heading')" :description="__('filament-user-profile::default.profile.personal_info.subheading')">
    <x-filament::card>
        <form wire:submit.prevent="submit" class="space-y-6">

            {{ $this->form }}

            <div class="text-right">
                <x-filament::button type="submit" form="submit" class="align-right">
                    {{ __('filament-user-profile::default.profile.personal_info.submit.label') }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::card>
</x-filament-user-profile::grid-section>
