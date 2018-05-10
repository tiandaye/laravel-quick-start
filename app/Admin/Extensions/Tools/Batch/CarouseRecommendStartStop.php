<?php

namespace App\Admin\Extensions\Tools\Batch;

use Encore\Admin\Grid\Tools\BatchAction;

class CarouseRecommendStartStop extends BatchAction
{
    protected $action;

    public function __construct($action = 1)
    {
        $this->action = $action;
    }

    public function script()
    {
        switch ($this->action){
            case 'start':
                $url = route('mall.homepage.carouse_recommends.start');
                break;
            case 'stop':
                $url = route('mall.homepage.carouse_recommends.stop');
                break;
        }

        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {
    batchCarouseStartStop('$url',selectedRows(),'$this->action')

});

EOT;

    }
}
