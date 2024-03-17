<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\ScoreUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ScoreRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Post(
     *   path="/score",
     *   summary="Submit score based on activity type",
     *   operationId="submitScore",
     *   tags={"Score"},
     *   security = { { "Authorization": {} } },
     *   @OA\RequestBody(
     *     required=true,
     *     description="Submit score for a specific activity",
     *     @OA\JsonContent(
     *       required={"type"},
     *       @OA\Property(
     *         property="type",
     *         type="string",
     *         description="Type of activity for score submission",
     *         example="watch_video",
     *         enum={"watch_video", "complete_profile", "play_game", "upload_video"}
     *       ),
     *     )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="success", type="boolean", example=true),
     *                 @OA\Property(property="user_score", type="integer", example=230),
     *                 @OA\Property(property="points_added", type="integer", example=10),
     *                 @OA\Property(property="daily_total", type="integer", example=200),
     *                 @OA\Property(property="action", type="string", example="WatchVideo"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="success", type="boolean", example=false),
     *                 @OA\Property(property="user_score", type="integer", example=230),
     *                 @OA\Property(property="points_added", type="integer", example=0),
     *                 @OA\Property(property="daily_total", type="integer", example=200),
     *                 @OA\Property(property="action", type="string", example="WatchVideo"),
     *             ),
     *         )
     *     ),
     * )
     */

    public function index(ScoreRequest $request)
    {
        $eventInstance =  ScoreUpdated::dispatch($request->type);

        if ($eventInstance[0]['success']) {
            return $this->success($eventInstance[0]);
        }
        return $this->error('Unprocessable Entity',Response::HTTP_UNPROCESSABLE_ENTITY, $eventInstance[0]);
    }
}
