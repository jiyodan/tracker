the .php files and the image folder need putting inside of a folder called "shows" inside of the htdocs folder in xampp/lampp. and the .sql file needs importing using the phpmyadmin web interface. <br/>
<br/>
you need to create a new database called "tracker" and then import the sql inside of that. the table should be called "shows".<br/>
<br/>
you need to add a user inside of phpmyadmin called "tracker" with the password "password" and give it full priveleges. <br/>
<br/>
once those steps have been done you should be able to start the apache and mysql using the "xamp control panel" in windows or in linux by navigating to "/opt/lampp" and executing the command "./lampp start"
to access the website you navigate to "IPADDRESS/shows/index.php"<br/>

![screenshot](screenshot.png)
