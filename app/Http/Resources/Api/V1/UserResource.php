<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'department_id' => $this->department_id,
            'tenant_id' => $this->tenant_id,
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            
            // Relationships
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            
            // Computed attributes
            'initials' => $this->getInitials(),
            'is_active' => $this->status === 'active',
            'avatar_color' => $this->getAvatarColor(),
        ];
    }

    /**
     * Get user initials for avatar
     */
    protected function getInitials(): string
    {
        $parts = explode(' ', $this->name);
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get avatar color based on user ID
     */
    protected function getAvatarColor(): string
    {
        $colors = [
            'from-blue-400 to-blue-600',
            'from-purple-400 to-purple-600',
            'from-pink-400 to-pink-600',
            'from-green-400 to-green-600',
            'from-yellow-400 to-yellow-600',
            'from-red-400 to-red-600',
        ];
        
        return $colors[$this->id % count($colors)];
    }
}
