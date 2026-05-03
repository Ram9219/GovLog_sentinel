/**
 * GovLog Sentinel — Real-time Log Updates Module
 * Polls for new logs and pushes them into the log table without full page reload
 */

class RealtimeLogFeed {
    constructor() {
        this.lastLogId = 0;
        this.pollInterval = null;
        this.isPolling = false;
        this.toastQueue = [];
        this.init();
    }

    init() {
        // Get the highest log ID currently on the page
        const rows = document.querySelectorAll('[data-log-id]');
        rows.forEach(row => {
            const id = parseInt(row.dataset.logId);
            if (id > this.lastLogId) this.lastLogId = id;
        });

        // Create toast container
        this.createToastContainer();

        // Start polling for critical alerts
        this.startAlertPolling();

        console.log('[GovLog] Realtime feed initialized, last log ID:', this.lastLogId);
    }

    /**
     * Create a floating toast notification container
     */
    createToastContainer() {
        if (document.getElementById('govlog-toasts')) return;

        const container = document.createElement('div');
        container.id = 'govlog-toasts';
        container.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem;max-width:400px;';
        document.body.appendChild(container);
    }

    /**
     * Show a toast notification for critical events
     */
    showToast(log) {
        const container = document.getElementById('govlog-toasts');
        if (!container) return;

        const severityColors = {
            critical: 'border-red-500 bg-red-50',
            emergency: 'border-red-700 bg-red-100',
            error: 'border-orange-500 bg-orange-50',
            warning: 'border-yellow-500 bg-yellow-50'
        };

        const colorClass = severityColors[log.severity] || 'border-blue-500 bg-blue-50';

        const toast = document.createElement('div');
        toast.className = `border-l-4 ${colorClass} p-4 rounded-r shadow-lg transition-all duration-300 transform translate-x-full`;
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-800">
                        ${log.severity === 'critical' ? '🚨' : '⚠️'} ${log.severity.toUpperCase()}
                    </p>
                    <p class="text-xs text-gray-600 mt-1">${log.message.substring(0, 120)}${log.message.length > 120 ? '...' : ''}</p>
                    <p class="text-xs text-gray-400 mt-1">IP: ${log.source_ip} • ${log.action_type}</p>
                </div>
                <button onclick="this.closest('div').remove()" class="ml-2 text-gray-400 hover:text-gray-600">×</button>
            </div>
        `;

        container.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        });

        // Auto-remove after 8 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 8000);
    }

    /**
     * Poll for new critical/emergency logs every 30 seconds
     */
    startAlertPolling() {
        const statsUrl = document.querySelector('meta[name="realtime-stats-url"]')?.content;
        if (!statsUrl) return;

        this.pollInterval = setInterval(() => {
            fetch(statsUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.critical_last_hour > 0) {
                    // Update critical badge if exists
                    const badge = document.getElementById('criticalBadge');
                    if (badge) {
                        badge.textContent = data.critical_last_hour;
                        badge.classList.remove('hidden');
                    }
                }
            })
            .catch(() => {}); // Silent fail
        }, 30000);
    }

    destroy() {
        if (this.pollInterval) clearInterval(this.pollInterval);
    }
}

// Auto-initialize on every page
window.realtimeLogFeed = new RealtimeLogFeed();

export default RealtimeLogFeed;
