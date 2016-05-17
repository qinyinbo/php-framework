<?php
class IndexController extends Yaf_Controller_Abstract {
    public function indexAction() 
    {
        $src  = trim($this->getRequest()->getQuery("src", ""));

        $testmodel = new TestModel();

        $a = $testmodel->test("a");

        $this->display('indexss');


    }
    public function viewAction()
    {
        $disply = array();
        $disply["here"] = "f";
        $disply["view_helper"] = new Tools_Viewhelper();
        $this->display('result',$disply);
        return false;
    }

}
