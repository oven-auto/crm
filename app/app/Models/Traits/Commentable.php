<?php

namespace App\Models\Traits;
use App\Models\Interfaces\GiveDataForCommentInterface;

Trait Commentable
{
    public function changesList(Array $arr)
    {
        $data = [];
        if($arr)
        {
            foreach(self::RUSSIAN_COLLUMNS as $key => $name)
                if(key_exists($key, $arr) && $arr[$key] != '')
                {
                    if(key_exists($key, self::LINK_TO_LINK))
                    {
                        $className = self::LINK_TO_LINK[$key];
                        if($this->$className instanceof GiveDataForCommentInterface)
                            $data[] = $name . ' = ' . $this->$className->forComment();
                    }
                    elseif(str_contains($key, '_at'))
                        $data[] = $name . ' = ' . date('d.m.Y (H:i)',strtotime($arr[$key]));
                    else
                        $data[] = $name . ' = ' . $arr[$key];
                }
        }
        return join('\\n', $data);
    }

    private function getValue(GiveDataForCommentInterface $obj)
    {
        return $this->forComment();
    }
}
