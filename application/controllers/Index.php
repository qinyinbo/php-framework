<?php
class IndexController extends Yaf_Controller_Abstract {
    public function indexAction() 
    {
        $param_a  = trim($this->getRequest()->getQuery("a", ""));
        $request = Yaf_Registry::get('request');
        $param_b = strtolower($request->getQuery('b', 'defaultvalue', 'trim|htmlentities'));
        $param_c = $request->getQuery('c', 1, 'int|range:1-x');

        $testmodel = new TestModel();

        $a = $testmodel->test("a");
        $fileload=Yaf_Loader::getInstance();
        $library_path  = $fileload->getLibraryPath();
        var_dump($library_path);

        $this->display('index');


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
