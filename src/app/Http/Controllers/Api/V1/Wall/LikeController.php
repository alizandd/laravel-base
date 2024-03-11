<?php

namespace App\Http\Controllers\Api\V1\Wall;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LikeRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Post(
     *   path="/wall/likes/toggle",
     *   summary="Toggle like on a post or comment",
     *   operationId="toggleLike",
     *   tags={"Wall"},
     *   security = { { "Authorization": {} } },
     *   @OA\RequestBody(
     *     required=true,
     *     description="Toggle like on an item",
     *     @OA\JsonContent(
     *       required={"type", "id"},
     *       @OA\Property(
     *         property="type",
     *         type="string",
     *         description="Type of the item to like",
     *         example="Post",
     *         enum={"Post", "Comment"}
     *       ),
     *       @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="ID of the item to like",
     *         example=1
     *       ),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Like toggled successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="Successfully liked"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized - Invalid or missing token",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="Unauthorized ."),
     *     )
     *   )
     * )
     */

    public function toggleLike(LikeRequest $request)
    {

        $type = 'App\Models\\' . $request->type;
        $likeable = $type::find($request->id);

        if (!$likeable) {
            return $this->error('Content not found',404);
        }

        $like = $likeable->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            // If already liked, unlike it
            $like->delete();
            return $this->success(['type'=>'unliked'],'Successfully unliked');
        } else {
            // If not liked, like it
            $likeable->likes()->create(['user_id' => Auth::id()]);
            return $this->success(['type'=>'liked'],'Successfully liked');
        }
    }
}
