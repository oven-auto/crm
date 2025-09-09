<?php 

namespace App\Services\Credit;

use App\Models\Credit;
use DB;

Class CreditListService
{
	private $query;

	private function setQuery()
	{
		$this->query = Credit::join('credit_marks', 'credit_marks.credit_id', '=', 'credits.id');
	}

	private function setMark($mark)
	{
		$this->query->where('credit_marks.mark_id', $mark);
	}

	public function setData($data = [])
	{
		$this->setQuery();

		if(isset($data['mark_id']))
			$this->setMark($data['mark_id']);

		return $this;
	}

	public function onCurrentDate()
	{
		$date = date('y-m-d');
		if($this->query)
			$this->query->whereDate('credits.begin_at', '<', $date)->whereDate('credits.end_at', '>', $date);
		return $this;
	}

	public function onActive()
	{
		if($this->query)
			$this->query->where('credits.status', 1);
		return $this;
	}

	public function getAll()
	{
		$result = $this->query->get();
		$this->query = '';
		foreach ($result as $key => $item) {
            $item->banner = asset('storage/'.$item->banner) . '?'.date('dmYh');
        }
        return $result;
	}
}