/**
 * GovLog Sentinel — Dashboard Module
 * Handles real-time stats refresh, notification badge, and chart interactions
 */

class DashboardManager {
    constructor() {
        this.statsInterval = null;
        this.notificationCount = 0;
        this.init();
    }

    init() {
        // Start real-time stats polling
        this.startRealtimePolling();

        // Setup notification bell
        this.setupNotificationBell();

        // Setup log stream auto-scroll
        this.setupLogStream();

        console.log('[GovLog] Dashboard initialized');
    }

    /**
     * Poll /admin/dashboard/realtime-stats every 15 seconds
     */
    startRealtimePolling() {
        const statsUrl = document.querySelector('meta[name="realtime-stats-url"]')?.content;
        if (!statsUrl) return;

        const update = () => {
            fetch(statsUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                // Update live counters
                this.animateCounter('activeIPs', data.unique_ips || 0);
                this.animateCounter('logsPerMinute', data.logs_last_minute || 0);
                this.animateCounter('criticalLastHour', data.critical_last_hour || 0);

                // Flash indicator
                const indicator = document.getElementById('liveIndicator');
                if (indicator) {
                    indicator.classList.add('animate-ping');
                    setTimeout(() => indicator.classList.remove('animate-ping'), 1000);
                }
            })
            .catch(err => console.warn('[GovLog] Stats fetch failed:', err));
        };

        update();
        this.statsInterval = setInterval(update, 15000);
    }

    /**
     * Animate number counter from current to target value
     */
    animateCounter(elementId, targetValue) {
        const el = document.getElementById(elementId);
        if (!el) return;

        const current = parseInt(el.textContent) || 0;
        if (current === targetValue) return;

        const diff = targetValue - current;
        const steps = 10;
        const stepValue = diff / steps;
        let step = 0;

        const timer = setInterval(() => {
            step++;
            el.textContent = Math.round(current + stepValue * step);
            if (step >= steps) {
                el.textContent = targetValue;
                clearInterval(timer);
            }
        }, 30);
    }

    /**
     * Setup notification bell with unread count
     */
    setupNotificationBell() {
        const bell = document.getElementById('notificationBell');
        if (!bell) return;

        bell.addEventListener('click', () => {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        });
    }

    /**
     * Auto-scroll the live log stream panel
     */
    setupLogStream() {
        const stream = document.getElementById('logStream');
        if (!stream) return;

        // Auto-scroll to bottom on new entries
        const observer = new MutationObserver(() => {
            stream.scrollTop = stream.scrollHeight;
        });
        observer.observe(stream, { childList: true });
    }

    /**
     * Filter dashboard data by date range
     */
    filterByDateRange(startDate, endDate) {
        const url = new URL(window.location);
        url.searchParams.set('date_from', startDate);
        url.searchParams.set('date_to', endDate);
        window.location = url;
    }

    destroy() {
        if (this.statsInterval) {
            clearInterval(this.statsInterval);
        }
    }
}

// Auto-initialize on pages with dashboard elements
if (document.getElementById('classificationChart') || document.getElementById('activeIPs')) {
    window.dashboardManager = new DashboardManager();
}

export default DashboardManager;
