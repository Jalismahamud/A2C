<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'nid_number' => $this->nid_number,
            'address' => $this->address,
            'role' => $this->role,
            'image' => $this->image ? url('backend/images/user/' . $this->image) : null,
            'created_by' => $this->created_by,
            'approved' => $this->approved,
            'status' => $this->status,
            'balance' => $this->balance ?? 0,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
