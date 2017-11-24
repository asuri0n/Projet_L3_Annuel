<?php

use Phalcon\Mvc\Controller;

/**
 * Class ControllerBase
 * @property \Ajax\php\phalcon\JsUtils $jquery
 */
class productsController extends Controller{
    /**
     * @var Ajax\Semantic
     **/
    protected $semantic;

    public function initialize(){
        $this->semantic = $this->jquery->semantic();
    }

    /**
     * Default action
     */
    public function indexAction(){
        $products = Products::find();
        $lv = $this->semantic->dataTable("lv1-1","Products",$products);
        $lv->setFields(["id","name","price","active"]);
        $lv->getOnRow("click","products/show","#productDetails",["attr"=>"data-ajax"]);
        $lv->getOnRow("dblclick","products/edit","#productForm",["attr"=>"data-ajax"]);
        $lv->setActiveRowSelector("error");

        $this->semantic->htmlButton("btUpdate","Modifier");

        $this->jquery->compile($this->view);
    }

    public function showAction($id){
        $this->view->disable();
        $product = Products::findFirst($id);
        echo $product->getName();
        echo "<br>";
        echo $product->getPrice()." €";
        echo "<br>";
        echo $product->getActive();
    }

    public function editAction($id){
        $this->view->disable();
        $product = Products::findFirst($id);
        echo $df = $this->semantic->dataForm("df4",$product);
    }
}
