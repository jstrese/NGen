<?php
	class Controller
	{
		/**
		 * This sets a description for the page -- to be displayed after the
		 * site title (IE: N-Gen - Creativity at mind; where "N-Gen" is the
		 * title and "Creativity at mind" is the description).
		 * @staticvar
		 * @public
		 */
		static public $description    = null;

		/**
		 * $caching must be set to true for any caching to happen.
		 * @staticvar
		 * @public
		 */
		static public $caching        = null;

		/**
		 * $cache_lifetime is the time (in seconds) the cache is good for.
		 * @staticvar
		 * @public
		 */
		static public $cache_lifetime = null;

		/**
		 * $cache_uid gives the cache a unique id -- useful for caching the
		 * same page twice but with different/altered content.
		 * @staticvar
		 * @public
		 */
		static public $cache_uid      = null;

		/**
		 * $silence controls whether or not a page is displayed after
		 * the control is executed.
		 * @staticvar
		 * @public
		 */
		static public $silence        = null;

		/**
		 * $vars contains an array of template vars which are sent to
		 * the renderer and passed to the template file.
		 * @public
		 */
        public $vars = array();
	}
?>