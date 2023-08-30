<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeAvailability\Reader;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Client\ProductOfferShipmentTypeAvailability\Dependency\Client\ProductOfferShipmentTypeAvailabilityToShipmentTypeStorageClientInterface;

class ShipmentTypeStorageReader implements ShipmentTypeStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferShipmentTypeAvailability\Dependency\Client\ProductOfferShipmentTypeAvailabilityToShipmentTypeStorageClientInterface
     */
    protected ProductOfferShipmentTypeAvailabilityToShipmentTypeStorageClientInterface $shipmentTypeStorageClient;

    /**
     * @param \Spryker\Client\ProductOfferShipmentTypeAvailability\Dependency\Client\ProductOfferShipmentTypeAvailabilityToShipmentTypeStorageClientInterface $shipmentTypeStorageClient
     */
    public function __construct(ProductOfferShipmentTypeAvailabilityToShipmentTypeStorageClientInterface $shipmentTypeStorageClient)
    {
        $this->shipmentTypeStorageClient = $shipmentTypeStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageTransfer|null
     */
    public function findShipmentTypeStorageByProductOfferServicePointAvailabilityConditionsTransfer(
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): ?ShipmentTypeStorageTransfer {
        if (!$productOfferServicePointAvailabilityConditionsTransfer->getShipmentTypeUuid()) {
            return null;
        }

        $shipmentTypeStorageConditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setStoreName($productOfferServicePointAvailabilityConditionsTransfer->getStoreNameOrFail())
            ->addUuid($productOfferServicePointAvailabilityConditionsTransfer->getShipmentTypeUuidOrFail());
        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($shipmentTypeStorageConditionsTransfer);

        $shipmentTypeStorageCollectionTransfer = $this->shipmentTypeStorageClient->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);

        if (!$shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->count()) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer */
        $shipmentTypeStorageTransfer = $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->getIterator()->current();

        return $shipmentTypeStorageTransfer;
    }
}
