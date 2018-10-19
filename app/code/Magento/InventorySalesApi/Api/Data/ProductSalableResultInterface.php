<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventorySalesApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Represents result of service Magento\InventorySalesApi\Api\IsProductSalableForRequestedQtyInterface::execute
 *
 * @api
 */
interface ProductSalableResultInterface extends ExtensibleDataInterface
{
    /**
     * @return bool
     */
    public function isSalable(): bool;

    /**
     * @return ProductSalabilityErrorInterface[]
     */
    public function getErrors(): array;

    /**
     * Retrieve existing extension attributes object
     *
     * @return \Magento\InventorySalesApi\Api\Data\ProductSalableResultExtensionInterface|null
     */
    public function getExtensionAttributes(): ?ProductSalableResultExtensionInterface;

    /**
     * Set an extension attributes object
     *
     * @param \Magento\InventorySalesApi\Api\Data\ProductSalableResultExtensionInterface $extensionAttributes
     * @return void
     */
    public function setExtensionAttributes(ProductSalableResultExtensionInterface $extensionAttributes): void;
}
