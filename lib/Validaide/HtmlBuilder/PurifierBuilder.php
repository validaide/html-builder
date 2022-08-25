<?php declare(strict_types=1);

namespace Validaide\HtmlBuilder;

use HTMLPurifier;
use HTMLPurifier_Config;

final class PurifierBuilder
{
    public const SUPPORTED_ELEMENTS_FOR_DATA_ATTRIBUTES = [
        'h1', 'h2', 'h3', 'div', 'a', 'span', 'i', 'ul', 'li', 'b',
    ];

    public const DATA_ATTRIBUTES = [
        'data-available',
        'data-available-certificates',
        'data-capability-id',
        'data-company-name',
        'data-content',
        'data-core-question',
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
        'data-my-id',
        'data-number-of-available-views',
        'data-number-of-images',
        'data-placement',
        'data-quality-index',
        'data-target',
        'data-task-action',
        'data-task-id',
        'data-their-view-id',
        'data-toggle',
        'data-trigger',
        'data-vd-c-path',
    ];

    public static function purifier(): HTMLPurifier
    {
        $config = HTMLPurifier_Config::createDefault();
        // $config->set('Cache.DefinitionImpl', null); // remove this later, testing only
        $config->set('Attr.EnableID', true);
        $config->set('AutoFormat.RemoveEmpty', false);
        $config->set('AutoFormat.RemoveEmpty.RemoveNbsp', false);

        self::enrichDataDefinitions($config);

        return new HTMLPurifier($config);
    }

    private static function enrichDataDefinitions(HTMLPurifier_Config $config): void
    {
        $def = $config->getHTMLDefinition(true);
        if (is_null($def)) {
            return;
        }

        $def->addBlankElement('data-*');

        foreach(self::SUPPORTED_ELEMENTS_FOR_DATA_ATTRIBUTES as $element) {
            foreach(self::DATA_ATTRIBUTES as $dataAttribute) {
                $def->addAttribute($element, $dataAttribute, 'Text');
            }
        }
    }
}
