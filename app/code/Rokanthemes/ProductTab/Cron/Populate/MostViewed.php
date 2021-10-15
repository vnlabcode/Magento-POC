<?php
namespace Rokanthemes\ProductTab\Cron\Populate;

class MostViewed
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
        $maintable = $this->writeAdapter->getTableName('rokanthemes_sorting_most_viewed');
        $tableData = $this->writeAdapter->getTableName('report_viewed_product_index');
        $queryTruncate = "TRUNCATE $maintable";
        $this->writeAdapter->query($queryTruncate);
        foreach($this->storeManager->getStores() as $store)
        {
            $storeId = $store->getId();
            $query = "insert into $maintable(product_id, store_id, viewed)
                        SELECT it.product_id, $storeId, count(*) from $tableData as it
                        where it.store_id = $storeId
                        group by it.product_id";
            $this->writeAdapter->query($query);
        }
        $queryInsertDefaultStore = "insert into $maintable(product_id, store_id, viewed)
                                    select product_id, 0, sum(viewed) from $tableData
                                    where store_id != 0
                                    group by product_id
                                    ";
        $this->writeAdapter->query($queryInsertDefaultStore);
        return true;
    }
}
