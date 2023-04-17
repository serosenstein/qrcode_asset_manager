![Add new device](https://github.com/serosenstein/qrcode_asset_manager/raw/main/add_new_device.png "Add new device")
![Screen After newly added device](https://github.com/serosenstein/qrcode_asset_manager/raw/main/screen_after_add.png "screen after newly added device")
![search all](https://github.com/serosenstein/qrcode_asset_manager/raw/main/search_all.png "search all")

#Pre Reqs:
- webserver with php configured
- qrencode installed in $PATH of web server user
- A mysql DB server that is allowing remote connections 
- A share beween /var/lib/mysql-files/ on mysql box to something on web server box
- Change the vars.php.example to vars.php and change the parameters to fit your env 
#To create an empty table called qrcodes (we are assuming this is in a DB also called qrcodes):
CREATE TABLE `qrcodes` (
  `device_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `device_name` varchar(255) DEFAULT NULL,
  `device_details` varchar(255) DEFAULT NULL,
  `qrcode` longblob NOT NULL,
  PRIMARY KEY (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

#Give qrcodes user grants from any host:


GRANT ALL PRIVILEGES ON `qrcodes`.* TO `qrcodes`@`%` WITH GRANT OPTION;
GRANT FILE ON *.* TO `qrcodes`@`%`;

#To see all your DB transactions:
select * from qrcodes;

#To clear your DB for all assets (assuming DB name is "qrcodes") and reset increment count to 0


######### (*NOTE* THIS WILL WIPE YOUR DB, USE WITH CAUTION) ###################


DELETE FROM qrcodes WHERE 1=1;

ALTER TABLE qrcodes MODIFY COLUMN device_id bigint(10) UNSIGNED;

COMMIT;

ALTER TABLE qrcodes MODIFY COLUMN device_id bigint(10) UNSIGNED AUTO_INCREMENT;

COMMIT;


# Be sure to clear all of the previously generated .pngs on the share!

* If you're getting an error about QR codes being NULL *
1) Make sure secure-file-priv=/var/lib/mysql-files is set in your my.cnf (requires DB bounce)
1a) SHOW VARIABLES LIKE "secure_file_priv"; -- this can be used to verify it's set properly and see what the value is, maybe also need to look at mysql "insecure mode";
2) make sure you have qrencode installed and it's in the users path that your webserver is running as
3) Tested on Rocky Linux with PHP and lighttpd and qrencode
	php-7.2.24-1.module+el8.4.0+413+c9202dda.x86_64
	php-pdo-7.2.24-1.module+el8.4.0+413+c9202dda.x86_64
	php-json-7.2.24-1.module+el8.4.0+413+c9202dda.x86_64
	php-cli-7.2.24-1.module+el8.4.0+413+c9202dda.x86_64
	php-mysqlnd-7.2.24-1.module+el8.4.0+413+c9202dda.x86_64
	qrencode-3.4.4-5.el8.x86_64
	qrencode-libs-3.4.4-5.el8.x86_64
	lighttpd-fastcgi-1.4.67-1.el8.x86_64
	lighttpd-mod_magnet-1.4.67-1.el8.x86_64
	lighttpd-mod_webdav-1.4.67-1.el8.x86_64
	lighttpd-1.4.67-1.el8.x86_64
	lighttpd-mod_authn_ldap-1.4.67-1.el8.x86_64
	lighttpd-mod_deflate-1.4.67-1.el8.x86_64
	lighttpd-filesystem-1.4.67-1.el8.noarch
	lighttpd-mod_openssl-1.4.67-1.el8.x86_64
	lighttpd-mod_vhostdb_ldap-1.4.67-1.el8.x86_64
