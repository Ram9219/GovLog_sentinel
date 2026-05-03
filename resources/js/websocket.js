/**
 * GovLog Sentinel — WebSocket / Server-Sent Events Module
 * Provides real-time push notifications when a WebSocket server is available,
 * gracefully degrades to polling when not.
 */

class GovLogWebSocket {
    constructor() {
        this.connection = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 3000;
        this.listeners = new Map();

        this.init();
    }

    init() {
        // Check if Pusher/Echo is configured
        if (window.Echo) {
            this.connectViaEcho();
        } else {
            // Fallback: use long-polling via fetch
            console.log('[GovLog WS] No WebSocket server detected, using polling fallback');
            this.startPollingFallback();
        }
    }

    /**
     * Connect via Laravel Echo (Pusher/WebSocket)
     */
    connectViaEcho() {
        try {
            window.Echo.channel('govlog-alerts')
                .listen('CriticalLogEvent', (event) => {
                    this.handleEvent('critical_alert', event);
                })
                .listen('LogCreated', (event) => {
                    this.handleEvent('log_created', event);
                });

            console.log('[GovLog WS] Connected via Laravel Echo');
        } catch (e) {
            console.warn('[GovLog WS] Echo connection failed, falling back to polling');
            this.startPollingFallback();
        }
    }

    /**
     * Polling fallback when no WebSocket is available
     */
    startPollingFallback() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const statsUrl = document.querySelector('meta[name="realtime-stats-url"]')?.content;

        if (!statsUrl) return;

        setInterval(() => {
            fetch(statsUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.handleEvent('stats_update', data);
            })
            .catch(() => {}); // Silent
        }, 30000);
    }

    /**
     * Handle incoming events from WebSocket or polling
     */
    handleEvent(type, data) {
        const callbacks = this.listeners.get(type) || [];
        callbacks.forEach(cb => {
            try { cb(data); } catch (e) { console.error('[GovLog WS] Listener error:', e); }
        });
    }

    /**
     * Register event listener
     */
    on(eventType, callback) {
        if (!this.listeners.has(eventType)) {
            this.listeners.set(eventType, []);
        }
        this.listeners.get(eventType).push(callback);
        return this;
    }

    /**
     * Remove event listener
     */
    off(eventType, callback) {
        const callbacks = this.listeners.get(eventType) || [];
        this.listeners.set(eventType, callbacks.filter(cb => cb !== callback));
        return this;
    }

    destroy() {
        if (this.connection) {
            this.connection.close();
        }
        this.listeners.clear();
    }
}

// Auto-initialize
window.govlogWS = new GovLogWebSocket();

export default GovLogWebSocket;
