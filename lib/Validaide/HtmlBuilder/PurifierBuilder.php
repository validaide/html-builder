<?php declare(strict_types=1);

namespace Validaide\HtmlBuilder;

use Exception;
use HTMLPurifier;
use HTMLPurifier_Config;
use HTMLPurifier_HTMLDefinition;

final class PurifierBuilder
{
    public const SUPPORTED_ELEMENTS_FOR_DATA_ATTRIBUTES = [
        'h1', 'h2', 'h3', 'div', 'a', 'span', 'i', 'ul', 'li', 'b', 'button', 'img',
    ];

    public const DATA_ATTRIBUTES = [
        'data-available',
        'data-available-certificates',
        'data-bs-animation',
        'data-bs-dismiss',
        'data-bs-parent',
        'data-bs-placement',
        'data-bs-target',
        'data-bs-title',
        'data-bs-toggle',
        'data-capability-id',
        'data-company-name',
        'data-content',
        'data-core-question',
        'data-current-value',
        'data-day-of-operation',
        'data-day-of-operation-available',
        'data-day-of-operation-selected',
        'data-dismiss',
        'data-dmf-action-type',
        'data-dmf-action-url',
        'data-dmf-confirmation-url',
        'data-dmf-form-is-wizard',
        'data-dmf-form-url',
        'data-dmf-modal-title',
        'data-dmf-modal-type',
        'data-html',
        'data-id',
        'data-my-id',
        'data-number-of-available-views',
        'data-number-of-images',
        'data-old-value',
        'data-parent',
        'data-placement',
        'data-quality-index',
        'data-target',
        'data-task-action',
        'data-task-id',
        'data-their-view-id',
        'data-title',
        'data-toggle',
        'data-trigger',
        'data-vd-c-path',
        'aria-controls',
        'aria-expanded',
        'aria-valuemax',
        'aria-valuemin',
        'aria-valuenow',
    ];

    public static function purifier(): HTMLPurifier
    {
        static $purifier;

        if (is_null($purifier)) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Attr.EnableID', true);
            $config->set('AutoFormat.RemoveEmpty', false);
            $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', false);
            $config->set('CSS.Trusted', true);
            $config->set('Cache.SerializerPath', sys_get_temp_dir());

            $def = $config->getHTMLDefinition(true);
            if ($def) {
                // CN - order matters here; e.g. addElement for button should be before adding attributes
                self::enrichGenericDefinitions($def);
                self::enrichDataDefinitions($def);
            }

            $purifier = new HTMLPurifier($config);
        }

        return $purifier;
    }

    /**
     * @throws Exception
     */
    public static function checkAttribute(string $attributeName, string $elementName)
    {
        if (substr($attributeName, 0, 5) !== 'data-') {
            return;
        }

        if (!in_array($elementName, self::SUPPORTED_ELEMENTS_FOR_DATA_ATTRIBUTES)) {
            throw new Exception(sprintf("Unsupported html element (%s) for data attributes", $elementName));
        }

        if (!in_array($attributeName, self::DATA_ATTRIBUTES)) {
            throw new Exception(sprintf("Unsupported data attribute (%s)", $attributeName));
        }
    }

    private static function enrichDataDefinitions(HTMLPurifier_HTMLDefinition $def): void
    {
        $def->addBlankElement('data-*');

        foreach (self::SUPPORTED_ELEMENTS_FOR_DATA_ATTRIBUTES as $element) {
            foreach (self::DATA_ATTRIBUTES as $dataAttribute) {
                $def->addAttribute($element, $dataAttribute, 'Text');
            }
        }
    }

    private static function enrichGenericDefinitions(HTMLPurifier_HTMLDefinition $def): void
    {
        foreach (['i', 'span', 'div'] as $element) {
            $def->addAttribute($element, 'aria-hidden', 'Text');
        }

        $def->addElement('button', 'Block', 'Flow', 'Common');
    }
}
