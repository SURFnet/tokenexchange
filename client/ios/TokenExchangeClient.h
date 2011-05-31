/**
 * Singleton helper class for registering the device token for push notifications.
 *
 * Also tries to abstract the device token in a more general notification
 * token which remains the same if the device token changed due to a clean
 * install of iOS after which a backup was restored etc.
 *
 * Before using the class, make sure you set TEDeviceTokenExchangeURL in your
 * application settings bundle. The value should be set to the url of your
 * TokenExchange server including the appId, for example:
 * http://example.com/tokenexchange/v1.1/?appId=yourapp
 */
@interface TokenExchangeClient : NSObject {
	NSMutableData *responseData;
}

/**
 * Returns the singleton instance of this class.
 *
 * @return singleton instance
 */
+ (TokenExchangeClient *)sharedInstance;


/**
 * Returns the notification token (if set).
 *
 * @return notification token
 */
@property (nonatomic, readonly, copy) NSString *notificationToken;


/**
 * Send a new registration request to the server using the given
 * device token.
 *
 * @param deviceToken device token
 */
- (void)exchangeDeviceToken:(NSData *)deviceToken;

@end
