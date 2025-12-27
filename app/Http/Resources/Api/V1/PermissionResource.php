<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $module = $this->module ?? $this->inferModuleFromName($this->name);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug ?? str_replace(' ', '_', $this->name),
            'module' => $module,
            'description' => $this->description,
            'rolesCount' => $this->roles_count ?? $this->roles()->count(),
        ];
    }

    protected function inferModuleFromName(?string $name): string
    {
        if (!$name) {
            return 'general';
        }

        // Handle dot notation (module.action)
        if (strpos($name, '.') !== false) {
            return explode('.', $name, 2)[0];
        }

        // Handle underscore notation (action_resource)
        if (strpos($name, '_') !== false) {
            $parts = explode('_', $name);
            // Return the resource part (everything after the first part)
            return implode('_', array_slice($parts, 1)) ?: $parts[0];
        }

        return 'general';
    }
}
