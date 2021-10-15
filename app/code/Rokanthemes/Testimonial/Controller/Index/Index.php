<?php
namespace Rokanthemes\Testimonial\Controller\Index;

class Index extends \Rokanthemes\Testimonial\Controller\Index {

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->getBlock('testimonialform');
        $this->_view->renderLayout();
    }
}
