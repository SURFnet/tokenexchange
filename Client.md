todo. For now, see the sample.php file in the clients/php directory for an overview of how the client works.

From the command line:

create:

    $ curl https://tx.example.org/ -d appId=test -d deviceToken=DEADBEEF
    0123456789abcdef0123456789abcdef01234567

retrieve a device token for a specific notification token:

     $ curl 'https://tx.example.org/ -d appId=test -d notificationToken=0123456789abcdef0123456789abcdef01234567'
    DEADBEEF

update an existing notification token:

    $ curl 'https://tx.example.org/ -d appId=test -d notificationToken=0123456789abcdef0123456789abcdef01234567 -d deviceToken=CAFEBABE'
    0123456789abcdef0123456789abcdef01234567

retrieve a device token after update:

    $ curl https://tx.example.org/ -d appId=test -d notificationToken=0123456789abcdef0123456789abcdef01234567
    CAFEBABE

