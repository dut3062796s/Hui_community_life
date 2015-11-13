<?php
/**
 * @name IndexController
 * @author desktop-uabd7nl\administrator
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class HomeController extends Yaf_Controller_Abstract {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/Sample/home/home/home/name/desktop-uabd7nl\administrator 的时候, 你就会发现不同
     */
	public function loginAction() {
		try {
			// 创建一个新cURL资源
			//$client = new yar_client("http://localhost:8996/Server/Operator.php");
			//$ch = curl_init();
			//curl_setopt($ch, CURLOPT_NOSIGNAL, 1);//启用时忽略所有的curl传递给php进行的信号
			//Set timeout to 1s
			//$client->SetOpt(YAR_OPT_CONNECT_TIMEOUT, 500);
			//$client->SetOpt(YAR_OPT_TIMEOUT, 1000*60*5);
			//Set packager to JSON
			//$client->SetOpt(YAR_OPT_PACKAGER, "json");

			/* call remote service */
			//$result = $client->some_method("parameter");
			/* call directly */
			//var_dump($client->add(1, 2));
			/* call via call */
			//var_dump($client->call("add", array(3, 2)));
			/* __add can not be called */
			//var_dump($client->_add(1, 2));
			// 抓取URL并把它传递给浏览器
			//curl_exec($ch);
			//关闭cURL资源，并且释放系统资源
			//curl_close($ch);
			//2. fetch model
			$model =  new TestModel();
			$model->add(1,1);
			echo json_encode($model->add(1,1));
			//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
			//return TRUE;
		}catch (Exception $e){
			echo 'Exception:'.$e->getMessage();
		}

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
		return false;
	}
}
