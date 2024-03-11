<?php

namespace App\Http\Controllers\Api\V1\Wall;

use App\Events\ReportSubmitted;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ReportRequest;
use App\Http\Resources\V1\PostResource;
use App\Models\Report;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    use ApiResponse;
    /**
     * @OA\Post(
     *   path="/wall/report",
     *   summary="Report a post or comment",
     *   operationId="reportItem",
     *   tags={"Wall"},
     *   security = { { "Authorization": {} } },
     *   @OA\RequestBody(
     *     required=true,
     *     description="Report a post or comment with a reason",
     *     @OA\JsonContent(
     *       required={"type", "id", "reason"},
     *       @OA\Property(
     *         property="type",
     *         type="string",
     *         description="Type of the item being reported",
     *         example="Post",
     *         enum={"Post", "Comment"}
     *       ),
     *       @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="ID of the item being reported",
     *         example=1
     *       ),
     *       @OA\Property(
     *         property="reason",
     *         type="string",
     *         description="Reason for reporting",
     *         example="This is inappropriate content."
     *       ),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Report submitted successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="Successfully Report"),
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

    public function store(ReportRequest $request)
    {
        $type = 'App\Models\\' . $request->type;
        $reportable = $type::find($request->id);

        if (!$reportable) {
            return $this->error('Content not found',404);
        }

        $report = $reportable->reports()->create(['reason' => $request->reason, 'user_id' => Auth::id()]);

        event(new ReportSubmitted($report));

        return $this->success([],__('messages.submit_success'));
    }

}
