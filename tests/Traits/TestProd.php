<?php


namespace Tests\Traits;


trait TestProd
{

    protected function skipTestIfNoProd($messege = '')
    {
        if(!$this->isTestingProd()){
            $this->markTestSkipped($messege);
        }
    }
    protected function isTestingProd()
    {
        return env('TESTING_PROD') == true;
    }
}
