<?php
/**
 * Google Optimizer Data Helper
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GoogleOptimizer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ObjectManager;
use Magento\GoogleGtag\Helper\GtagConfiguration;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 *
 * @api
 * @since 100.0.2
 */
class Data extends AbstractHelper
{
    /**
     * Xml path google experiments enabled
     */
    public const XML_PATH_ENABLED = 'google/analytics/experiments';

    /**
     * Xml path google experiments enabled for GA4
     */
    public const XML_PATH_ENABLED_GA4 = 'google/gtag/analytics4/experiments';

    /**
     * @var bool
     */
    protected $_activeForCmsFlag;

    /**
     * @var \Magento\GoogleAnalytics\Helper\Data
     */
    protected $_analyticsHelper;

    /**
     * @var GtagConfiguration|null
     */
    private $gtagConfiguration;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param \Magento\GoogleAnalytics\Helper\Data $analyticsHelper
     * @param GtagConfiguration|null $gtagHelper
     */
    public function __construct(
        Context $context,
        \Magento\GoogleAnalytics\Helper\Data $analyticsHelper,
        GtagConfiguration $gtagConfiguration = null
    ) {
        $this->_analyticsHelper = $analyticsHelper;
        $this->gtagConfiguration = $gtagConfiguration ?: ObjectManager::getInstance()->get(GtagConfiguration::class);
        parent::__construct($context);
    }

    /**
     * Checks if Google Experiment is enabled
     *
     * @param string $store
     * @return bool
     */
    public function isGoogleExperimentEnabled($store = null)
    {
        return ($this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $store
        )) || ($this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED_GA4,
            ScopeInterface::SCOPE_STORE,
            $store
        ));
    }

    /**
     * Checks if Google Experiment is active
     *
     * @param string $store
     * @return bool
     */
    public function isGoogleExperimentActive($store = null)
    {
        return $this->isGoogleExperimentEnabled($store) &&
            (
                $this->_analyticsHelper->isGoogleAnalyticsAvailable($store) ||
                $this->gtagConfiguration->isGoogleAnalyticsAvailable($store)
            );
    }
}
