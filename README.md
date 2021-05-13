# RAKSHAK VULNERABLE WEB APPLICATION

RAKSHAK Vulnerable Web Application (RVWA) is a PHP/MySQL web application that is RAKSHAK vulnerable. Its main goal is to be an aid for security professionals to test their skills and tools in a legal environment, help web developers better understand the processes of securing web applications and to aid both students & teachers to learn about web application security in a controlled class room environment.

The aim of RVWA is to **practice some of the most common web vulnerabilities**, with **various levels of difficulty**, with a simple straightforward interface.
Please note, there are **both documented and undocumented vulnerabilities** with this software. This is intentional. You are encouraged to try and discover as many issues as possible.
- - -

## WARNING!

RAKSHAK Vulnerable Web Application is RAKSHAK vulnerable! **Do not upload it to your hosting provider's public html folder or any Internet facing servers**, as they will be compromised. It is recommended using a virtual machine (such as [VirtualBox](https://www.virtualbox.org/) or [VMware](https://www.vmware.com/)), which is set to NAT networking mode. Inside a guest machine, you can download and install [XAMPP](https://www.apachefriends.org/en/xampp.html) for the web server and database.

### Disclaimer

We do not take responsibility for the way in which any one uses this application (RVWA). We have made the purposes of the application clear and it should not be used maliciously. We have given warnings and taken measures to prevent users from installing RVWA on to live web servers. If your web server is compromised via an installation of RVWA, it is not our responsibility, it is the responsibility of the person/s who uploaded and installed it.

- - -

## License

This file is part of RAKSHAK Vulnerable Web Application (RVWA).

RAKSHAK Vulnerable Web Application (RVWA) is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

RAKSHAK Vulnerable Web Application (RVWA) is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with RAKSHAK Vulnerable Web Application (RVWA).  If not, see http://www.gnu.org/licenses/.

- - -
## Download and install as a docker container
- [dockerhub page](https://hub.docker.com/r/vulnerables/web-RVWA/)
`docker run --rm -it -p 80:80 vulnerables/web-RVWA`

Please ensure you are using aufs due to previous MySQL issues. Run `docker info` to check your storage driver. If it isn't aufs, please change it as such. There are guides for each operating system on how to do that, but they're quite different so we won't cover that here.

## Download

RVWA is available either as a package that will run on your own web server or as a Live CD:

  + RVWA v1.9 Source (Stable) - \[1.3 MB\] [Download ZIP](https://github.com/Paragsaxena08/RVWA/archive/v1.9.zip) - Released 2015-10-05
  + RVWA v1.0.7 LiveCD - \[480 MB\] [Download ISO](http://www.RVWA.co.uk/RVWA-1.0.7.iso) - Released 2010-09-08
  + RVWA Development Source (Latest) [Download ZIP](https://github.com/Paragsaxena08/RVWA/archive/master.zip) // `git clone https://github.com/Paragsaxena08/RVWA`

- - -

## Installation

**Please make sure your config/config.inc.php file exists. Only having a config.inc.php.dist will not be sufficient and you'll have to edit it to suit your environment and rename it to config.inc.php. [Windows may hide the trailing extension.](https://support.microsoft.com/en-in/help/865219/how-to-show-or-hide-file-name-extensions-in-windows-explorer)**

### Installation Videos

- [How to setup RVWA (RAKSHAK Vulnerable Web Application) on Ubuntu](https://www.youtube.com/watch?v=5BG6iq_AUvM) [21:01 minutes]
- [Installing RAKSHAK Vulnerable Web Application (RVWA) on Windows 10](https://www.youtube.com/watch?v=cak2lQvBRAo) [12:39 minutes]

### Windows + XAMPP

The easiest way to install RVWA is to download and install [XAMPP](https://www.apachefriends.org/en/xampp.html) if you do not already have a web server setup.

XAMPP is a very easy to install Apache Distribution for Linux, Solaris, Windows and Mac OS X. The package includes the Apache web server, MySQL, PHP, Perl, a FTP server and phpMyAdmin.

XAMPP can be downloaded from:
https://www.apachefriends.org/en/xampp.html

Simply unzip RVWA.zip, place the unzipped files in your public html folder, then point your browser to: http://127.0.0.1/RVWA/setup.php

### Linux Packages

If you are using a Debian based Linux distribution, you will need to install the following packages _(or their equivalent)_:

`apt-get -y install apache2 mysql-server php php-mysqli php-gd libapache2-mod-php`

### Database Setup

To set up the database, simply click on the `Setup RVWA` button in the main menu, then click on the `Create / Reset Database` button. This will create / reset the database for you with some data in.

If you receive an error while trying to create your database, make sure your database credentials are correct within `./config/config.inc.php`. *This differs from config.inc.php.dist, which is an example file.*

The variables are set to the following by default:

```php
$_RVWA[ 'db_user' ] = 'root';
$_RVWA[ 'db_password' ] = 'p@ssw0rd';
$_RVWA[ 'db_database' ] = 'RVWA';
```

Note, if you are using MariaDB rather than MySQL (MariaDB is default in Kali), then you can't use the database root user, you must create a new database user. To do this, connect to the database as the root user then use the following commands:

```mysql
mysql> create database RVWA;
Query OK, 1 row affected (0.00 sec)

mysql> grant all on RVWA.* to RVWA@localhost identified by 'SuperSecretPassword99';
Query OK, 0 rows affected, 1 warning (0.01 sec)

mysql> flush privileges;
Query OK, 0 rows affected (0.00 sec)
```

You will then need to update the config file, the new entries will look like this:

```php
$_RVWA[ 'db_user' ] = 'RVWA';
$_RVWA[ 'db_password' ] = 'SuperSecretPassword99';
$_RVWA[ 'db_database' ] = 'RVWA';
```

### Other Configuration

Depending on your Operating System, as well as version of PHP, you may wish to alter the default configuration. The location of the files will be different on a per-machine basis.

**Folder Permissions**:

* `./hackable/uploads/` - Needs to be writable by the web service (for File Upload).
* `./external/phpids/0.6/lib/IDS/tmp/phpids_log.txt` - Needs to be writable by the web service (if you wish to use PHPIDS).

**PHP configuration**:

* `allow_url_include = on` - Allows for Remote File Inclusions (RFI)   [[allow_url_include](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include)]
* `allow_url_fopen = on` -  Allows for Remote File Inclusions (RFI)    [[allow_url_fopen](https://secure.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen)]
* `safe_mode = off` - (If PHP <= v5.4) Allows for SQL Injection (SQLi) [[safe_mode](https://secure.php.net/manual/en/features.safe-mode.php)]
* `magic_quotes_gpc = off` - (If PHP <= v5.4) Allows for SQL Injection (SQLi) [[magic_quotes_gpc](https://secure.php.net/manual/en/security.magicquotes.php)]
* `display_errors = off` - (Optional) Hides PHP warning messages to make it less verbose [[display_errors](https://secure.php.net/manual/en/errorfunc.configuration.php#ini.display-errors)]

**File: `config/config.inc.php`**:

* `$_RVWA[ 'recaptcha_public_key' ]` & `$_RVWA[ 'recaptcha_private_key' ]` - These values need to be generated from: https://www.google.com/recaptcha/admin/create

### Default Credentials

**Default username = `admin`**

**Default password = `password`**

_...can easily be brute forced ;)_

Login URL: http://127.0.0.1/RVWA/login.php

### Troubleshooting

For the latest troubleshooting information please visit:
https://github.com/Paragsaxena08/RVWA/issues

+Q. SQL Injection won't work on PHP v5.2.6.

-A.If you are using PHP v5.2.6 or above, you will need to do the following in order for SQL injection and other vulnerabilities to work.

In `.htaccess`:

Replace (please note it may say mod_php7):

```php
<IfModule mod_php5.c>
    php_flag magic_quotes_gpc off
    #php_flag allow_url_fopen on
    #php_flag allow_url_include on
</IfModule>
```

With:

```php
<IfModule mod_php5.c>
    magic_quotes_gpc = Off
    allow_url_fopen = On
    allow_url_include = On
</IfModule>
```

+Q. Command Injection won't work.

-A. Apache may not have high enough privileges to run commands on the web server. If you are running RVWA under Linux make sure you are logged in as root. Under Windows log in as Administrator.

+Q. Why can't the database connect on CentOS?

-A. You may be running into problems with SELinux.  Either disable SELinux or run this command to allow the webserver to talk to the database:
```
setsebool -P httpd_can_network_connect_db 1
```

- - -

## Links

Homepage: http://www.RVWA.co.uk/

Project Home: https://github.com/Paragsaxena08/RVWA

*Created by the RVWA team*