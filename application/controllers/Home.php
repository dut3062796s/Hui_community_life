<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/3
 * Time: 8:16
 */
class HomeController extends Yaf_Controller_Abstract {

    /**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/Sample/home/home/home/name/desktop-uabd7nl\administrator 的时候, 你就会发现不同
     */
    public function indexAction($name = "Stranger") {
        //1. fetch query
        $get = $this->getRequest()->getQuery("get", "default value");

        //2. fetch model
        $model = new SampleModel();

        //3. assign
        $this->getView()->assign("content", "Home123456");
        $this->getView()->assign("name", "Home123");

        //4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return TRUE;
    }

}