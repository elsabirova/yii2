<?php
namespace app\components;

use yii\base\Component;

class TestService extends Component
{
    public $property = 'test';

    public function get() {
        return $this->property;
    }
}