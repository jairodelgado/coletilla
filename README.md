# coletilla

Simple Q&amp;A website

# Features

  - Allows users to post questions to the comunity.
  - A web-site moderatos aprove all questions and answers, nothing get published withoe approval.
  - Users can register a jabber id to get notified when answers to their questions or discussions intantaneously.

# Installation

## Requirements
  1. Apache web server v.2.4.4+ [mod_rewrite]
  2. PHP v.5.4.12+ [php_openssl, php_pgsql, php_pdo_pgsql]
  3. Postgres SQL v.9.1+

## Configuration.
  1. Run the db_script.sql provided in this package into the database server you pleace.
  2. Create an user with administration privileges on the tb_user table in the database.
  3. Copy the software files into you apache web server root directory.
  4. Setup the configuration.php file provided in this package.
  5. Delete install directory from this package for security reasons.

# Disclaimer

This is just a demo project that can be used for educational purpouses and small deployments. Enjoy!