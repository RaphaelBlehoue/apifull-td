<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 20/12/2017
 * Time: 10:57
 */

namespace Labs\ApiBundle\AnnotationHandler;


use Labs\ApiBundle\Annotation\RestResult;
use Labs\ApiBundle\Factory\ParamAnnotationFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Extractor\Handler\FosRestHandler;
use Nelmio\ApiDocBundle\Extractor\HandlerInterface;
use Symfony\Component\Routing\Route;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RestResultHandler implements HandlerInterface
{

    /**
     * @var FosRestHandler
     */
    protected $fosRestHandler;

    public function __construct(FosRestHandler $fosRestHandler)
    {
        $this->fosRestHandler = $fosRestHandler;
    }

    /**
     * Parse route parameters in order to populate ApiDoc.
     *
     * @param ApiDoc $apiDoc
     * @param array $annotations
     * @param \Symfony\Component\Routing\Route $route
     * @param \ReflectionMethod $method
     * @internal param ApiDoc $annotation
     */
    public function handle(ApiDoc $apiDoc, array $annotations, Route $route, \ReflectionMethod $method)
    {
        $forwardAnnotations = array();
        foreach ($annotations as $annotation) {
            if ($annotation instanceof RestResult){
                if ($pagination = $annotation->getPaginate()) {
                    $forwardAnnotations[] = ParamAnnotationFactory::getPageParam();
                    $forwardAnnotations[] = ParamAnnotationFactory::getLimitParam(is_int($pagination) ? $pagination : 50);
                }
                if ($sortColumns = $annotation->getSort()){
                    $param = ParamAnnotationFactory::getOrderByParam($sortColumns);
                    $param->name = $param->key;
                    $forwardAnnotations[] = $param;
                    $param = ParamAnnotationFactory::getOrderDirParam();
                    $param->name = $param->key;
                    $forwardAnnotations[]  = $param;
                }
            }
        }
        if (count($forwardAnnotations)){
            $this->fosRestHandler->handle($apiDoc, $forwardAnnotations, $route, $method);
        }
    }
}