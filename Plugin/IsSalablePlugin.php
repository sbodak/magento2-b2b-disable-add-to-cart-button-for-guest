<?php
/**
 * IsSalable plugin
 *
 * @package   Bodak\DisableAddToCart
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Bodak\DisableAddToCart\Plugin;

use Magento\Framework\App\Http\Context;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;

/**
 * Class IsSalablePlugin
 *
 * @category Plugin
 * @package  Bodak\DisableAddToCart\Plugin
 */
class IsSalablePlugin
{
    /**
     * Scope config
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * HTTP Context
     * Customer session is not initialized yet
     *
     * @var Context
     */
    protected $context;

    /**
     * @var State
     */
    private $state;


    const DISABLE_ADD_TO_CART = 'catalog/frontend/catalog_frontend_disable_add_to_cart_for_guest';

    /**
     * SalablePlugin constructor.
     *
     * @param ScopeConfigInterface $scopeConfig ScopeConfigInterface
     * @param Context              $context     Context
     * @param State                $state       State
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context,
        State $state
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
        $this->state = $state;
    }

    /**
     * Check if is disable add to cart and if customer is logged in
     *
     * @return bool
     */
    public function afterIsSalable(): bool
    {
        if($this->state->getAreaCode() == Area::AREA_ADMINHTML) {
            return true;
        }

        $scope = ScopeInterface::SCOPE_STORE;

        if ($this->scopeConfig->getValue(self::DISABLE_ADD_TO_CART, $scope)) {
            if ($this->context->getValue(CustomerContext::CONTEXT_AUTH)) {
                return true;
            }
            return false;
        }
        return true;
    }
}
