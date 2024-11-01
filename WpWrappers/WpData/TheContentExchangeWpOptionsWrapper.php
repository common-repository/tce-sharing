<?php


namespace TheContentExchange\WpWrappers\WpData;

/**
 * Class TheContentExchangeWpOptionsWrapper
 * @package TheContentExchange\WpWrappers\WpData
 */
class TheContentExchangeWpOptionsWrapper
{

    /**
     * @var string
     */
    private $optionsPrefix = 'tce-sharing-options-';

    public function tceGetOptionsPrefix(): string
    {
        return $this->optionsPrefix;
    }

    /**
     * Initialize an option in the options table with an empty value.
     *
     * @param string $optionName
     *
     * @return void
     */
    public function tceInitOption(string $optionName): void
    {
        $optionName = $this->optionsPrefix . $optionName;
        add_option($optionName);
    }

    /**
     * Gets the value of an option in the options table.
     *
     * @param string $optionName
     *
     * @return string
     */
    public function tceGetOptionValue(string $optionName): string
    {
        $optionName = $this->optionsPrefix . $optionName;
        return get_option($optionName);
    }

    /**
     * Updates an option if it exists.
     *
     * @param string $optionName
     * @param string $optionValue
     */
    public function tceUpdateOptionValue(string $optionName, string $optionValue): void
    {
        $optionName = $this->optionsPrefix . $optionName;
        update_option($optionName, $optionValue);
    }

    /**
     * Delete an option if it exists.
     *
     * @param string $optionName
     */
    public function tceDeleteOption(string $optionName): void
    {
        $optionName = $this->optionsPrefix . $optionName;
        delete_option($optionName);
    }
}
