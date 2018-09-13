<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Indexer\Setup;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Indexer\StateInterface;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Indexer\ConfigInterface;
use Magento\Indexer\Model\Indexer\StateFactory;
use Magento\Indexer\Model\ResourceModel\Indexer\State\CollectionFactory;
use Magento\Framework\Indexer\IndexerRegistry;

/**
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Recurring implements InstallSchemaInterface
{
    /**
     * Indexer collection factory
     *
     * @var CollectionFactory
     */
    private $statesFactory;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var StateFactory
     */
    private $stateFactory;

    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    /**
     * Init
     *
     * @param CollectionFactory $statesFactory
     * @param StateFactory $stateFactory
     * @param ConfigInterface $config
     * @param EncryptorInterface $encryptor
     * @param EncoderInterface $encoder
     * @param IndexerRegistry $indexerRegistry
     */
    public function __construct(
        CollectionFactory $statesFactory,
        StateFactory $stateFactory,
        ConfigInterface $config,
        EncryptorInterface $encryptor,
        EncoderInterface $encoder,
        IndexerRegistry $indexerRegistry = null
    ) {
        $this->statesFactory = $statesFactory;
        $this->stateFactory = $stateFactory;
        $this->config = $config;
        $this->encryptor = $encryptor;
        $this->encoder = $encoder;
        $this->indexerRegistry = $indexerRegistry ? : ObjectManager::getInstance()->get(IndexerRegistry::class);
    }

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        foreach ($this->config->getIndexers() as $indexerId => $indexerConfig) {

            $indexerState = $this->indexerRegistry->get($indexerId)->getState();
            $expectedHashConfig = $this->encryptor->hash(
                $this->encoder->encode($indexerConfig),
                Encryptor::HASH_VERSION_MD5
            );

            if ($indexerState->getHashConfig() != $expectedHashConfig) {
                $indexerState->setStatus(StateInterface::STATUS_INVALID);
                $indexerState->setHashConfig($expectedHashConfig);
                $indexerState->save();
            }
        }
    }
}
