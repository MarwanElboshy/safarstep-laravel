<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->collection->count(),
                'stats' => $this->getStats(),
            ],
        ];
    }

    /**
     * Get statistics about users
     */
    protected function getStats(): array
    {
        $stats = [
            'total' => $this->collection->count(),
            'active' => 0,
            'inactive' => 0,
            'by_role' => [],
        ];

        foreach ($this->collection as $user) {
            if ($user->status === 'active') {
                $stats['active']++;
            } else {
                $stats['inactive']++;
            }
        }

        return $stats;
    }
}
