# Adding the client to your app #

  * Add the four files from the clients/ios folder to your project
  * Edit your project's main Appname-Info.plist file, and add the following entry:
    * Key: TEDeviceTokenExchangeURL
    * Value: The url where you installed the tokenexchange server, INCLUDING the appId you want to use.
> > Example: http://example.com/tokenexchange/v1.1/?appId=myApp
  * Add the following import to your app delegate implementation file:
```
#import "TokenExchangeClient.h" 
```
  * Assuming your app is set up properly to receive push notifications, you must add the following code to your app delegate's didRegisterForRemotenotificationsWithDeviceToken method. This will trigger sending the deviceToken to the service and stores the notificationToken in the user settings:

```
- (void)application:(UIApplication *)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData *)deviceToken {
        [[TokenExchangeClient sharedInstance] exchangeDeviceToken:deviceToken];
}
```

This is all the code you really need. The client takes care of dealing with updated deviceTokens etc.

Note: in the plist entry, you can use variables such as ${PRODUCT\_NAME} for the appId if you like.

# Sending the notificationToken to the web service #

You are responsible for sending the user's notificationToken to the web service. This is deliberately not a feature of the TokenExchange, since every app will use a different mechanism to get the notificationToken known on the server side:

  * Some apps may just simply send it anonymously to retrieve generic push notifications
  * Some apps may want to send the notificationToken to a service along with user information (for example during account creation).

Just read the notificationToken from the client and use it as you see fit.

# Reading the notificationToken #

When you need the notificationToken, just include the header file:

```
#import "TokenExchangeClient.h"
```

and use the following code to retrieve the notificationToken:

```
NSString *token = [TokenExchangeClient sharedInstance].notificationToken;
```