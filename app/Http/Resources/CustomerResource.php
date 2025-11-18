<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'type' => $this->type,
            'nid_number' => $this->nid_number,
            'vehicle_type' => $this->vehicle_type ?? null,
            'license_number' => $this->license_number ?? null,
            'school_name' => $this->school_name ?? null,
            'class_name' => $this->class_name ?? null,
            'image_url' => $this->image ? asset('backend/images/customer/'.$this->image) : null,
            'created_by' => $this->created_by,
            'approved' => $this->approved,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
