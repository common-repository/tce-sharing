<?php


namespace TheContentExchange\Core;

/**
 * Class TheContentExchangeSharingLoader
 * @package TheContentExchange\Core
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 */

class TheContentExchangeSharingLoader
{
    /**
     * The array of actions registered with WordPress.
     *
     * @var array<mixed>    $actions    The actions registered with WordPress
     */
    private $actions;

    /**
     * The array of actions registered with WordPress.
     *
     * @var array<mixed>    $filters    The filters registered with WordPress
     */
    private $filters;

    /**
     * TheContentExchangeSharingLoader constructor.
     *
     * Initialize the collections used to maintain the actions and filters.
     */
    public function __construct()
    {

        $this->actions = [];
        $this->filters = [];
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @param   string     $hook             The name of the WordPress action that is being registered.
     * @param   object     $component        A reference to the instance of the object on which the action is defined.
     * @param   string     $callback         The name of the function definition on the $component.
     * @param   int        $priority         Optional. The priority at which the function should be fired. Default is 10.
     * @param   int        $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function tceAddAction(
        $hook,
        $component,
        $callback,
        $priority = 10,
        $accepted_args = 1
    ): void {
        $this->actions = $this->tceAddHook(
            $this->actions,
            $hook,
            $component,
            $callback,
            $priority,
            $accepted_args
        );
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param   string     $hook             The name of the WordPress filter that is being registered.
     * @param   object     $component        A reference to the instance of the object on which the filter is defined.
     * @param   string     $callback         The name of the function definition on the $component.
     * @param   int        $priority         Optional. The priority at which the function should be fired. Default is 10.
     * @param   int        $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function tceAddFilter(
        $hook,
        $component,
        $callback,
        $priority = 10,
        $accepted_args = 1
    ): void {
        $this->filters = $this->tceAddHook(
            $this->filters,
            $hook,
            $component,
            $callback,
            $priority,
            $accepted_args
        );
    }

    /**
     * Add a new notice to the collection to be registered with WordPress.
     *
     * @param object $component A reference to the instance of the object on which the action is defined.
     * @param string $callback The name of the function definition on the $component.
     */
    public function tceAddAdminNotice(object $component, string $callback): void
    {
        $this->tceAddAction('admin_notices', $component, $callback, 999, 0);
    }

    /**
     * A Utility function that is used to add an action or filter ot its
     * respective collection.
     *
     * @param    array<mixed>   $hooks            The collection of hooks that is being registered (that is, actions or filters).
     * @param    string         $hook             The name of the WordPress filter that is being registered.
     * @param    object         $component        A reference to the instance of the object on which the filter is defined.
     * @param    string         $callback         The name of the function definition on the $component.
     * @param    int            $priority         The priority at which the function should be fired.
     * @param    int            $accepted_args    The number of arguments that should be passed to the $callback.
     * @return   array<mixed>                     The collection of actions or filters registered with WordPress.
     */
    private function tceAddHook(
        $hooks,
        $hook,
        $component,
        $callback,
        $priority,
        $accepted_args
    ): array {

        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    /**
     * Register the filters and actions with WordPress.
     */
    public function tceRunLoader(): void
    {

        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}
