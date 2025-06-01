/**
 * FCM Token Management Service
 * 
 * This service handles Firebase Cloud Messaging token registration
 * and management for the ecommerce application.
 */

class FcmTokenService {
    constructor(apiBaseUrl = '/api') {
        this.apiBaseUrl = apiBaseUrl;
    }

    /**
     * Save FCM token to the server
     * @param {Object} tokenData - Token data object
     * @param {string} tokenData.token - FCM token
     * @param {string} [tokenData.userId] - User ID
     * @param {string} [tokenData.platform] - Platform (android/ios/web)
     * @param {string} [tokenData.appVersion] - App version
     * @param {string} [tokenData.packageName] - Package name
     * @returns {Promise<Object>} API response
     */
    async saveFcmToken(tokenData) {
        const payload = {
            token: tokenData.token,
            userId: tokenData.userId || null,
            platform: tokenData.platform || this.detectPlatform(),
            timestamp: new Date().toISOString(),
            appVersion: tokenData.appVersion || this.getAppVersion(),
            packageName: tokenData.packageName || this.getPackageName()
        };

        try {
            const response = await fetch(`${this.apiBaseUrl}/fcm/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Failed to save FCM token');
            }

            console.log('FCM token saved successfully:', result.data);
            return result;

        } catch (error) {
            console.error('Error saving FCM token:', error);
            throw error;
        }
    }

    /**
     * Get all active tokens for a user
     * @param {string} userId - User ID
     * @returns {Promise<Array>} User tokens
     */
    async getUserTokens(userId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/fcm/user/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Failed to fetch user tokens');
            }

            return result.data;

        } catch (error) {
            console.error('Error fetching user tokens:', error);
            throw error;
        }
    }

    /**
     * Deactivate a specific token
     * @param {number} tokenId - Token ID
     * @returns {Promise<boolean>} Success status
     */
    async deactivateToken(tokenId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/fcm/deactivate/${tokenId}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Failed to deactivate token');
            }

            console.log('FCM token deactivated successfully');
            return true;

        } catch (error) {
            console.error('Error deactivating token:', error);
            return false;
        }
    }

    /**
     * Initialize Firebase and register FCM token
     * @param {Object} firebaseConfig - Firebase configuration
     * @param {string} [userId] - User ID if logged in
     * @returns {Promise<string>} FCM token
     */
    async initializeFirebase(firebaseConfig, userId = null) {
        try {
            // Import Firebase modules
            const { initializeApp } = await import('firebase/app');
            const { getMessaging, getToken } = await import('firebase/messaging');

            // Initialize Firebase
            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);

            // Request notification permission
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                throw new Error('Notification permission denied');
            }

            // Get FCM token
            const token = await getToken(messaging, {
                vapidKey: firebaseConfig.vapidKey
            });

            if (token) {
                // Save token to server
                await this.saveFcmToken({
                    token: token,
                    userId: userId
                });

                // Store token locally for future use
                localStorage.setItem('fcm_token', token);
                
                console.log('FCM initialization successful');
                return token;
            } else {
                throw new Error('No registration token available');
            }

        } catch (error) {
            console.error('Error initializing Firebase:', error);
            throw error;
        }
    }

    /**
     * Handle token refresh
     * @param {Function} messaging - Firebase messaging instance
     * @param {string} [userId] - User ID if logged in
     */
    setupTokenRefresh(messaging, userId = null) {
        // Listen for token refresh
        messaging.onTokenRefresh(async () => {
            try {
                const { getToken } = await import('firebase/messaging');
                const newToken = await getToken(messaging);
                
                if (newToken) {
                    console.log('FCM token refreshed');
                    
                    // Save new token
                    await this.saveFcmToken({
                        token: newToken,
                        userId: userId
                    });

                    // Update local storage
                    localStorage.setItem('fcm_token', newToken);
                }
            } catch (error) {
                console.error('Error handling token refresh:', error);
            }
        });
    }

    /**
     * Detect current platform
     * @returns {string} Platform name
     */
    detectPlatform() {
        const userAgent = navigator.userAgent.toLowerCase();
        
        if (userAgent.includes('android')) {
            return 'android';
        } else if (userAgent.includes('iphone') || userAgent.includes('ipad')) {
            return 'ios';
        } else {
            return 'web';
        }
    }

    /**
     * Get app version from meta tag or default
     * @returns {string} App version
     */
    getAppVersion() {
        const metaTag = document.querySelector('meta[name="app-version"]');
        return metaTag ? metaTag.content : '1.0.0';
    }

    /**
     * Get package name from meta tag or domain
     * @returns {string} Package name
     */
    getPackageName() {
        const metaTag = document.querySelector('meta[name="package-name"]');
        return metaTag ? metaTag.content : window.location.hostname;
    }

    /**
     * Clean up inactive tokens for a user
     * @param {string} userId - User ID
     */
    async cleanupUserTokens(userId) {
        try {
            const tokens = await this.getUserTokens(userId);
            const currentToken = localStorage.getItem('fcm_token');

            // Deactivate tokens that are not the current one
            for (const token of tokens) {
                if (token.token !== currentToken) {
                    await this.deactivateToken(token.id);
                }
            }

            console.log('Token cleanup completed');
        } catch (error) {
            console.error('Error during token cleanup:', error);
        }
    }
}

// Usage example:
/*
const fcmService = new FcmTokenService();

// Initialize Firebase and register token
const firebaseConfig = {
    apiKey: "your-api-key",
    authDomain: "your-auth-domain",
    projectId: "your-project-id",
    storageBucket: "your-storage-bucket",
    messagingSenderId: "your-sender-id",
    appId: "your-app-id",
    vapidKey: "your-vapid-key"
};

// When user logs in
fcmService.initializeFirebase(firebaseConfig, 'user123')
    .then(token => {
        console.log('FCM token registered:', token);
    })
    .catch(error => {
        console.error('Failed to register FCM token:', error);
    });

// When user logs out, clean up tokens
fcmService.cleanupUserTokens('user123');
*/

export default FcmTokenService;
