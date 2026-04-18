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

use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class IsSalablePlugin
 *
 * @category Plugin
 * @package  Bodak\DisableAddToCart\Plugin
 */
class IsSalablePlugin
{
    const DISABLE_ADD_TO_CART = 'catalog/frontend/catalog_frontend_disable_add_to_cart_for_guest';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Context
     */
    protected $context;

    /**
     * IsSalablePlugin constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Context              $context
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
    }

    /**
     * Restrict salability for guest users when the feature is enabled.
     *
     * @param Product $subject
     * @param bool    $result
     * @return bool
     */
    public function afterIsSalable(Product $subject, bool $result): bool
    {
        if ($this->scopeConfig->getValue(self::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)) {
            if ($this->context->getValue(CustomerContext::CONTEXT_AUTH)) {
                return $result;
            }
            return false;
        }
        return $result;
    }
}
