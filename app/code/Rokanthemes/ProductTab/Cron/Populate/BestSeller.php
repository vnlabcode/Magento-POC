<?php
namespace Rokanthemes\ProductTab\Cron\Populate;

class BestSeller
{

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $readAdapter;
    protected $writeAdapter;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * compositeConfig constructor.
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->storeManager = $storeManager;
        $this->readAdapter = $this->resourceConnection->getConnection('core_read');
        $this->writeAdapter = $this->resourceConnection->getConnection('core_write');
    }
    public function execute()
    {
        $tableBestSeller = $this->writeAdapter->getTableName('rokanthemes_sorting_bestseller');
        $tableSaleItem = $this->writeAdapter->getTableName('sales_order_item');
        $queryTruncate = "TRUNCATE $tableBestSeller";
        $this->writeAdapter->query($queryTruncate);
        foreach($this->storeManager->getStores() as $store)
        {
            $storeId = $store->getId();
            $query = "insert into $tableBestSeller(product_id, store_id, bestseller)
                        SELECT it.product_id, $storeId, sum(it.qty_ordered) from $tableSaleItem as it
                        where it.store_id = $storeId
                        group by it.product_id";
            $this->writeAdapter->query($query);
        }
        $queryInsertDefaultStore = "insert into $tableBestSeller(product_id, store_id, bestseller)
                                    select product_id, 0, sum(bestseller) from $tableBestSeller
                                    where store_id != 0
                                    group by product_id
                                    ";
        $this->writeAdapter->query($queryInsertDefaultStore);
        return true;
    }
}
