<?php

namespace App\Services\Form;
use App\Models\Form;
use Illuminate\Http\UploadedFile;
use App\Models\FormSection;
use Storage;
use App\Models\Widget;
use App\Services\Form\WidgetRepository;

Class FormRepository
{
    private $widgetService;

    public function __construct(WidgetRepository $repo)
    {
        $this->widgetService = $repo;
    }

    public function saveRecipient($data)
    {

    }

    public function saveBodies($data)
    {

    }

    public function save(Form $form, $data = [])
    {
        $result = [];
        try {
            $section = FormSection::find($data['form_section_id']);
            $form->brand_id = $section->brand_id;

            $form->fill(array_diff_key($data, array_flip(['bodies','recipients','widget'])));
            $form->sort = Form::max('sort')+1;
            $form->save();

            if( ($data['recipients']))
                $form->recipients()->sync(explode(',', $data['recipients']));

            if( ($data['bodies']))
                $form->bodies()->sync(explode(',', $data['bodies']));

            $this->widgetService->saveWidget($form, $data['widget']);

             $result = ['status' => 1, 'data'=>$form];
        } catch(\Exception $e) {
            $result = ['status' => 0, 'message' => $e->getMessage(), 'data'=>[]];
        }
        return $result;
    }

    public function getFormById($id)
    {
        $form = Form::find($id);

        $data = $form->toArray();
        $data['bodies'] = $form->bodies->pluck('id');
        $data['recipients'] = $form->recipients->pluck('id');
        $data['widget'] = $form->widget;
        $data['widget']['banner'] = $form->widget->banner;
        $data['widget']['banner']->image = $data['widget']['banner']->image ? asset('storage/'.$data['widget']['banner']->image) . '?'.date('dmYh') : '';
        $data['widget']['body'] = $form->widget->body;
        $data['widget']['badges'] = $form->widget->badges;
        foreach($data['widget']['badges'] as $item)
            $item->urlimage = $item->image ? asset('storage/'.$item->image)  : '';
        return $data;
    }

    public function findBy($data = [])
    {
        $query = Form::select('forms.*')->with('widget');

    	if(isset($data['id']))
    		$query->where('forms.id',$data['id']);

        if(isset($data['brand_id']))
            $query->where('forms.brand_id', $data['brand_id']);

        if(isset($data['form_event']))
            $query->leftJoin('form_events', 'form_events.id', 'forms.form_event_id')
                ->where('form_events.name', $data['form_event']);

        $form = $query->first();
        $form->banner = $form->banner ? asset('storage/'.$form->banner) . '?'.date('dmYh') : '';
        $result = $form->toArray();
        $result['bodies'] = $form->bodies->groupBy('form_controll_group_id');

        return $result;
    }
}
