<?php

/**
 * Get back to stock (when order is canceled or whatever else)
 *
 * @param int $productId
 * @param float $qty
 * @param int $scopeId
 * @return bool
 */
//Magento\CatalogInventory\Model/StockManagement;

//public function backItemQty($productId, $qty, $scopeId = null)
//{
//    //if (!$scopeId) {
//    $scopeId = $this->stockConfiguration->getDefaultScopeId();
//    //}
//    $stockItem = $this->stockRegistryProvider->getStockItem($productId, $scopeId);
//    if ($stockItem->getItemId() && $this->stockConfiguration->isQty($this->getProductType($productId))) {
//        if ($this->canSubtractQty($stockItem)) {
//            $stockItem->setQty($stockItem->getQty() + $qty);
//        }
//        if ($this->stockConfiguration->getCanBackInStock($stockItem->getStoreId()) && $stockItem->getQty()
//            > $stockItem->getMinQty()
//        ) {
//            $stockItem->setIsInStock(true);
//            $stockItem->setStockStatusChangedAutomaticallyFlag(true);
//        }
//        $stockItem->save();
//    }
//    return true;
//}
