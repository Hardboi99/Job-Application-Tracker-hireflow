document.addEventListener('DOMContentLoaded', () => {

    const rootUrl = window.location.origin;

    // ── Fetch stats from AJAX (or fallback to demo data) ──────────────
    const nonce = window.hfData ? window.hfData.nonce : '';

    function loadStats() {
        fetch(window.hfData ? window.hfData.ajaxurl : '/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=hf_get_stats&nonce=${encodeURIComponent(nonce)}`
        })
        .then(r => r.json())
        .then(res => {
            if (res.success && res.data) {
                updateStatCards(res.data);
                initCharts(res.data);
                populateInterviews(res.data.upcoming_interviews || []);
                populateRecentApps(res.data.recent_apps || []);
            } else {
                useDemoData();
            }
        })
        .catch(() => useDemoData());
    }

    function useDemoData() {
        const demo = {
            total: 14,
            applied: 7,
            interview: 3,
            rejected: 2,
            offer: 1,
            accepted: 1,
            statusDistribution: { applied: 7, interview: 3, rejected: 2, offer: 1, accepted: 1 },
            monthlyApps: {
                labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                data: [1, 3, 4, 2, 3, 1]
            },
            upcoming_interviews: [
                { company: 'Google', job_title: 'Frontend Engineer', interview_date: '2026-08-05' },
                { company: 'Atlassian', job_title: 'WordPress Developer', interview_date: '2026-08-12' },
                { company: 'Shopify', job_title: 'Full Stack Dev', interview_date: '2026-08-18' },
            ],
            recent_apps: [
                { company: 'Google', job_title: 'Frontend Engineer', status: 'interview', date: 'Jul 20' },
                { company: 'Spotify', job_title: 'PHP Developer', status: 'applied', date: 'Jul 18' },
                { company: 'Netflix', job_title: 'React Developer', status: 'rejected', date: 'Jul 15' },
                { company: 'Shopify', job_title: 'Full Stack Dev', status: 'offer', date: 'Jul 10' },
                { company: 'Atlassian', job_title: 'WP Engineer', status: 'applied', date: 'Jul 5' },
            ]
        };
        updateStatCards(demo);
        initCharts(demo);
        populateInterviews(demo.upcoming_interviews);
        populateRecentApps(demo.recent_apps);
    }

    // ── Stat card counter animation ───────────────────────────────────
    function updateStatCards(data) {
        const map = {
            'hf-stat-total':      data.total || 0,
            'hf-stat-interviews': data.interview || 0,
            'hf-stat-offers':     data.offer || 0,
            'hf-stat-rejections': data.rejected || 0
        };

        Object.entries(map).forEach(([id, target]) => {
            const el = document.getElementById(id);
            if (!el) return;
            animateCounter(el, target);
        });
    }

    function animateCounter(el, target) {
        let current = 0;
        const step = Math.max(1, Math.ceil(target / 30));
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 40);
    }

    // ── Charts ────────────────────────────────────────────────────────
    function initCharts(data) {
        // inject canvas elements if they don't exist yet
        const statusContainer  = document.getElementById('hf-status-chart-container');
        const timelineContainer = document.getElementById('hf-timeline-chart-container');

        if (statusContainer && !document.getElementById('statusChart')) {
            const c = document.createElement('canvas');
            c.id = 'statusChart';
            statusContainer.appendChild(c);
        }
        if (timelineContainer && !document.getElementById('timelineChart')) {
            const c = document.createElement('canvas');
            c.id = 'timelineChart';
            timelineContainer.appendChild(c);
        }

        const statusCtx   = document.getElementById('statusChart');
        const timelineCtx = document.getElementById('timelineChart');

        if (typeof Chart === 'undefined') return; // chart.js not loaded yet

        const dist = data.statusDistribution || {};
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Applied', 'Interview', 'Rejected', 'Offer', 'Accepted'],
                    datasets: [{
                        data: [
                            dist.applied  || 0,
                            dist.interview || 0,
                            dist.rejected  || 0,
                            dist.offer     || 0,
                            dist.accepted  || 0
                        ],
                        backgroundColor: ['#3B82F6','#F59E0B','#EF4444','#10B981','#00C9A7'],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#8BA3C7', padding: 12, font: { size: 12 } }
                        }
                    }
                }
            });
        }

        const monthly = data.monthlyApps || {};
        if (timelineCtx) {
            new Chart(timelineCtx, {
                type: 'bar',
                data: {
                    labels: monthly.labels || [],
                    datasets: [{
                        label: 'Applications',
                        data: monthly.data || [],
                        backgroundColor: 'rgba(0,201,167,0.7)',
                        borderRadius: 6,
                        hoverBackgroundColor: '#00C9A7'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#8BA3C7' },
                            grid: { color: 'rgba(139,163,199,0.08)' }
                        },
                        x: {
                            ticks: { color: '#8BA3C7' },
                            grid: { display: false }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }
    }

    // ── Upcoming Interviews ───────────────────────────────────────────
    function populateInterviews(interviews) {
        const container = document.getElementById('hf-upcoming-interviews-list');
        if (!container) return;

        if (!interviews.length) {
            container.innerHTML = '<p style="color:#8BA3C7;text-align:center;padding:1.5rem 0;">No upcoming interviews.</p>';
            return;
        }

        container.innerHTML = interviews.map(iv => {
            const d = new Date(iv.interview_date);
            const day   = isNaN(d) ? '--' : d.getDate();
            const month = isNaN(d) ? '--' : d.toLocaleString('default', { month: 'short' });
            return `
            <div class="hf-interview-item">
                <div class="hf-interview-date-box">
                    <span class="day">${day}</span>
                    <span class="month">${month}</span>
                </div>
                <div class="hf-interview-info">
                    <h4>${escHtml(iv.job_title)}</h4>
                    <p>${escHtml(iv.company)}</p>
                </div>
            </div>`;
        }).join('');
    }

    // ── Recent Applications Table ─────────────────────────────────────
    function populateRecentApps(apps) {
        const container = document.getElementById('hf-recent-applications-table');
        if (!container) return;

        if (!apps.length) {
            container.innerHTML = '<p style="color:#8BA3C7;text-align:center;padding:1.5rem 0;">No applications yet.</p>';
            return;
        }

        const badgeClass = { applied:'applied', interview:'interview', rejected:'rejected', offer:'offer', accepted:'accepted' };
        const labels = { applied:'Applied', interview:'Interview', rejected:'Rejected', offer:'Offer', accepted:'Accepted' };

        const rows = apps.map(a => `
            <tr>
                <td><strong>${escHtml(a.company)}</strong><br><small style="color:#8BA3C7">${escHtml(a.job_title)}</small></td>
                <td><span class="hf-badge hf-badge-${badgeClass[a.status] || 'applied'}">${labels[a.status] || a.status}</span></td>
                <td style="color:#8BA3C7;font-size:0.82rem">${escHtml(a.date)}</td>
            </tr>`).join('');

        container.innerHTML = `
            <table class="hf-app-table">
                <thead>
                    <tr>
                        <th>Company / Role</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>`;
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    loadStats();
});
