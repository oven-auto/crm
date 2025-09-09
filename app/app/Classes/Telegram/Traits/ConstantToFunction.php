<?php

namespace App\Classes\Telegram\Traits;

trait ConstantToFunction
{
    public function options() : array
    {
        return isset($this->options) ? $this->options : [];
    }

    public function answer() : array
    {
        return isset($this->answer) ? $this->answer : [];
    }

    public function storageState() : array
    {
        return isset($this->storageState) ? $this->storageState : [];
    }

    public function rules() : array
    {
        return isset($this->rules) ? $this->rules : [];
    }

    public function stateCount() : int
    {
        return isset($this->stateCount) ? $this->stateCount : 0;
    }
}
