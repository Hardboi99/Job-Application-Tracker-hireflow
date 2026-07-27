document.addEventListener('DOMContentLoaded', () => {
    // Only run if we have canvas elements for charts
    const statusCtx = document.getElementById('statusChart');
    const timelineCtx = document.getElementById('timelineChart');
    
    if (!statusCtx && !timelineCtx) return;

    // Fetch Stats from REST API
    // Note: Assuming the REST API endpoint is /wp-json/hireflow/v1/stats
    // Fallback data provided in case API is not yet built in Phase 1
    
    const rootUrl = window.location.origin; 
    const apiUrl = `${rootUrl}/wp-json/hireflow/v1/stats`;

    fetch(apiUrl)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(data => {
            initCharts(data);
            animateCounters();
        })
        .catch(err => {
            console.warn('REST API not available yet, using fallback data for UI demonstration.', err);
            const fallbackData = {
                statusDistribution: {
                    applied: 12,
                    interview: 5,
                    rejected: 3,
                    offer: 2,
                    accepted: 1
                },
                monthlyApps: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    data: [2, 5, 4, 8, 3, 1]
                }
            };
            initCharts(fallbackData);
            animateCounters();
        });

    function initCharts(data) {
        // Doughnut Chart for Status
        if (statusCtx && data.statusDistribution) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Applied', 'Interview', 'Rejected', 'Offer', 'Accepted'],
                    datasets: [{
                        data: [
                            data.statusDistribution.applied || 0,
                            data.statusDistribution.interview || 0,
                            data.statusDistribution.rejected || 0,
                            data.statusDistribution.offer || 0,
                            data.statusDistribution.accepted || 0
                        ],
                        backgroundColor: [
                            '#3B82F6', // Applied
                            '#F59E0B', // Interview
                            '#EF4444', // Rejected
                            '#10B981', // Offer
                            '#00C9A7'  // Accepted
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#E8F0FE' }
                        }
                    },
                    cutout: '75%'
                }
            });
        }

        // Bar Chart for Monthly Applications
        if (timelineCtx && data.monthlyApps) {
            new Chart(timelineCtx, {
                type: 'bar',
                data: {
                    labels: data.monthlyApps.labels,
                    datasets: [{
                        label: 'Applications',
                        data: data.monthlyApps.data,
                        backgroundColor: '#00C9A7',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#8BA3C7' },
                            grid: { color: 'rgba(139, 163, 199, 0.1)' }
                        },
                        x: {
                            ticks: { color: '#8BA3C7' },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    }

    function animateCounters() {
        const counters = document.querySelectorAll('.hf-counter');
        const speed = 200; // The lower the slower

        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            if (!target) return;
            
            const updateCount = () => {
                const count = +counter.innerText;
                const inc = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 15);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    }
});
