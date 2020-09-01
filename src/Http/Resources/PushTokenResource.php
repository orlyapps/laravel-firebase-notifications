<?php

namespace Orlyapps\LaravelFirebaseNotifications\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PushTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
