<?php
	class OnLoad
	{
		//
		// This function is optional
		//
		public static function all()
		{
			echo 'all!';
			// This executes before every page render
		}

		//
		// The following function is an example of how to
		// make section-specific default actions.
		//
		public static function home()
		{
			echo 'HOME!';
			// This executes before any page in the
			// section 'home' is rendered
			// (notice the naming schema: section_[section])
		}
	}
?>