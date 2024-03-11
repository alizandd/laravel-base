<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'content' => $this->content,
            'user' =>UserResource::make($this->user),
            'media' => MediaResource::collection($this->whenLoaded('media')), // Assuming you have a MediaResource
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'likes_count' => $this->whenLoaded('likes', function () {
                return $this->likes->count();
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            //'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
