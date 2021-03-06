<?php
/**
 * SugarCLI
 *
 * PHP Version 5.3 -> 5.5
 * SugarCRM Versions 6.5 - 7.7
 *
 * @author Rémi Sauvat
 * @author Emmanuel Dyan
 * @author Joe Cora
 * @copyright 2005-2015 iNet Process
 * @copyright 2016 The New York Times
 *
 * @package nyt/sugarcli-nyt
 *
 * @license Apache License 2.0
 *
 * @link http://www.inetprocess.com
 *
 * @since 1.11.1 Added baseModuleName and conventionalRelationshipName methods
 */

namespace SugarCli\Utils;

use Symfony\Component\Yaml\Dumper as YamlDumper;

/**
 * Various Utils
 */
class Utils
{
    /** @var array $coreModuleBeans         assoc. array of module name keys to bean name values */
    private static $coreModuleBeans = array(
        'Accounts' => 'Account',
        'Bugs' => 'Bug',
        'Calls' => 'Call',
        'Cases' => 'Case',
        'Contacts' => 'Contact',
        'Contracts' => 'Contract',
        'Documents' => 'Document',
        'Emails' => 'Email',
        'Employees' => 'Employee',
        'Forecasts' => 'ForecastOpportunities',
        'ForecastWorksheets' => 'ForecastWorksheet',
        'KBContents' => 'KBContent',
        'Leads' => 'Lead',
        'Manufacturers' => 'Manufacturer',
        'Meetings' => 'Meeting',
        'Notes' => 'Note',
        'Opportunities' => 'Opportunity',
        'ProductCategories' => 'ProductCategory',
        'Products' => 'Product',
        'ProductTemplates' => 'ProductTemplate',
        'ProspectLists' => 'ProspectList',
        'Prospects' => 'Prospect',
        'Quotas' => 'Quota',
        'Quotes' => 'Quote',
        'Reports' => 'SavedReport',
        'RevenueLineItems' => 'RevenueLineItem',
        'Tasks' => 'Task'
    );


    /**
     * Create a new line every X words
     *
     * @param string  $sentence
     * @param integer $cutEvery
     *
     * @return string Same sentence cut
     */
    public static function newLineEveryXWords($sentence, $cutEvery)
    {
        // New line every 5 words
        $words = explode(' ', $sentence);
        $numWords = count($words);
        for ($i = 0; $i < $numWords; $i++) {
            $words[$i] = ($i !== 0 && $i%$cutEvery === 0 ? PHP_EOL : '') . $words[$i];
        }

        return implode(' ', $words);
    }

    /**
     * Generate a YAML file from an array
     *
     * @param array  $data
     * @param string $outputFile
     *
     * @throws \InvalidArgumentException
     */
    public static function generateYaml(array $data, $outputFile)
    {
        $outputFileDir = dirname($outputFile);
        if (!is_dir($outputFileDir)) {
            throw new \InvalidArgumentException("$outputFileDir is not a valid directory (" . __FUNCTION__ . ')');
        }

        $dumper = new YamlDumper();
        $dumper->setIndentation(4);
        $yaml = $dumper->dump($data, 3);
        file_put_contents($outputFile, $yaml);

        return true;
    }

    /**
     * Return the base module name with the prefix removed
     *
     * @author Joe Cora
     *
     * @param string $module_name
     * @requires |$module_name| > 0
     * @return string                       base module name
     */
    public static function baseModuleName($module_name)
    {
        // Perform regex to match pattern for Sugar module names with prefix defined
        preg_match('/^([a-zA-Z]{1,5}_){0,1}(.+)$/', $module_name, $matches);

        // Return the second match (1st is prefix; 2nd is base)
        return $matches[2];
    }

    /**
     * Return the bean name for Sugar core modules otherwise returns the incoming module name itself
     *
     * @author Joe Cora
     *
     * @param string $module_name
     * @requires |$module_name| > 0
     * @return string                       module bean name
     */
    public static function moduleBeanName($module_name)
    {
        // Check if the module name is in the core module list. Return the bean name for the core module or return
        //    the module name itself if module is not found
        if (array_key_exists($module_name, self::$coreModuleBeans)) {
            return self::$coreModuleBeans[$module_name];
        } else {
            return $module_name;
        }
    }

    /**
     * Return a conventional relationship name from left and right module names
     * Conventional name is treated as: lower($left_module_name) + '_to_' + lower($right_module_name)
     *
     * @author Joe Cora
     *
     * @param string $left_module_name
     * @param string $right_module_name
     * @requires |$left_module_name| > 0
     * @requires |$right_module_name| > 0
     * @return string                       conventional relationship name
     */
    public static function conventionalRelationshipName($left_module_name, $right_module_name)
    {
        return strtolower($left_module_name). '_to_'. strtolower($right_module_name);
    }
}
