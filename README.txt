-- About --

Author		Jason Strese (jason@jstrese.net)
Website		http://n-gen.googlecode.com/
Version		2.1

Documentation can be found at the aforementioned website.
Please note that it is still being written.

-- ACKNOWLEDGEMENTS --
 1) This software is distributed "as-is" with no guarantee that
    it will work properly or at all.
 2) Developers and contributors of this software may not be
    held responsible for any harm or damage this software may
    cause (IE: security leaks).
 3) This software is NOT backwards compatible with PHP 4. To get
    the most out of this software it must be used with the latest
    PHP version (5.3).
 4) All parties using this software should have already read this
    file before asking for help.


-- Installation --
 -- Before you start --
  If you are unfamiliar with the PHP syntax, "//" denotes
  a commented line (along with "/* */" and "#" too). Lines
  prefixed with "//" are ignored. When told to "uncomment
  these lines" you are expected to remove the "//" prefix
  from the mentioned lines. String values are to be
  in-between single-quotes ('value') -- note that single-
  quotes need to be escaped when used inside a single-
  quote (IE: 'value\'s').

 -- Configuration --
  Rename "config.new.php" to "config.php"
  
  If your site is going to be powered by a
  database (IE: MySQL, PostgreSQL, SQLite)
  you will need to uncomment lines 6-9 and
  line 12.

  $configs['db'][0]['user'] - your database username
  $configs['db'][0]['pass'] - your database password
  $configs['db'][0]['host'] - your database hostname (IP, Domain name, 'localhost')
  $configs['db'][0]['base'] - your database name

  Edit your site title by editing $configs['site_title']

  Next, edit $configs['document_root']. Set it to the
  absolute path (starting at your web root) of the config
  file. For example, if /var/www is your webroot, and the
  config file is in /var/www/mysite/, the path would be
  "/mysite/".

 -- Friendly URLs --
  No software is used to render URLs as "friendly," however
  our URLs instrinsically include "index.php" in front of
  them (IE: index.php/my/page/here). This can either be left
  alone, or you can strip the "index.php" part out with the
  help of your web servers support for URL rewriting. Apache,
  IIS, and NGINX support URL rewriting that would allow you to
  do this.

  This package is coupled with two .htaccess files for Apache,
  use whichever one you find more suitable for your environment,
  and please note that these are entirely optional and are meant
  only for Apache.
  ".htaccess". The two included files are:
   - .htaccess_basic
    - Uses Apache's mod_rewrite module to strip out "index.php"
   - .htaccess_recommended
    - Has the same benefit as .htaccess_basic, but adds support
      for mod_expires to make web pages load more efficiently.
      It also disallows direct access to the config.php file.