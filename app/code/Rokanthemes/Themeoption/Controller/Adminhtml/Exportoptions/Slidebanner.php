<?php

namespace Rokanthemes\Themeoption\Controller\Adminhtml\Exportoptions;

use Magento\Framework\App\Filesystem\DirectoryList;

class Slidebanner extends \Magento\Backend\App\Action
{
    protected $fileFactory;

    protected $_parser;

    protected $config;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\PageCache\Model\Config $config
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->fileFactory = $fileFactory;
		$this->_importPath = BP. '/' . DirectoryList::PUB . '/' . DirectoryList::MEDIA . '/demo_importer/';
		$this->_parser = new \Magento\Framework\Xml\Parser();
    }

    public function execute()
    {
        $fileName = 'slidebanner.xml';
   
		$dom = $this->_parser->getDom();
		$dom->formatOutput = true;
		$root = $dom->createElement('root');

		$collection_slider = $this->_objectManager->get('Rokanthemes\SlideBanner\Model\Slider')->getCollection();
		$blocks_slider = $dom->createElement('slider');
		foreach($collection_slider as $block_s)
		{
			$item_s = $dom->createElement('item');
			foreach($block_s->getData() as $key_s => $val_s)
			{

				$element_s = $dom->createElement($key_s);
				$content_s = $dom->createCDATASection($val_s);
				$element_s->appendChild($content_s);
				$item_s->appendChild($element_s);
			}
			$blocks_slider->appendChild($item_s);
		}
		$root->appendChild($blocks_slider);


		$collection = $this->_objectManager->get('Rokanthemes\SlideBanner\Model\Slide')->getCollection();
		$blocks = $dom->createElement('slide');
		foreach($collection as $block)
		{
			$item = $dom->createElement('item');
			foreach($block->getData() as $key => $value)
			{

				$element = $dom->createElement($key);
				$content = $dom->createCDATASection($value);
				$element->appendChild($content);
				$item->appendChild($element);
			}
			$blocks->appendChild($item);
		}
		$root->appendChild($blocks);

		$dom->appendChild($root);
		$content = $dom->saveXML();
		$dom->save($this->_importPath . $fileName);
		$this->messageManager->addSuccess(__('Elements Slidebanner Exported.'));
        $this->_redirect('adminhtml/system_config/edit/section/exportoptions');
    }
}
?>