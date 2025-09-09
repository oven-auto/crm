<?php

namespace App\Repositories\Device;

use App\Models\Device;
use App\Http\Filters\DeviceFilter;
use App\Services\Download\DownloadImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

Class DeviceRepository
{
    private $loadService;



    public function __construct(DownloadImage $loadService)
    {
        $this->loadService = $loadService;
    }



    public function delete(Device $device)
    {
        $name = $device->name;
        $device->delete();
        return response()->json([
            'status'=>1,
            'message' => 'Оборудование '.$name.' удалено'
        ]);
    }



    public function getAll($data = [])
    {
        $query = Device::query()->forFilter();
        $filter = app()->make(DeviceFilter::class, ['queryParams' => array_filter($data)]);
        $devices = $query->filter($filter)->get();

        if(isset($data['group']) && $data['group'] == 'type')
            $devices = $devices->groupBy('sort');

        return $devices;
    }



    public function save(Device $device, $data = [])
    {
        $device->fill(Arr::except($data, ['brand_id','image']))->save();
        $device->brands()->sync($data['brand_id']);

        if (isset($data['image']) && $data['image'] instanceof UploadedFile){
            $arr['image'] = $this->loadService
                ->setFile($data['image'])
                ->setCatalog('device')
                ->setPathName($device->id)
                ->setPrefix('device')
                ->save();
            $device->image->fill($arr)->save();
        }
        return $device;
    }



	public function getByComplectationId($complect_id)
	{
		$devices = Device::leftJoin('complectation_devices', 'complectation_devices.device_id', '=', 'devices.id')
    		->where('complectation_devices.complectation_id', $complect_id)
    		->get();
    	return $devices;
	}
}
