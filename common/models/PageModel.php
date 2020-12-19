<?php
namespace common\models;
use Yii;

/**
 * 分页的数据原型
 * 根据数据总数和每页显示条数获取分页信息
 * @author juwelga
 */
class PageModel {
    /**
     * 页数
     * @var int
     */
    public $page;
    /**
     * 总页数
     * @var int
     */
    public $total_page;
    /**
     * 偏移量/起始位置
     * @var int
     */
    public $offset;
    /**
     * 每页显示数据条数
     * @var int
     */
    public $page_size;
    /**
     * 数据总条数
     * @var int
     */
    public $count;

    public function __construct($count , $pageSize = '', $page = ''){

        $this->count = $count;
        $this->page_size = $pageSize;
        if(empty($this->page_size)){
            $this->page_size = Yii::$app->request->getParam("page_size");// 前端传的page_size 限制最多 2000
            if($this->page_size > 2000 ){
                $this->page_size = 2000;
                //throw new SDPException('每页大小最多2000条');
            }
        }

        if(empty($this->page_size)){
            $this->page_size = Yii::$app->request->getParam("pageSize");// 前端传的page_size 限制最多 2000
            if($this->page_size > 2000 ){
                $this->page_size = 2000;
                //throw new SDPException('每页大小最多2000条');
            }
        }

        if (empty($this->page_size) || $this->page_size < 0) {
            $this->page_size = ViewConfig::PAGE_SIZE;
        }

        if (!is_numeric($this->page_size)) {
            throw new SDPException('每页大小参数类型非法:' . $this->page_size);
        }
        $this->total_page = ceil($count / $this->page_size);
        if(empty($page)){
            $this->page  = Yii::$app->request->getParam("page" , 1);
        }else{
            $this->page = $page;
        }
        $this->offset = ($this->page - 1) * $this->page_size;
    }

    /**
     * 获取分页所需的参数
     * @return array 分页所需的参数
     */
    public function getParams(){
        return [
            "page"       => $this->page ,
            "total_page" => $this->total_page ,
            "page_size"  => $this->page_size ,
            "count"      => $this->count
        ];
    }
}
