<?php
namespace Rokanthemes\OptimizeSpeed\Plugin;

use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\ResponseInterface;
use Rokanthemes\OptimizeSpeed\Processor\LazyLoadProcessor;

class OutputLazyLoadProcessor
{
    /** @var Request */
    private $request;

    /** @var OutputProcessor */
    private $outputProcessor;

    public function __construct(
        Request $request,
        LazyLoadProcessor $outputProcessor
    ) {
        $this->request         = $request;
        $this->outputProcessor = $outputProcessor;
    }

    public function aroundRenderResult($subject, \Closure $proceed, ResponseInterface $response)
    {
        $result = $proceed($response);
        $content = $response->getBody();
        $content = $this->outputProcessor->process($content);
        $response->setBody($content);

        return $result;
    }
}
