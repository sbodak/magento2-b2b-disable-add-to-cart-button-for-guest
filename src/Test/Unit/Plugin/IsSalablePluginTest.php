<?php

declare(strict_types=1);

namespace Bodak\DisableAddToCart\Test\Unit\Plugin;

use Bodak\DisableAddToCart\Plugin\IsSalablePlugin;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IsSalablePluginTest extends TestCase
{
    /** @var ScopeConfigInterface|MockObject */
    private $scopeConfig;

    /** @var Context|MockObject */
    private $context;

    /** @var Product|MockObject */
    private $subject;

    private IsSalablePlugin $plugin;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->context = $this->createMock(Context::class);
        $this->subject = $this->createMock(Product::class);

        $this->plugin = new IsSalablePlugin($this->scopeConfig, $this->context);
    }

    public function testAfterIsSalableReturnsTrueWhenFeatureDisabledAndProductSalable(): void
    {
        $this->scopeConfig->method('getValue')
            ->with(IsSalablePlugin::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)
            ->willReturn(false);

        $this->assertTrue($this->plugin->afterIsSalable($this->subject, true));
    }

    public function testAfterIsSalableReturnsFalseWhenFeatureDisabledAndProductNotSalable(): void
    {
        $this->scopeConfig->method('getValue')
            ->with(IsSalablePlugin::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)
            ->willReturn(false);

        $this->assertFalse($this->plugin->afterIsSalable($this->subject, false));
    }

    public function testAfterIsSalableReturnsTrueWhenFeatureEnabledAndCustomerLoggedIn(): void
    {
        $this->scopeConfig->method('getValue')
            ->with(IsSalablePlugin::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)
            ->willReturn(true);

        $this->context->method('getValue')
            ->with(CustomerContext::CONTEXT_AUTH)
            ->willReturn(true);

        $this->assertTrue($this->plugin->afterIsSalable($this->subject, true));
    }

    public function testAfterIsSalableRespectsOriginalResultWhenFeatureEnabledAndCustomerLoggedIn(): void
    {
        $this->scopeConfig->method('getValue')
            ->with(IsSalablePlugin::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)
            ->willReturn(true);

        $this->context->method('getValue')
            ->with(CustomerContext::CONTEXT_AUTH)
            ->willReturn(true);

        $this->assertFalse($this->plugin->afterIsSalable($this->subject, false));
    }

    public function testAfterIsSalableReturnsFalseWhenFeatureEnabledAndGuestUser(): void
    {
        $this->scopeConfig->method('getValue')
            ->with(IsSalablePlugin::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)
            ->willReturn(true);

        $this->context->method('getValue')
            ->with(CustomerContext::CONTEXT_AUTH)
            ->willReturn(false);

        $this->assertFalse($this->plugin->afterIsSalable($this->subject, true));
    }

    public function testAfterIsSalableReturnsFalseWhenFeatureEnabledGuestUserAndProductNotSalable(): void
    {
        $this->scopeConfig->method('getValue')
            ->with(IsSalablePlugin::DISABLE_ADD_TO_CART, ScopeInterface::SCOPE_STORE)
            ->willReturn(true);

        $this->context->method('getValue')
            ->with(CustomerContext::CONTEXT_AUTH)
            ->willReturn(false);

        $this->assertFalse($this->plugin->afterIsSalable($this->subject, false));
    }
}
