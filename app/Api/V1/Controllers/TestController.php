<?php

namespace App\Api\V1\Controllers;

use App\Models\TrnMessage;
use App\Models\TrnMessageTarget;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->responseSuccess(['api' => "I'm from v1 api."]);
    }

    public function requestMessages()
    {
        $params = request()->all();
        $targetId = $params['target_id'] ?? 3;
        $MAX_DISPLAY = 2;
        $readStatus = false;
        $allowInsert = true;

        // check messages whether they are 'read' or 'unread' statuses?
        $idsMessage = $params['message_ids'] ?? null;
        if (!empty($idsMessage)) {
            if (!is_array($idsMessage)) {
                $idsMessage = explode(',', $idsMessage);
            }
            $messagesTarget = TrnMessageTarget::where([
                    'target_id' => $targetId,
                    'status' => 'UNREAD'
                ])
                ->whereIn('message_id', $idsMessage)
                ->get()
                ->toArray();
            // any records
            if (!empty($messagesTarget)) {
                $allowInsert = false;
                foreach ($messagesTarget as $row) {
                    $paramsUpdate = [
                        'read_count' => $row['read_count'] + 1
                    ];
                    if ($row['read_count'] + 1 == $MAX_DISPLAY) {
                        $paramsUpdate['read_count'] =  $MAX_DISPLAY;
                        $paramsUpdate['status'] = 'READ';
                        $readStatus = true;
                    }
                    TrnMessageTarget::where([
                        'message_id' => $row['message_id'],
                        'target_id' => $targetId,
                        'status' => 'UNREAD'
                    ])->update($paramsUpdate);
                }
            }
        }

        $sql = "
            SELECT
                m.*,
                t.title,
                t.content,
                t.content_short,
                t.url,
                t.navigation,
                t.metadata,
                t.locale
            FROM trn_message as m
            LEFT JOIN trn_message_transaltion AS t ON (t.message_id = m.id)
            WHERE
                m.deleted_at IS NULL
                AND
                t.locale = :locale
                AND
                NOT EXISTS (
                    SELECT * FROM trn_message_target as tmt
                    WHERE
                        tmt.message_id = m.id AND
                        tmt.target_id = :target_id AND
                        tmt.status = 'READ' AND
                        tmt.deleted_at IS NULL
                )
        ";
        $binding = [
            'locale' => 'en',
            'target_id' => $targetId
        ];

        // if (!empty($params['last_receive_time'])) {
        //     $sql .= " AND m.updated_at >= :last_receive_time ";
        //     $binding['last_receive_time'] = $params['last_receive_time'];
        // }

        $sql .= " ORDER BY m.updated_at DESC";

        $result = DB::select($sql, $binding);

        return $this->responseSuccess($result);

        // $lastReceiveTime = $params['last_receive_time'] ?? null;
        $data = [];
        // Only get 1 rows
        if (!empty($result)) {
            $data[] = $result[0];
        }
        // if (!empty($data)) {
        //     $lastReceiveTime = $data[0]->updated_at;
        // }

        // Track messages sent
        if (!empty($data) && $allowInsert) {
            $dataInsert = array_map(function ($item) use ($targetId) {
                return [
                    'message_id' => $item->id,
                    'target_id' => $targetId,
                    'read_count' => 0,
                    'status' => 'UNREAD'
                ];
            }, $data);
            TrnMessageTarget::insert($dataInsert);
        }

        return $this->responseSuccess([
            // 'last_receive_time' => $lastReceiveTime,
            'data' => $data
        ]);
    }

    public function requestReadMessages()
    {
        $params = request()->all();
        $targetId = $params['target_id'];
        $idsMessage = $params['message_ids'];
        if (!is_array($idsMessage)) {
            $idsMessage = explode(',', $idsMessage);
        }
        TrnMessageTarget::where('target_id', $targetId)
            ->whereIn('message_id', $idsMessage)
            ->update(['status' => 'READ']);

        return $this->responseSuccess(null);
    }
}
