<?php
if (isset($_SERVER["CLEARDB_DATABASE_URL"])) 
{
  $db = parse_url($_SERVER["CLEARDB_DATABASE_URL"]);
} 
else 
{
  $db = parse_url('mysql://wpuser:password@localhost/wordpress?reconnect=true');
}


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', trim($db["path"],"/"));

/** MySQL database username */
define('DB_USER', $db["user"]);

/** MySQL database password */
define('DB_PASSWORD', $db["pass"]);

/** MySQL hostname */
define('DB_HOST', $db["host"]);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
?>