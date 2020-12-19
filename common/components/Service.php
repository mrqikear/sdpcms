<?php
/**
 * service基类，所有service都要继承它
 * @author juwelga
 */
namespace common\components;

use common\models\PageModel;

class  Service {



    /**
     * @description 构造方法
     * @create 2018-05-21 15:19:29
     * @author 骑大象去上学 <huyuaning@gmail.com>
     */
    public function __construct(){
    }

    public static function getInstance(){
        static $_instance = array ();
        $static_class = get_called_class();
        if (empty($_instance[$static_class])) {
            $instance = $_instance[$static_class] = new static();
            return $instance;
        }
        return $_instance[$static_class];
    }

    /**
     * 按分页格式返回数据
     * @param array  $list 列表数据
     * @param object $pageModel page原型
     * @return array 分页格式的数据
     */
    public function page($list , PageModel $pageModel){
        return [
            "list"       => $list ,
            'pageParams' => $pageModel->getParams()
        ];
    }



}
