<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="application-name" content="ER Navigator Free">
    <meta name="author" content="Jacob Russell">
    <meta name="contact" content="jacobrussell.nremtp@gmail.com">
    <meta name="version" content="1.0.0-Free">
    <meta name="license" content="MIT">
    <meta name="copyright" content="Copyright 2024 Jacob Russell">
    
    <title>ER Navigator Pro</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root {
            --primary: #002d62; 
            --accent: #d32f2f;
            --bg: #f4f7f9;
        }

        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background: var(--bg); 
            margin: 0; 
            padding: 0; 
        }

        header {
            background: var(--primary);
            color: white;
            padding: 10px 15px;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 1001;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* MINI MAP STYLING */
        #map { 
            display: block; 
            width: 80px; 
            height: 80px; 
            border-radius: 8px;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        #status-bar {
            background: #ffc107;
            color: #856404;
            font-size: 0.75rem;
            padding: 6px;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid #e0a800;
        }

        .gps-success {
            background: #d4edda !important;
            color: #155724 !important;
        }

        /* UPDATED FILTER BAR: TWO ROWS, LEFT ALIGNED */
        .filter-bar {
            display: flex;
            flex-wrap: wrap; /* Allows wrapping to two rows */
            background: white;
            padding: 10px;
            gap: 6px;
            border-bottom: 1px solid #ddd;
            justify-content: flex-start; /* Aligns buttons to the left */
        }

        .filter-btn {
            padding: 6px 12px;
            border-radius: 15px;
            border: 1px solid #ddd;
            background: #f8f9fa;
            font-size: 0.7rem;
            font-weight: bold;
            flex: 0 1 auto; /* Allows buttons to size to content */
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        #hospital-list { padding: 12px; }

        .hospital-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-left: 6px solid var(--primary);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .hospital-name {
            color: var(--primary);
            font-size: 1.1rem;
            font-weight: 800;
            margin: 0;
        }

        .tag {
            display: inline-block;
            background: #eef2f7;
            color: #445;
            font-size: 0.65rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            margin: 6px 4px 0 0;
            border: 1px solid #d1d9e0;
        }

        .dist-txt {
            font-weight: bold;
            color: var(--accent);
            font-size: 0.9rem;
            background: #fff5f5;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .codes-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 10px;
            background: #f1f4f8;
            border-radius: 10px;
            padding: 12px;
            margin: 15px 0;
            cursor: pointer;
            align-items: center;
        }

        .door-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .code-label { font-size: 0.6rem; color: #666; font-weight: bold; text-transform: uppercase; }
        .code-val { font-size: 1.2rem; font-weight: 900; color: #000; font-family: monospace; }
        .code-locked { filter: blur(5px); user-select: none; color: #999; }

        .submit-link {
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: bold;
            color: #007bff;
            background: white;
            border: 1px solid #007bff;
            padding: 6px 10px;
            border-radius: 6px;
        }

        .action-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn {
            text-decoration: none;
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .btn-phone { background: #e8f5e9; color: #2e7d32; border: 1px solid #2e7d32; }
        .btn-nav { background: var(--primary); color: white; }
    </style>
</head>
<body>

<header>
    <div style="font-weight: 900; letter-spacing: 1px; font-size: 0.9rem;">DFW ER NAVIGATOR</div>
    <div id="map"></div>
</header>

<div id="status-bar">🛰️ SEARCHING FOR GPS...</div>

<div class="filter-bar">
    <button class="filter-btn active" onclick="setFilter('ALL', this)">ALL</button>
    <button class="filter-btn" onclick="setFilter('TRAUMA', this)">TRAUMA</button>
    <button class="filter-btn" onclick="setFilter('PSC', this)">STROKE (PSC)</button>
    <button class="filter-btn" onclick="setFilter('CSC', this)">STROKE (CSC)</button>
    <button class="filter-btn" onclick="setFilter('STEMI', this)">STEMI</button>
    <button class="filter-btn" onclick="setFilter('PEDS', this)">PEDS</button>
    <button class="filter-btn" onclick="setFilter('HBOT', this)">HBOT</button>
    <button class="filter-btn" onclick="setFilter('BURNS', this)">BURNS</button>
</div>

<div id="hospital-list"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let db = [];
    let currentFilter = "ALL";
    let codesUnlocked = sessionStorage.getItem("codesUnlocked") === "true";
    let map, userMarker;

    // Initialize the mini map
    function initMap() {
        map = L.map('map', {
            zoomControl: false,
            attributionControl: false
        }).setView([32.7767, -96.7970], 13); // Default to Dallas

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        userMarker = L.circleMarker([32.7767, -96.7970], {
            radius: 8,
            fillColor: "#007bff",
            color: "#fff",
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map);
    }

    async function loadData() {
        initMap();
        try {
            const response = await fetch('hospitals.json');
            db = await response.json();
            renderList();
            startGps();
        } catch (e) {
            document.getElementById('hospital-list').innerHTML = "JSON Load Error.";
        }
    }

    function attemptUnlock() {
        if (codesUnlocked) return;
        const doorPass = prompt("Enter Door Code Password:");
        if (doorPass === "EMS") { 
            codesUnlocked = true;
            sessionStorage.setItem("codesUnlocked", "true");
            renderList();
        } else {
            alert("Incorrect Password.");
        }
    }

    function setFilter(type, btn) {
        currentFilter = type;
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderList();
    }

    function renderList() {
        const list = document.getElementById('hospital-list');
        list.innerHTML = "";

        const filtered = db.filter(h => {
            if (currentFilter === "ALL") return true;
            if (currentFilter === "TRAUMA") return (h.lvl === "I" || h.lvl === "II");
            return h.tags.includes(currentFilter);
        });

        filtered.forEach(h => {
            const mailSub = encodeURIComponent(`Code Update: ${h.n}`);
            const mailBody = encodeURIComponent(`Hospital: ${h.n}\nNew Door Code:\nNew EMS Room Code:`);
            const lockClass = codesUnlocked ? "" : "code-locked";
            const displayDC = codesUnlocked ? (h.dc || '----') : "XXXX";
            const displayBC = codesUnlocked ? (h.bc || '----') : "XXXX";

            list.innerHTML += `
                <div class="hospital-card">
                    <div class="card-header">
                        <div style="flex:1;">
                            <h2 class="hospital-name">${h.n}</h2>
                            <div style="font-size:0.7rem; color:#666; margin-top:2px;">LVL ${h.lvl} • ${h.c}</div>
                            <div>${h.tags.map(t => `<span class="tag">${t}</span>`).join('')}</div>
                        </div>
                        <div class="dist-txt">${h.dist ? h.dist + ' mi' : '--'}</div>
                    </div>

                    <div class="codes-row" onclick="attemptUnlock()">
                        <div class="door-container">
                            <span class="door-icon">${codesUnlocked ? '🔓' : '🔒'}</span>
                            <div>
                                <span class="code-label">ER Door</span>
                                <span class="code-val ${lockClass}">${displayDC}</span>
                            </div>
                        </div>
                        <div class="door-container" style="border-left: 1px solid #ccc; padding-left: 10px;">
                            <div>
                                <span class="code-label">EMS Room</span>
                                <span class="code-val ${lockClass}">${displayBC}</span>
                            </div>
                        </div>
                        <a href="mailto:yourditchdoc@gmail.com?subject=${mailSub}&body=${mailBody}" class="submit-link" onclick="event.stopPropagation()">EDIT</a>
                    </div>

                    <div class="action-row">
                        <a href="tel:${h.p}" class="btn btn-phone">CALL ER</a>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${h.lat},${h.lng}" target="_blank" class="btn btn-nav">NAVIGATE</a>
                    </div>
                </div>
            `;
        });
    }

    function startGps() {
        if ("geolocation" in navigator) {
            navigator.geolocation.watchPosition((pos) => {
                const uLat = pos.coords.latitude;
                const uLon = pos.coords.longitude;
                
                // Update Mini Map
                const newPos = [uLat, uLon];
                map.setView(newPos, 14);
                userMarker.setLatLng(newPos);

                const sb = document.getElementById('status-bar');
                sb.innerText = `✅ GPS FOUND: ${uLat.toFixed(4)}, ${uLon.toFixed(4)}`;
                sb.classList.add('gps-success');

                db.forEach(h => {
                    const R = 3958.8; 
                    const dLat = (h.lat - uLat) * Math.PI / 180;
                    const dLon = (h.lng - uLon) * Math.PI / 180;
                    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                              Math.cos(uLat * Math.PI / 180) * Math.cos(h.lat * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    h.dist = (R * c).toFixed(1);
                });

                db.sort((a, b) => (parseFloat(a.dist) || 999) - (parseFloat(b.dist) || 999));
                renderList();
            }, (err) => {
                document.getElementById('status-bar').innerText = "❌ GPS ERROR - SORTING DISABLED";
            }, { enableHighAccuracy: true });
        }
    }

    loadData();
</script>

</body>
</html>
