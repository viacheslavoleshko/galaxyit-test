<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $role = $data['role'];
        unset($data['role']);

        $this->record->syncRoles($role);

        return $data;
    }
    

    protected function fillForm(): void
    {
        $data = $this->getRecord()->attributesToArray();

        $data['role'] = $this->getRecord()->getRoleNames()->first(); // получаем текущую роль

        $this->form->fill($data);
    }
}
