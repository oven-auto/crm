<?php

namespace App\Repositories\Audit\Services;

use App\Repositories\Audit\Interfaces\AuditSortInterface;

class SortTree
{
    public static function tree(&$questions, AuditSortInterface|null $el, array &$arr = [])
    {   
        if($questions->count() == count($arr))
           return $arr;
        
        if($el)
        {
            $arr[] = $el;
            
            self::tree($questions, $questions->where('sort', $el->id)->first(), $arr);
        }

        return $arr;
    }



    /**
     * Сортировка под
     */
    public static function sortAfter(AuditSortInterface $object, AuditSortInterface $after)
    {
        $class = get_class($object);

        $childObject = $class::where('sort', $object->id)->first();
        $childAfter = $class::where('sort', $after->id)->first();

        if($childObject)
            $childObject->fill(['sort' => $object->sort])->save();

        if($childAfter)
            $childAfter->fill(['sort' => $object->id])->save();
        
        $object->fill(['sort' => $after->id])->save();
    }



    /**
     * Сортировка на первое место
     */
    public static function sortPrefer(AuditSortInterface $object, AuditSortInterface $root)
    {
        $class = get_class($object);
        
        $parent = $class::find($object->sort);
        $child = $class::where('sort', $object->id)->first();
      
        $root->fill(['sort' => $object->id])->save();

        if($parent && $child)
            $child->fill(['sort' => $parent->id])->save();
        
        $object->fill(['sort' => null])->save();
    }



    public static function changeSortOnDelete(AuditSortInterface $object)
    {
        $class = get_class($object);

        $parent = $class::find($object->sort);

        $child = $class::where('sort', $object->id)->first();
        
        $newSort = $parent->id ?? null;

        if($child)
            $child->fill(['sort' => $newSort])->save(); 
    }
}