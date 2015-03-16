# Introduction #

This page explains how the TokenExchange works with a few simple diagrams.

# First time startup #

![http://tokenexchange.googlecode.com/svn/wiki/img/tx_register_device.png](http://tokenexchange.googlecode.com/svn/wiki/img/tx_register_device.png)

  1. The app starts up and asks the TokenExchangeClient to send the deviceToken to the TokenExchange server
  1. The server generates a unique notificationToken for this app and returns it.
  1. The TokenExchangeClient stores the notificationToken in the user's preferences for the app.
  1. The app is responsible for communicating the notificationToken to the web service that is responsible for sending push notifications.

# Sending push notifications #

![http://tokenexchange.googlecode.com/svn/wiki/img/tx_send_push.png](http://tokenexchange.googlecode.com/svn/wiki/img/tx_send_push.png)

  1. A server wants to send a push notification to a user.
  1. It sends the notificationToken of the user to the TokenExchange.
  1. In return, it retrieves the latest deviceToken for this user.
  1. The app sends a push notification using the deviceToken.

Sending the actual notification is not part of this project, but if your service is written in PHP, we recommend looking at [ApnsPHP](http://code.google.com/p/apns-php/).

# Updating the device token #

What happens when the deviceToken of the user changes?

![http://tokenexchange.googlecode.com/svn/wiki/img/tx_update_device.png](http://tokenexchange.googlecode.com/svn/wiki/img/tx_update_device.png)

  1. The app sends its new deviceToken along with its existing notificationToken to the TokenExchange.
  1. The TokenExchange returns a notificationToken.
  1. Although the server doesn't change the notificationToken, we reserved the possibility to update the tokens in the future. Therefor the TokenExchange sends the notificationToken back
  1. If the notificationToken was changed, the app should update the notificationToken for the user in its webservices.