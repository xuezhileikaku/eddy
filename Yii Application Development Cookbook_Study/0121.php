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

//自定义验证规则：
//两种实现方法：1、类方法；2、单独的类

//模型
class SiteConfirmation extends CFormModel
{
	public $url;

	//返回对属性的验证规则，在CModel类中被定义（返回一个空数组）
	//array('attribute list', 'validator name', 'on'=>'scenario name', ...validation parameters...)
	public function rules()
	{
		return array(
			array('url','confirm'),//自定义的类方法
		);
	}

	public function confirm($attribute,$params)
	{
		$ch = curl_init();//初始化一个新的会话，返回一个cURL句柄
		curl_setopt($ch, CURLOPT_URL, $this->url);//为给定的cURL会话句柄设置一个选项,CURLOPT_URL:需要获取的URL地址
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//CURLOPT_RETURNTRANSFER:将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
		$output = curl_exec($ch);
		curl_close($ch);
		if (trim($output)!='code here') {
			//为给的的属性添加一条错误信息，此方法在CModel类中被定义
			//$this->_errors[$attribute][]=$error;
			$this->addError('url','Please upload file first.');
		}
	}
}

//控制器
class UrlController extends CController
{
	function actionIndex()
	{
		$confirmation = new SiteConfirmation();
		$confirmation->url = 'http://www.rrgod.com/ydzl.txt';
		if ($confirmation->validate()) {
			echo 'OK';
		}else{
			echo 'Please upload a file.';
		}
	}
}

//放在单独的类中，方便代码复用。注意：集成自CValidator类，实现其抽象方法validateAttribute()
class RemoteFileValidator extends CValidator
{
    public $content = '';
    protected function validateAttribute($object,$attribute)
    {
        $value=$object->$attribute;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $value);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        
        if(trim($output)!=$this->content)
            $this->addError($object,$attribute,'Please upload file first.');
    }
}

//此时验证规则这样写：
array('url', 'RemoteFileValidator', 'content' => 'code here'),


//Yii中文件上传的处理
//视图文件：

<?php if($uploaded): ?>
	<p>File was uploaded. Check <?php echo $dir ?>.</p>
	<?php endif ?>
	<?php 
	//生成一个表单的开始标签。
	//beginForm(mixed $action='', string $method='post', array $htmlOptions=array ( ))
	echo CHtml::beginForm('','post',array('enctype'=>'multipart/form-data'))?>
	<?php 
	//显示一个模型属性的第一个有效的错误。error(CModel $model, string $attribute, array $htmlOptions=array ( ))
	echo CHtml::error($model,'file')?>
	<?php 
	//为一个模型属性生成一个文件输入框。
	//activeFileField(CModel $model, string $attribute, array $htmlOptions=array ( ))
	echo CHtml::activeFileField($model,'file');?>
	<?php 
	//生成一个提交按钮。
	//submitButton(string $label='submit', array $htmlOptions=array ( ))
	echo CHtml::submitButton('Upload')?>
<?php 
//生成一个表单的结束标签。
echo CHtml::endForm()?>

<?php
//控制器代码
class UploadController extends CController
{
	public function actionIndex()
	{
		//翻译一个别名为一个文件路径。得到的是绝对路径，如D:\WWW\blog\protected\uploads
		$dir = Yii::getPathOfAlias('application.uploads');
		$uploaded = false;
		$model = new Upload();
		
		if (isset($_POST['Upload'])) {
			$model->attributes=$_POST['Upload'];
			//CUploadedFile是对一个已上传文件的抽象，包含所有相关信息
			//Returns an instance of the specified uploaded file.
			//getInstance(CModel $model, string $attribute)
			$file = CUploadedFile::getInstance($model,'file');
			if ($model->validate()) {
				//saveAs方法保存上传文件；getName方法获取上传文件名
				$uploaded = $file->saveAs($dir.'/'.$file->getName());
			}
		}
		$this->render('index',array(
			'model'=>$model,
			'uploaded'=>$uploaded,
			'dir'=>$dir,
			));
	}
}

//模型代码
class Upload extends CFormModel
{
	public $file;

	public function rules()
	{
		return array(
			array('file','file','types'=>'jpg'),
			);
	}
}
?>