<?php

namespace App\Services\Car;
use App\Models\Car;

Class CarSearchService
{
	private $query = '';
	private $params = [];
	private $count = 15;

	private function init()
	{
		$this->query = Car::select('cars.*')
            ->with(['brand','complectation', 'mark', 'color', 'price']);
        $this->query->leftJoin('car_prices', 'car_prices.car_id', '=', 'cars.id');
	}

	private function setParam($data = [])
	{
		$this->params = $data;
		if(isset($data['count']))
			$this->count = $data['count'];
        if(isset($data['order']) && $data['order']!="")
            if($data['order'] == 'min')
                $this->query->orderBy('car_prices.full_price', 'ASC');
            else if($data['order'] == 'max')
                $this->query->orderBy('car_prices.full_price', 'DESC');
        if(isset($data['car_ids']) && $data['car_ids'])
            $this->query->whereIn('cars.id', $data['car_ids']);
	}

	public function prepare($data = []) 
	{
		$this->init();
		$this->setParam($data);
		$this->searchMark();
		$this->searchComplectation();
		$this->searchVin();
		$this->searchPrice();
		$this->searchMotor();
		$this->searchDevices();
		
		return $this;
    }

    public function paginate()
    {
    	$search = $this->query->paginate($this->count);
    	return $search;
    }

    public function get() 
    {
        $search = $this->query->get();
        return $search;
    }

    private function searchMark()
    {
    	if(isset($this->params['mark']))
            $this->query->where('cars.mark_id', $this->params['mark']);
    }
        
    private function searchComplectation()
    {
    	if(isset($this->params['complectation_id']))
    		$this->query->where('complectation_id', $this->params['complectation_id']);
    }
        
    private function searchVin()
    {
        if(isset($this->params['vin']))
            $this->query->where('vin', 'like', '%'.$this->params['vin'].'%');
    }
    
    private function searchPrice()
    {
        if(isset($this->params['minPrice']) || isset($this->params['maxPrice'])) {
            
            if(isset($this->params['minPrice']))
                $this->query->where('car_prices.full_price', '>=', $this->params['minPrice']);
            if(isset($this->params['maxPrice']))
                $this->query->where('car_prices.full_price', '<=', $this->params['maxPrice']);
        }
    }

    private function searchDevices()
    {
        if(isset($this->params['devices']) && is_array($this->params['devices'])) {
            $deviceCount = count($this->params['devices']);
            $this->query->leftJoin('car_equipments', 'car_equipments.car_id', '=', 'cars.id');            
            $this->query->groupBy('cars.id');
            $this->query->having(\DB::Raw('count(cars.id)'), '>=', $deviceCount);
            $this->query->whereIn('car_equipments.device_filter_id', $this->params['devices']);
        }
	}

    private function searchMotor()
    {
        if(isset($this->params['transmission']) || isset($this->params['driver'])) {
            $this->query->leftJoin('complectations', 'complectations.id', '=', 'cars.complectation_id');
            $this->query->leftJoin('motors', 'motors.id', '=', 'complectations.motor_id');
            
            if(isset($this->params['transmission'])) {
                $this->query->leftJoin('motor_transmissions', 'motor_transmissions.id', '=', 'motors.motor_transmission_id');
                $this->query->where('motor_transmissions.transmission_type_id', $this->params['transmission']);
            }

            if(isset($this->params['driver'])) {
                $this->query->leftJoin('motor_drivers', 'motor_drivers.id', '=', 'motors.motor_driver_id');
                $this->query->where('motor_drivers.driver_type_id', $this->params['driver']);
            }
        }
    }    
}