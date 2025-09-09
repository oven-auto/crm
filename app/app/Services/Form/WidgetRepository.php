<?php

namespace App\Services\Form;
use App\Models\Form;
use App\Models\Widget;
use App\Services\Download\DownloadImage;
//use App\Services\Form\WidgetRepository;
use Illuminate\Http\UploadedFile;

Class WidgetRepository
{
    public $loadService;

    public function __construct(DownloadImage $service)
    {
        $this->loadService = $service;
    }

    public function saveWidget(Form $form, $data)
    {
        if($data && $form->widget_status) {
            $arr = [
                'badge_line' => $data['badge_line'],
                'badge_table' => $data['badge_table'],
                'badge_number' => $data['badge_number'],
                'badge_align' => $data['badge_align'],
                'badge_position' => $data['badge_position'],
                'description' => $data['description']
            ];

            $form->widget->fill($arr);
            $form->widget->save();

            $this->saveWidgetBanner(    $form->widget, $data['banner']  );
            $this->saveWidgetBody(      $form->widget, $data['body']    );
            if(isset($data['badges']))
                $this->saveWidgetBadges(    $form->widget, $data['badges']  );
        }
    }

    public function saveWidgetBanner(Widget $widget, $data)
    {
        $arr['position'] = $data['position'];
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $arr['image'] = $this->loadService
                ->setFile($data['image'])
                ->setCatalog('widget')
                ->setPathName($widget->id)
                ->setPrefix('widget')
                ->save();
        }

        $widget->banner->fill($arr);
        $widget->banner->save();
    }

    public function saveWidgetBody(Widget $widget, $data)
    {
        $widget->body->fill($data);
        $widget->body->save();
    }

    public function saveWidgetBadges(Widget $widget, $data)
    {
        if(\is_array($data)) {
            $widget->badges()->delete();
            foreach ($data as $item) {
                if(isset($item['image']) && $item['image'] instanceof UploadedFile)
                    $item['image'] =  $this->loadService
                        ->setFile($item['image'])
                        ->setCatalog('widget')
                        ->setPathName($widget->id)
                        ->setPrefix('widget_badge')
                        ->save();
                $widget->badges()->create($item);
            }
        }
    }

}
