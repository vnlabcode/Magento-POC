<?php
namespace Rokanthemes\Testimonial\Block\Widget;

/**
 * Super Deals List widget block
 * Class ProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
 
class Testimonial extends \Magento\Framework\View\Element\Template  implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = 'Rokanthemes_Testimonial::testimonial.phtml';
    /**
	 * Testimonial Factory
	 * @var \Rokanthemes\Testimonial\Model\TestimoFactory
	 */
	protected $_testimoFactory;

	protected $_scopeConfig;
	
	protected $customerSession;

	/**
	 * [__construct description]
	 * @param \Magento\Framework\View\Element\Template\Context                $context                 [description]
	 * @param \Rokanthemes\Testimonial\Model\TestimoFactory                     $testimoFactory           [description]
	 * @param \Magento\Framework\Registry                                     $coreRegistry            [description]
	 * @param \Rokanthemes\Testimonial\Model\ResourceModel\Testimo\CollectionFactory $testimoCollectionFactory [description]
	 * @param \Magento\Customer\Model\Session $customerSession [description]
	 * @param array                                                           $data                    [description]
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Rokanthemes\Testimonial\Model\TestimoFactory $testimoFactory,
		\Rokanthemes\Testimonial\Model\ResourceModel\Testimo\CollectionFactory $testimoCollectionFactory,
		\Magento\Customer\Model\Session $customerSession,
		array $data = []
	) {
		parent::__construct($context, $data);
		$this->_testimoFactory = $testimoFactory;
		$this->_testimoCollectionFactory = $testimoCollectionFactory;
		$this->_scopeConfig = $context->getScopeConfig();
		$this->customerSession = $customerSession;
	}
	
	public function getStoreId()
	{
		return $this->_storeManager->getStore()->getId();
	}
	
	/**
	 * @return
	 */
	public function getTestimonial($limit = false) {
		$CurentstoreId = $this->_storeManager->getStore()->getId();
		$sliderCollection = $this->_testimoFactory
			->create()
			->getCollection()
			->addFieldToFilter('status', 1)
			->addFieldToFilter('store_id', array('or'=> array(
				0 => array('eq', '0'),
				1 => array('like' => '%'.$CurentstoreId.'%')
		)));

		$sliderCollection->setOrderByTestimo();
		if($limit){
			$sliderCollection->getSelect()->limit($limit);
		}
		return $sliderCollection;
	}
	
	public function getConfig($config)
	{
		return $this->getData($config);
	}
	
	public function getIdStore()
	{
		return $this->_storeManager->getStore()->getId();
	}
	
	/**
	 * @return
	 */
	public function getMediaFolder() {
		$media_folder = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		return $media_folder;
	}
	
	
	public function checklogin()
	{
		return $this->customerSession->isLoggedIn();
	}
}
?>
