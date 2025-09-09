<?php

namespace App\Repositories\Worksheet\CommentHistory;

Class HistoryRepository
{
    public function getWorksheetHistory(string $class, array $data)
    {
        if(!class_exists($class))
            throw new \Exception('Не могу создать историю по данному запросу');

        $model = new $class;

        if($model instanceof \App\Models\WorksheetActionComment)
        {
            $comments = $this->getWorksheetActionComment($model, $data);
            $result = $comments->map(fn($item) => new WorksheetCommentData($item));
            return $result;
        }

        elseif($model instanceof \App\Models\SubActionComment)
        {
            $comments = $this->getWorksheetSubActionComment($model, $data);
            $result = $comments->map(fn($item) => new SubActionCommentData($item));
            return $result;
        }

        elseif($model instanceof \App\Models\WSMRedemptionComment)
        {
            $comments = $this->getRedemptionComment($model, $data);
            $result = $comments->map(fn($item) => new RedemptionCommentData($item));
            return $result;
        }

        elseif($model instanceof \App\Models\WsmReserveComment)
        {
            $comments = $this->getReserveComment($model, $data);
            $result = $comments->map(fn($item) => new ReserveCommentData($item));
            return $result;
        }
    }



    private function getWorksheetActionComment($model, $param)
    {
        $data = $model->select('worksheet_action_comments.*')
            ->with(['author','action'])
            ->leftJoin('worksheet_actions', 'worksheet_actions.id', 'worksheet_action_comments.action_id')
            ->where('worksheet_actions.worksheet_id', $param['worksheet_id'])
            ->orderBy('worksheet_action_comments.id','DESC')
            ->get();

        return $data;
    }



    private function getWorksheetSubActionComment($model, $param)
    {
        $data = $model->select('sub_action_comments.*')
            ->with(['author'])
            ->leftJoin('sub_actions', 'sub_actions.id', 'sub_action_comments.sub_action_id')
            ->where('sub_actions.worksheet_id', $param['worksheet_id'])
            ->get();

        return $data;
    }

    

    public function getRedemptionComment($model, $param)
    {
        $data = $model->select('wsm_redemption_comments.*')
            ->with('author')
            ->leftJoin('wsm_redemption_cars', 'wsm_redemption_cars.id', 'wsm_redemption_comments.redemption_car_id')
            ->where('wsm_redemption_cars.worksheet_id', $param['worksheet_id'])
            ->get();

        return $data;
    }



    public function getReserveComment($model, $param)
    {
        $data = $model->select('wsm_reserve_comments.*')
            ->with('author')
            ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.id', 'wsm_reserve_comments.reserve_id')
            ->where('wsm_reserve_new_cars.worksheet_id', $param['worksheet_id'])
            ->get();

        return $data;
    }
}
