<?php
/**
 * Yii中利用魔术方法__get、__set实现了类似C#中的getter、setter。示例代码如下：
*/
class CTest
{
	private $pro;

	public function getPro()
	{
		return $this->pro;
	}

	public function setPro($value)
	{
		$this->pro = $value;
	}

	public function __get($name)
	{
		$getter = 'get' . $name;
		if(method_exists($this, $getter))
		{
			return $this->$getter();
		}
	}

	public function __set($name,$value)
	{
		$setter = 'set' . $name;
		if(method_exists($this, $setter))
		{
			return $this->$setter($value);
		}
	}
}

$obj = new CTest;
$obj->pro = 5;
echo $obj->pro;
////////////////////////////////////////
/**
 * Yii中的事件处理机制：
 * 添加一个相应的方法来申明事件。
 * 附加一个或多个事件句柄（public void attachEventHandler(string $name, callback $handler)）。
 * 通过使用CComponent::raiseEvent方法来激活事件。
 * 所有绑定的事件句柄被自动调用。
 * 
 * 在php中，定义回调函数（用作事件句柄）的方法有下面几种：
 * 全局函数-function_name、类静态方法-array('ClassName', 'staticMethodName')、对象方法array($object, 'objectMethod')、匿名函数
 * Yii使用一个CList对象来保存一个事件的事件句柄列表，可以使用CComponent::getEventHandlers来获取。
*/
////////////////////////////////////////
/**
 * Yii中类的导入和自动加载：
 * 导入一个类：Yii::import('application.apis.lyrics.LyricsFinder');
 * application是Yii中定义的一个标准别名，指向你应用程序的protected文件夹，它被翻译成文件系统路径。Yii还定义了其他的一些别名，如system、webroot等。
 * 如果你想你的类像Yii核心类那样自动被导入，你可以在配置文件main.php中配置全局导入。
*/
////////////////////////////////////////
?>