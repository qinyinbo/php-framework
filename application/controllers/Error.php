<?php
/**
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 */
class ErrorController extends Yaf_Controller_Abstract {

    public function errorAction($exception) {

        $this->getView()->assign("exception", $exception);
    }
}
