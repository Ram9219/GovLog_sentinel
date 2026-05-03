import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ============================================
// GovLog Sentinel — Frontend Modules
// ============================================

// Real-time log feed (toast notifications, critical alert polling)
import './realtime';

// WebSocket / polling fallback for push notifications
import './websocket';

// Dashboard-specific charts and stats (only runs on dashboard page)
import './dashboard';
