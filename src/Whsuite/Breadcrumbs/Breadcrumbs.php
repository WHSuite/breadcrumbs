<?php
namespace Whsuite\Breadcrumbs;

/**
* Breadcrumbs
*
* The breadcrumbs utility class provides an easy way to generate a breadcrumb
* trail using the views system built into WHSuite. You can also add update/alert
* notifications using the setNotice method.
*
* @package  WHSuite-Package-Utilities
* @author  WHSuite Dev Team <info@whsuite.com>
* @copyright  Copyright (c) 2013, Turn 24 Ltd.
* @license http://whsuite.com/license/ The WHSuite License Agreement
* @link http://whsuite.com
* @since  Version 1.0
*/
class Breadcrumbs
{
    public $links = array(); // Stores an array of all links in the breadcrumb trail.
    public $tpl; // Stores the template to fetch that is used for the breadcrumb trail.
    public $notice; // Stores an optional notice shown on the breadcrumb trail.

    /**
     * Init
     *
     * Initiates the breadcrumb class, and sets the breadcrumb template file so that
     * we dont have to do it every time we want to display a breadcrumb trail.
     *
     * @param  string $tpl path to the template we want to use (within the already set theme)
     * @return null
     */
    public function init($tpl)
    {
        $this->tpl = $tpl;
    }

    /**
     * Add Link
     *
     * Adds a link to the breadcrumb links array. Provides optional paramiters such as
     * setting the route to be an external link, and allowing extra html params to be added.
     * @param  string  $text           The link text to display
     * @param  string  $route          The route name or external link
     * @param  array   $route_params   Optional array of parameters for the route
     * @param  array   $params         Optional HTML paramiters to add to the link such as class, id, etc
     * @param  boolean $is_link        If true, the router system wont generate a route based link.
     * @return null
     */
    public function add($text, $route = false, $route_params = array(), $params = array(), $is_link = false)
    {
        if (! $is_link && $route != false) {

            $route = \App::get('router')->generate($route, $route_params);
        }

        $param_string = '';

        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $param_string .= $param.'="'.$value.'" ';
            }
        }

        $this->links[] = array(
            'text' => $text,
            'route' => $route,
            'params' => $param_string
        );
    }

    /**
     * Set Notice
     *
     * Sets a notice in the breadcrumb system. This is used for notifiying people of updates
     * or providing important info on the side of the breadcrumb trail.
     *
     * @param  string  $notice  The notice message to display
     * @param  string  $route   The route or external link to direct the user to if they click the notice
     * @param  boolean $is_link If true, the router system wont generate a route based link.
     * @return null
     */
    public function setNotice($notice, $route, $is_link = false)
    {
        if (!$is_link) {
            $route = \App::get('router')->generate($route);
        }

        $this->notice = array(
            'text' => $notice,
            'route' => $route,
        );
    }

    /**
     * Build Breadcrumbs
     *
     * Builds the breadcrumb trail and sets the breadcrumb view into a 'breadcrumbs' variable that
     * can then be accesed from inside the templates.
     *
     * @return null
     */
    public function build()
    {
        \App::get('view')->set('breadcrumb_notice', $this->notice);
        \App::get('view')->set('breadcrumb_links', $this->links);
        $breadcrumbs = \App::get('view')->fetch($this->tpl);
        \App::get('view')->set('breadcrumbs', $breadcrumbs);
    }
}
