/**
 * Booking WebSocket Client
 * File ini berisi implementasi client untuk WebSocket yang memantau dan menangani status booking
 */

class BookingSocket {
    constructor(options = {}) {
        this.options = {
            reconnectInterval: 5000, // Reconnect every 5 seconds
            maxReconnectAttempts: 10,
            debug: false,
            ...options
        };
        this.socket = null;
        this.reconnectAttempts = 0;
        this.isConnected = false;
        this.callbacks = {
            onOpen: [],
            onMessage: [],
            onClose: [],
            onError: [],
            onBookingExpired: []
        };
    }

    /**
     * Connect to WebSocket server
     * @param {string} url WebSocket URL
     */
    connect(url) {
        if (this.socket && (this.socket.readyState === WebSocket.OPEN || this.socket.readyState === WebSocket.CONNECTING)) {
            this.log('Socket already connected or connecting');
            return;
        }

        this.log(`Connecting to ${url}...`);
        try {
            this.socket = new WebSocket(url);
            this.setupEventHandlers();
        } catch (error) {
            this.log('Connection error:', error);
            this.reconnect(url);
        }
    }

    /**
     * Setup WebSocket event handlers
     */
    setupEventHandlers() {
        this.socket.onopen = (event) => {
            this.log('WebSocket connected');
            this.isConnected = true;
            this.reconnectAttempts = 0;
            this.callbacks.onOpen.forEach(callback => callback(event));
        };

        this.socket.onmessage = (event) => {
            this.log('Message received:', event.data);
            
            try {
                const data = JSON.parse(event.data);
                
                // Handle booking expired event
                if (data.type === 'booking_expired') {
                    this.callbacks.onBookingExpired.forEach(callback => callback(data));
                }
                
                // Call all message callbacks
                this.callbacks.onMessage.forEach(callback => callback(data));
            } catch (error) {
                this.log('Error parsing message:', error);
            }
        };

        this.socket.onclose = (event) => {
            this.log('WebSocket disconnected');
            this.isConnected = false;
            this.callbacks.onClose.forEach(callback => callback(event));
            
            // Reconnect if the socket was closed unexpectedly
            if (event.code !== 1000) {
                this.reconnect(this.socket.url);
            }
        };

        this.socket.onerror = (event) => {
            this.log('WebSocket error:', event);
            this.callbacks.onError.forEach(callback => callback(event));
        };
    }

    /**
     * Reconnect to WebSocket server
     * @param {string} url WebSocket URL
     */
    reconnect(url) {
        if (this.reconnectAttempts >= this.options.maxReconnectAttempts) {
            this.log('Max reconnect attempts reached');
            return;
        }

        this.reconnectAttempts++;
        this.log(`Reconnecting in ${this.options.reconnectInterval}ms... (Attempt ${this.reconnectAttempts}/${this.options.maxReconnectAttempts})`);
        
        setTimeout(() => {
            this.log('Attempting to reconnect...');
            this.connect(url);
        }, this.options.reconnectInterval);
    }

    /**
     * Send data to the WebSocket server
     * @param {Object} data Data to send
     */
    send(data) {
        if (!this.socket || this.socket.readyState !== WebSocket.OPEN) {
            this.log('Cannot send message, socket is not open');
            return false;
        }

        try {
            const message = typeof data === 'string' ? data : JSON.stringify(data);
            this.socket.send(message);
            return true;
        } catch (error) {
            this.log('Error sending message:', error);
            return false;
        }
    }

    /**
     * Register callback for specific event
     * @param {string} event Event name
     * @param {Function} callback Callback function
     */
    on(event, callback) {
        if (this.callbacks[event]) {
            this.callbacks[event].push(callback);
        }
    }

    /**
     * Remove callback for specific event
     * @param {string} event Event name
     * @param {Function} callback Callback function
     */
    off(event, callback) {
        if (this.callbacks[event]) {
            this.callbacks[event] = this.callbacks[event].filter(cb => cb !== callback);
        }
    }

    /**
     * Check booking status
     * @param {string} bookingCode Booking code to check
     */
    checkBookingStatus(bookingCode) {
        this.send({
            type: 'check_booking',
            booking_code: bookingCode
        });
    }

    /**
     * Close WebSocket connection
     */
    close() {
        if (this.socket) {
            this.socket.close();
        }
    }

    /**
     * Log debug messages
     */
    log(...args) {
        if (this.options.debug) {
            console.log('[BookingSocket]', ...args);
        }
    }
}

// Check if we're in a browser environment
if (typeof window !== 'undefined') {
    window.BookingSocket = BookingSocket;
}