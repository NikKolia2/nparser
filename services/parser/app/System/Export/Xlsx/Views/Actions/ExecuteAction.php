<?php

namespace App\System\Export\Xlsx\Views\Actions;

class ExecuteAction
{
    public function __construct(
        private AbstractAction $action, 
        private $value
    ){

    }
    public function execute():mixed{
        return $this->action->execute($this->value);
    }
}
