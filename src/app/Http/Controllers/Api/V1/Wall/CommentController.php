<?php

namespace App\Http\Controllers\Api\V1\Wall;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCommentRequest;
use App\Http\Resources\V1\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use ApiResponse;
    /**
     * @OA\Post(
     *     path="/wall/posts/{postId}/comments",
     *     summary="Submit a comment on a post with optional media",
     *     operationId="storeCommentWithMedia",
     *     tags={"Wall"},
     *     security = { { "Authorization": {} } },
     *     @OA\Parameter(
     *         name="postId",
     *         description="ID of the post to comment on",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Submit a comment with or without media files.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="content",
     *                     description="Comment content",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="media[]",
     *                     description="Media files",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */

    public function store(StoreCommentRequest $request, Post $post)
    {
        $comment = $post->comments()->create(['content'=> $request->input('content'),'user_id'=>Auth::id()]);
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $filePath = $file->store('media/comment', 'public');
                $comment->media()->create([
                    'file_name' => $filePath,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }
        $post->load(['media','comments.user','comments.likes','comments.media','likes']);
        return $this->success(PostResource::make($post),__('messages.submit_success'));
    }

}
