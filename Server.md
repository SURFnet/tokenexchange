# Introduction #

This page documents the TokenExchange server component

# Installation #

  * Download the tokenexchange package (or get it from SVN)
  * Add the server/www directory to your webserver, preferably in a vhost.
  * Create a database and install the schema from server/install/db.sql
  * Copy config.php.sample to config.php and adjust the configuration to match your setup
  * Reload the webserver configuration if you created a new vhost.

# Usage #

The server component has no usage instructions, it will only be used indirectly through any of the clients.

Note that one TokenExchange can service many apps; you typically only need one TokenExchange for all the Apps you release.

# API #

Todo