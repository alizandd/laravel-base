<?php

namespace App\Http\Controllers\Api\V1\Wall;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreatePostRequest;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    use ApiResponse;
    /**
     * @OA\Post(
     *   path="/wall/posts",
     *   summary="Create a new post",
     *   operationId="createPost",
     *   tags={"Wall"},
     *   security = { { "Authorization": {} } },
     *   @OA\RequestBody(
     *     required=true,
     *     description="Create a new post with content and optional files",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         type="object",
     *         required={"content"},
     *         @OA\Property(
     *           property="content",
     *           type="string",
     *           description="The content of the post",
     *           example="This is a sample post content."
     *         ),
     *         @OA\Property(
     *           property="media",
     *           type="array",
     *           description="Optional array of files (images/videos)",
     *           @OA\Items(type="string", format="binary"),
     *         ),
     *       ),
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Post created successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="اطلاعات شما با موفقیت ثبت شد ."),
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized - Invalid or expired token",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="کد وارد شده نامعتبر یا منقضی شده است ."),
     *     )
     *   )
     * )
     */

    public function create(CreatePostRequest $request)
    {

        $post = Post::create(['user_id'=>Auth::id(),'content'=>$request->input('content')]);

        if ($request->hasFile('media')) {

            foreach ($request->file('media') as $file) {
                $filePath = $file->store('media', 'public');

                $post->media()->create([
                    'file_name' => $filePath,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);

            }
        }

        return $this->success([]);
    }
}
