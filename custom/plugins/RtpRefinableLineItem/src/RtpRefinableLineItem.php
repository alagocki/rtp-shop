<?php declare(strict_types=1);

namespace RtpRefinableLineItem;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Framework;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class RtpRefinableLineItem extends Plugin
{

    public function install(InstallContext $installContext): void
    {

        $customFieldSetRepository = $this->container->get('custom_field_set.repository');

        if (null === $customFieldSetRepository) {
            throw new \RuntimeException('Required service custom_field_set.repository not found');
        }

        $customFieldSetRepository->create([
            [
                'name' => 'customizable_fields',
                'config' => [
                    'label' => [
                        'en-GB' => 'English custom field set label',
                        'de-DE' => 'German custom field set label',
                        Defaults::LANGUAGE_SYSTEM => "Mention the fallback label here"
                    ]
                ],
                'customFields' => [
                    [
                        'name' => 'customizable',
                        'type' => CustomFieldTypes::BOOL,
                        'config' => [
                            'label' => [
                                'en-GB' => 'English custom field label',
                                'de-DE' => 'German custom field label',
                                Defaults::LANGUAGE_SYSTEM => "Mention the fallback label here"
                            ],
                            'customFieldPosition' => 1
                        ]
                    ]
                ]
            ]
        ], $installContext->getContext());
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }

        // Remove or deactivate the data created by the plugin
    }

    public function activate(ActivateContext $activateContext): void
    {
        // Activate entities, such as a new payment method
        // Or create new entities here, because now your plugin is installed and active for sure
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        // Deactivate entities, such as a new payment method
        // Or remove previously created entities
    }

    public function update(UpdateContext $updateContext): void
    {
        // Update necessary stuff, mostly non-database related
    }

    public function postInstall(InstallContext $installContext): void
    {
    }

    public function postUpdate(UpdateContext $updateContext): void
    {
    }

}
