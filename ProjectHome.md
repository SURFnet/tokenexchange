Apple, Google and Blackberry send Push Notifications to mobile phones based on device tokens. The Token Exchange is a small service, written in PHP, that allows apps and webservices to exchange device tokens for a more generic notification token. When a user's device token changes (e.g. when they start using a new phone), many applications stop sending push notifications. When using the Token Exchange, the user's notification token doesn't change, and they can continue to receive notifications with their new phone.

One instance of the Token Exchange server can collect tokens for multiple apps; both the device and the webservice that wants to send push notifications use an AppId to retrieve the right tokens.

The project was sponsored by SURFnet and Egeniq.

Available clients (in the clients/ subdir of the software package):
  * PHP
  * iOS

More may be added in the future, contributions are welcome.

Although Blackberry and Android clients are not yet included, the server side packages is multi-device capable and supports ios, android and blackberry devices.

Getting started:

  * [How the token exchange works](TokenExchange.md)
  * [Installation of the server](Server.md)
  * [Using it on the iPhone](iPhone.md)
  * [Using the webbased client](Client.md)

Support:

Please use the issue tracker in the top menu to report any issues you find. If you need additional support, please contact [Egeniq](http://www.egeniq.com).