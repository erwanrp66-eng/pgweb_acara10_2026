<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jelajah Lembaga Pendidikan | Yogyakarta</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
      :root {
        color-scheme: light;
        --ink: #302638;
        --muted: #716779;
        --line: #e7dfe9;
        --surface: #fff;
        --canvas: #faf8fb;
        --rose: #f1dce8;
        --peach: #f8e5d7;
        --butter: #f8efcf;
        --mint: #deeee4;
        --lilac: #e8e1f3;
        --accent: #79516d;
        --accent-dark: #603e56;
        --shadow: 0 12px 32px rgb(52 35 58 / 6%);
        font-family: Inter, "Segoe UI", Arial, sans-serif;
        color: var(--ink);
        background: var(--canvas);
      }

      * { box-sizing: border-box; }

      html, body {
        margin: 0;
        min-height: 100%;
      }

      body {
        min-width: 320px;
        min-height: 100vh;
        background:
          radial-gradient(ellipse at top left, #f3eaf3 0, transparent 38rem),
          var(--canvas);
      }

      .topbar {
        width: min(100% - 40px, 1440px);
        margin: 0 auto;
        padding: 48px 0 24px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
      }

      .topbar h1 {
        margin: 0;
        font-size: clamp(1.7rem, 3vw, 2.4rem);
        line-height: 1.15;
      }

      .topbar p {
        margin: 0 0 8px;
        color: var(--accent);
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.12em;
      }

      .brand-mark {
        display: none;
      }

      .brand {
        display: block;
      }

      .topbar-tag {
        display: inline-flex;
        min-height: 42px;
        align-items: center;
        justify-content: center;
        padding: 9px 14px;
        border: 1px solid transparent;
        border-radius: 7px;
        background: var(--accent);
        color: #fff;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: background-color 140ms ease, transform 140ms ease;
      }

      .topbar-tag:hover {
        background: var(--accent-dark);
        transform: translateY(-1px);
      }

      .dashboard {
        display: grid;
        width: min(100% - 40px, 1440px);
        margin: 0 auto;
        grid-template-columns: minmax(290px, 350px) minmax(0, 1fr);
        gap: 18px;
        padding: 0 0 48px;
        min-height: 0;
      }

      .sidebar {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 12px;
        box-shadow: var(--shadow);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        min-height: 620px;
      }

      .stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
      }

      .stat-card {
        background: #faf8fb;
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 12px 10px;
      }

      .stat-card:nth-child(1) { background: #f3eef4; }
      .stat-card:nth-child(2) { background: var(--rose); }
      .stat-card:nth-child(3) { background: var(--peach); }
      .stat-card:nth-child(4) { background: var(--mint); }

      .stat-card .label {
        display: block;
        font-size: 0.72rem;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
      }

      .stat-card .value {
        display: block;
        margin-top: 7px;
        font-size: 1.45rem;
        font-weight: bold;
        color: var(--accent-dark);
      }

      .side-title {
        margin: 0;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--muted);
      }

      .list-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
      }

      .result-count {
        color: var(--muted);
        font-size: 0.78rem;
      }

      .filters {
        display: grid;
        gap: 9px;
      }

      .search-wrap {
        position: relative;
      }

      .search-wrap::before {
        content: "⌕";
        position: absolute;
        left: 12px;
        top: 7px;
        color: #9a8f9e;
        font-size: 20px;
        pointer-events: none;
      }

      .filter-control {
        width: 100%;
        border: 1px solid #dcd2df;
        border-radius: 7px;
        background: #fff;
        color: var(--ink);
        font: inherit;
        font-size: 0.86rem;
        padding: 11px 12px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
      }

      .search-wrap .filter-control {
        padding-left: 38px;
      }

      .filter-control:focus {
        border-color: #b990a8;
        box-shadow: 0 0 0 3px #ecdce7;
      }

      .filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
      }

      .button-reset {
        border: 0;
        background: transparent;
        color: var(--accent);
        font: inherit;
        font-size: 0.78rem;
        cursor: pointer;
        padding: 5px 0;
      }

      .button-reset:hover {
        text-decoration: underline;
      }

      .institution-list {
        display: flex;
        flex-direction: column;
        gap: 7px;
        flex: 1;
        overflow: auto;
        padding: 1px 4px 2px 0;
      }

      .institution-item {
        width: 100%;
        background: #fff;
        border: 1px solid var(--line);
        border-left: 4px solid var(--item-color, var(--accent));
        border-radius: 8px;
        text-align: left;
        padding: 10px 11px;
        cursor: pointer;
        transition: background-color 140ms ease, border-color 140ms ease;
        color: var(--ink);
      }

      .institution-item:hover {
        background: #fcfafd;
        border-color: #d7c6d6;
      }

      .institution-item.is-active {
        background: #f6eff6;
        border-color: #d7c6d6;
      }

      .institution-item strong {
        display: block;
        font-size: 0.9rem;
        margin-bottom: 4px;
      }

      .institution-item span {
        display: block;
        color: var(--muted);
        font-size: 0.75rem;
      }

      .type-pill {
        display: inline-block !important;
        width: fit-content;
        margin-top: 7px;
        border-radius: 99px;
        background: #f6eff6;
        padding: 3px 8px;
        color: var(--accent-dark) !important;
        font-size: 0.68rem !important;
      }

      .empty-state, .loading-state, .error-state {
        border: 1px dashed var(--line);
        border-radius: 8px;
        color: var(--muted);
        padding: 18px 12px;
        text-align: center;
        font-size: 0.85rem;
        line-height: 1.5;
      }

      .map-panel {
        position: relative;
        min-height: 620px;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: var(--shadow);
        border: 1px solid var(--line);
      }

      #map {
        width: 100%;
        height: 100%;
        min-height: 620px;
      }

      .legend {
        position: absolute;
        bottom: 20px;
        left: 16px;
        z-index: 400;
        background: rgba(255,255,255,0.96);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 10px 12px;
        box-shadow: 0 8px 24px rgb(52 35 58 / 10%);
        display: flex;
        flex-wrap: wrap;
        gap: 8px 12px;
      }

      .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.74rem;
        color: var(--text);
      }

      .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
      }

      .map-toolbar {
        position: absolute;
        z-index: 400;
        top: 14px;
        right: 60px;
        display: flex;
        gap: 8px;
      }

      .map-action {
        border: 1px solid var(--line);
        border-radius: 7px;
        background: rgba(255,255,255,.96);
        color: var(--accent-dark);
        padding: 9px 12px;
        font: inherit;
        font-size: .8rem;
        font-weight: 600;
        box-shadow: 0 5px 16px rgb(52 35 58 / 8%);
        cursor: pointer;
        transition: .18s ease;
      }

      .map-action:hover {
        background: #f6eff6;
        transform: translateY(-1px);
      }

      .leaflet-bar,
      .leaflet-control-layers {
        border: 1px solid var(--line) !important;
        border-radius: 7px !important;
        box-shadow: 0 4px 14px rgb(52 35 58 / 9%) !important;
      }

      .leaflet-bar a,
      .leaflet-control-layers {
        color: var(--accent-dark);
      }

      .leaflet-bar a:hover {
        background: #f6eff6;
        color: var(--accent-dark);
      }

      .leaflet-popup-content-wrapper,
      .leaflet-popup-tip {
        border-radius: 14px;
      }

      .leaflet-div-icon {
        background: transparent;
        border: none;
      }

      .custom-marker {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 3px 12px rgb(52 35 58 / 20%);
      }

      .custom-marker span {
        display: block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
      }

      :focus-visible {
        outline: 3px solid #b990a8;
        outline-offset: 3px;
      }

      @media (max-width: 900px) {
        .topbar {
          width: min(100% - 32px, 1440px);
          padding: 32px 0 20px;
        }
        .dashboard {
          width: min(100% - 32px, 1440px);
        }
        .dashboard {
          grid-template-columns: 1fr;
          min-height: 0;
        }

        .sidebar { min-height: 0; }
        .institution-list {
          max-height: 260px;
          min-height: 180px;
          flex: 1;
        }

        .map-panel, #map { min-height: 560px; }
      }

      @media (max-width: 640px) {
        .topbar {
          width: min(100% - 28px, 1440px);
          align-items: flex-start;
          flex-direction: column;
          padding: 28px 0 20px;
        }
        .dashboard {
          width: min(100% - 28px, 1440px);
          gap: 14px;
          padding-bottom: 28px;
        }
        .sidebar { padding: 14px; }
        .map-panel, #map { min-height: 480px; }
        .map-toolbar { top: 10px; right: 52px; }
        .map-action { padding: 8px 10px; font-size: .74rem; }
        .legend { left: 10px; bottom: 12px; max-width: calc(100% - 20px); }
      }
    </style>
  </head>
  <body>
    <header class="topbar">
      <div class="brand">
        <div>
          <p>WEB GIS &amp; DATA WILAYAH</p>
          <h1>Peta Lembaga Pendidikan</h1>
        </div>
      </div>
      <a class="topbar-tag" href="../PGWEB9/latihan/index.php">Kembali ke Data PGWEB9</a>
    </header>

    <main class="dashboard">
      <aside class="sidebar">
        <div class="stats">
          <div class="stat-card">
            <span class="label">Total</span>
            <span class="value" id="total-count">0</span>
          </div>
          <div class="stat-card">
            <span class="label">Kursus</span>
            <span class="value" id="kursus-count">0</span>
          </div>
          <div class="stat-card">
            <span class="label">PKBM</span>
            <span class="value" id="pkbm-count">0</span>
          </div>
          <div class="stat-card">
            <span class="label">Lainnya</span>
            <span class="value" id="other-count">0</span>
          </div>
        </div>

        <div class="filters">
          <div class="search-wrap">
            <input id="search-input" class="filter-control" type="search" placeholder="Cari nama atau kecamatan..." aria-label="Cari lembaga">
          </div>
          <div class="filter-row">
            <select id="type-filter" class="filter-control" aria-label="Filter jenjang">
              <option value="">Semua jenjang</option>
            </select>
            <select id="status-filter" class="filter-control" aria-label="Filter status">
              <option value="">Semua status</option>
            </select>
          </div>
          <div class="list-heading">
            <h2 class="side-title">Daftar lembaga</h2>
            <button type="button" class="button-reset" id="reset-filters">Reset filter</button>
          </div>
        </div>
        <div class="list-heading">
          <span class="result-count" id="result-count">Memuat data...</span>
        </div>
        <div id="institution-list" class="institution-list">
          <div class="loading-state">Mengambil data lembaga...</div>
        </div>
      </aside>

      <section class="map-panel">
        <div id="map"></div>
        <div class="map-toolbar">
          <button type="button" class="map-action" id="fit-map" title="Tampilkan semua hasil">↗ Tampilkan semua</button>
        </div>
        <div class="legend">
          <div class="legend-item"><span class="legend-dot" style="background:#79516d"></span> Kursus</div>
          <div class="legend-item"><span class="legend-dot" style="background:#c17d58"></span> PKBM</div>
          <div class="legend-item"><span class="legend-dot" style="background:#56846c"></span> Pesantren</div>
          <div class="legend-item"><span class="legend-dot" style="background:#a63d4d"></span> SKB</div>
        </div>
      </section>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
      const map = L.map('map').setView([-7.795, 110.37], 12);
      const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
      });
      const imageryMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri'
      });
      const baseMaps = { 'Peta jalan': streetMap, 'Citra satelit': imageryMap };
      const jenjangColors = {
        Kursus: '#79516d',
        PKBM: '#c17d58',
        'Pondok Pesantren': '#56846c',
        SKB: '#a63d4d'
      };
      const institutionList = document.getElementById('institution-list');
      const searchInput = document.getElementById('search-input');
      const typeFilter = document.getElementById('type-filter');
      const statusFilter = document.getElementById('status-filter');
      const resultCount = document.getElementById('result-count');
      const records = [];
      let emptyState;

      streetMap.addTo(map);
      L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);
      L.control.scale({ maxWidth: 150, position: 'bottomright' }).addTo(map);

      function createMarkerIcon(color) {
        return L.divIcon({
          className: 'custom-marker',
          html: `<span style="background:${color}"></span>`,
          iconSize: [24, 24],
          iconAnchor: [12, 12]
        });
      }

      function addPopupText(container, label, value) {
        const row = document.createElement('div');
        const strong = document.createElement('strong');
        strong.textContent = `${label}: `;
        row.append(strong, document.createTextNode(value || '-'));
        container.appendChild(row);
      }

      function createPopup(point) {
        const content = document.createElement('div');
        content.style.minWidth = '210px';
        const title = document.createElement('strong');
        title.textContent = point.nama;
        title.style.cssText = 'display:block;margin-bottom:7px;font-size:14px';
        content.appendChild(title);
        addPopupText(content, 'Jenjang', point.jenjang);
        addPopupText(content, 'Status', point.status);
        addPopupText(content, 'Wilayah', `${point.kecamatan}, ${point.kelurahan}`);
        addPopupText(content, 'Alamat', point.alamat);
        return content;
      }

      function populateFilters(points) {
        const values = {
          type: [...new Set(points.map(point => point.jenjang))].sort(),
          status: [...new Set(points.map(point => point.status))].sort()
        };

        values.type.forEach(value => {
          const option = document.createElement('option');
          option.value = value;
          option.textContent = value;
          typeFilter.appendChild(option);
        });

        values.status.forEach(value => {
          const option = document.createElement('option');
          option.value = value;
          option.textContent = value;
          statusFilter.appendChild(option);
        });
      }

      function updateStats(points) {
        const kursus = points.filter(point => point.jenjang === 'Kursus').length;
        const pkbm = points.filter(point => point.jenjang === 'PKBM').length;
        document.getElementById('total-count').textContent = points.length;
        document.getElementById('kursus-count').textContent = kursus;
        document.getElementById('pkbm-count').textContent = pkbm;
        document.getElementById('other-count').textContent = points.length - kursus - pkbm;
      }

      function createListItem(record) {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'institution-item';
        item.style.setProperty('--item-color', record.color);

        const name = document.createElement('strong');
        name.textContent = record.point.nama;
        const location = document.createElement('span');
        location.textContent = `${record.point.kecamatan} · ${record.point.kelurahan}`;
        const type = document.createElement('span');
        type.className = 'type-pill';
        type.textContent = `${record.point.jenjang} · ${record.point.status}`;
        item.append(name, location, type);

        item.addEventListener('click', () => {
          map.flyTo(record.marker.getLatLng(), 15, { duration: 0.8 });
          record.marker.openPopup();
          document.querySelectorAll('.institution-item.is-active').forEach(active => {
            active.classList.remove('is-active');
          });
          item.classList.add('is-active');
        });
        return item;
      }

      function applyFilters() {
        map.closePopup();
        const query = searchInput.value.trim().toLocaleLowerCase('id');
        const selectedType = typeFilter.value;
        const selectedStatus = statusFilter.value;
        const visiblePoints = [];

        records.forEach(record => {
          const point = record.point;
          const searchable = [
            point.nama, point.jenjang, point.status, point.kecamatan,
            point.kelurahan, point.alamat
          ].join(' ').toLocaleLowerCase('id');
          const matches = searchable.includes(query)
            && (!selectedType || point.jenjang === selectedType)
            && (!selectedStatus || point.status === selectedStatus);

          record.item.hidden = !matches;
          if (matches) {
            visiblePoints.push(point);
            if (!map.hasLayer(record.marker)) map.addLayer(record.marker);
          } else {
            if (record.marker.isPopupOpen()) record.marker.closePopup();
            if (map.hasLayer(record.marker)) map.removeLayer(record.marker);
            record.item.classList.remove('is-active');
          }
        });

        updateStats(visiblePoints);
        resultCount.textContent = `${visiblePoints.length} dari ${records.length} lembaga`;
        if (emptyState) emptyState.hidden = visiblePoints.length !== 0;
        return visiblePoints;
      }

      function fitVisiblePoints() {
        const visiblePoints = applyFilters();
        if (visiblePoints.length === 0) return;
        const bounds = L.latLngBounds(visiblePoints.map(point => [point.latitude, point.longitude]));
        map.fitBounds(bounds.pad(0.2), { maxZoom: 15 });
      }

      function renderMarkers(points) {
        populateFilters(points);
        institutionList.textContent = '';
        emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.textContent = 'Tidak ada lembaga yang cocok. Coba ubah kata kunci atau filter.';
        emptyState.hidden = true;
        institutionList.appendChild(emptyState);

        points.forEach(point => {
          const color = jenjangColors[point.jenjang] || '#82938b';
          const marker = L.marker([point.latitude, point.longitude], {
            icon: createMarkerIcon(color)
          }).bindPopup(createPopup(point));
          const record = { point, marker, color, item: null };
          record.item = createListItem(record);
          records.push(record);
          institutionList.insertBefore(record.item, emptyState);
        });

        const visible = applyFilters();
        if (visible.length) {
          map.fitBounds(L.latLngBounds(visible.map(point => [point.latitude, point.longitude])).pad(0.2), { maxZoom: 15 });
        }
      }

      searchInput.addEventListener('input', applyFilters);
      typeFilter.addEventListener('change', applyFilters);
      statusFilter.addEventListener('change', applyFilters);
      document.getElementById('reset-filters').addEventListener('click', () => {
        searchInput.value = '';
        typeFilter.value = '';
        statusFilter.value = '';
        fitVisiblePoints();
      });
      document.getElementById('fit-map').addEventListener('click', fitVisiblePoints);

      async function loadMarkers() {
        try {
          const response = await fetch('data.php');
          if (!response.ok) throw new Error(`Permintaan data gagal (${response.status})`);
          const points = await response.json();
          if (!Array.isArray(points)) throw new Error('Format data lembaga tidak valid');

          const validPoints = points.map(point => ({
            ...point,
            latitude: Number(point.latitude),
            longitude: Number(point.longitude)
          })).filter(point =>
            Number.isFinite(point.latitude)
            && Number.isFinite(point.longitude)
            && point.latitude >= -90 && point.latitude <= 90
            && point.longitude >= -180 && point.longitude <= 180
          );

          if (!validPoints.length) throw new Error('Belum ada data koordinat lembaga yang valid');
          renderMarkers(validPoints);
        } catch (error) {
          console.error(error);
          institutionList.textContent = '';
          const message = document.createElement('div');
          message.className = 'error-state';
          message.textContent = 'Data gagal dimuat. Pastikan Apache dan MySQL aktif, lalu muat ulang halaman.';
          institutionList.appendChild(message);
          resultCount.textContent = 'Data tidak tersedia';
        }
      }

      loadMarkers();
    </script>
  </body>
</html>
