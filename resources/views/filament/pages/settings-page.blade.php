<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div class="mt-4">
            <x-filament::button type="submit">
                Save Settings
            </x-filament::button>
        </div>
    </form>
</x-filament::page>