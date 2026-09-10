import Chart from 'chart.js/auto';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerIconRetina from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

// Gráficos usados pelos componentes Mary UI.
Chart.defaults.color = '#94a3b8';
Chart.defaults.borderColor = 'rgba(148, 163, 184, 0.16)';

window.Chart = Chart;

L.Icon.Default.mergeOptions({
    iconUrl: markerIcon,
    iconRetinaUrl: markerIconRetina,
    shadowUrl: markerShadow,
});

// Configuração compartilhada pelos mapas do painel.
const addMapTiles = (map) => {
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);
};

const validPosition = (latitude, longitude) => {
    const lat = Number.parseFloat(latitude);
    const lng = Number.parseFloat(longitude);

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
        return null;
    }

    if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
        return null;
    }

    return [lat, lng];
};

document.addEventListener('alpine:init', () => {
    // Mapa editável usado no cadastro e na edição.
    Alpine.data('locationPicker', (settings) => ({
        latitude: settings.latitude,
        longitude: settings.longitude,
        map: null,
        marker: null,

        init() {
            this.$nextTick(() => {
                const currentPosition = validPosition(this.latitude, this.longitude);
                const center = currentPosition ?? [settings.defaultLatitude, settings.defaultLongitude];

                this.map = L.map(this.$refs.map, { scrollWheelZoom: false }).setView(
                    center,
                    currentPosition ? 15 : settings.defaultZoom,
                );
                addMapTiles(this.map);

                if (currentPosition) {
                    this.placeMarker(currentPosition[0], currentPosition[1], false);
                }

                this.map.on('click', ({ latlng }) => this.placeMarker(latlng.lat, latlng.lng));
                this.$watch('latitude', () => this.syncMarkerFromInputs());
                this.$watch('longitude', () => this.syncMarkerFromInputs());

                window.setTimeout(() => this.map?.invalidateSize(), 100);
            });
        },

        placeMarker(latitude, longitude, updateCoordinates = true) {
            const position = [latitude, longitude];

            if (this.marker) {
                this.marker.setLatLng(position);
            } else {
                this.marker = L.marker(position, { draggable: true }).addTo(this.map);
                this.marker.on('dragend', ({ target }) => {
                    const markerPosition = target.getLatLng();
                    this.placeMarker(markerPosition.lat, markerPosition.lng);
                });
            }

            if (updateCoordinates) {
                this.latitude = latitude.toFixed(7);
                this.longitude = longitude.toFixed(7);
            }
        },

        syncMarkerFromInputs() {
            const position = validPosition(this.latitude, this.longitude);

            if (position) {
                this.placeMarker(position[0], position[1], false);
            }
        },

        clearPosition() {
            this.latitude = null;
            this.longitude = null;

            if (this.marker) {
                this.map.removeLayer(this.marker);
                this.marker = null;
            }
        },

        hasPosition() {
            return validPosition(this.latitude, this.longitude) !== null;
        },

        destroy() {
            this.map?.remove();
        },
    }));

    // Mapa de consulta com os relatos já cadastrados.
    Alpine.data('reportsMap', (settings) => ({
        map: null,

        init() {
            this.$nextTick(() => {
                this.map = L.map(this.$refs.map, { scrollWheelZoom: false }).setView(
                    [settings.defaultLatitude, settings.defaultLongitude],
                    settings.defaultZoom,
                );
                addMapTiles(this.map);

                const bounds = [];
                const statusColors = {
                    received: '#0ea5e9',
                    triage: '#f59e0b',
                    published: '#10b981',
                    restricted: '#64748b',
                    rejected: '#ef4444',
                };

                settings.reports.forEach((report) => {
                    const position = validPosition(report.latitude, report.longitude);

                    if (!position) {
                        return;
                    }

                    L.circleMarker(position, {
                        radius: 8,
                        color: '#ffffff',
                        weight: 2,
                        fillColor: statusColors[report.status.value] ?? '#059669',
                        fillOpacity: 0.95,
                    })
                        .addTo(this.map)
                        .bindPopup(this.popupContent(report));

                    bounds.push(position);
                });

                if (bounds.length === 1) {
                    this.map.setView(bounds[0], 15);
                } else if (bounds.length > 1) {
                    this.map.fitBounds(bounds, { padding: [32, 32], maxZoom: 15 });
                }

                window.setTimeout(() => this.map?.invalidateSize(), 100);
            });
        },

        popupContent(report) {
            const content = document.createElement('div');
            const address = document.createElement('strong');
            const status = document.createElement('span');

            address.textContent = report.address;
            status.textContent = report.status.label;
            content.append(address, document.createElement('br'), status);

            if (report.url) {
                const link = document.createElement('a');
                link.href = report.url;
                link.textContent = 'Abrir relato';
                link.className = 'mt-2 block font-semibold text-emerald-700';
                content.append(link);
            }

            return content;
        },

        destroy() {
            this.map?.remove();
        },
    }));

    // Mapa operacional para cadastrar e consultar gaiolas de coleta.
    Alpine.data('collectionPointsMap', (settings) => ({
        latitude: settings.latitude,
        longitude: settings.longitude,
        map: null,
        candidate: null,

        init() {
            this.$nextTick(() => {
                this.map = L.map(this.$refs.map, { scrollWheelZoom: false }).setView(
                    [settings.defaultLatitude, settings.defaultLongitude],
                    settings.defaultZoom,
                );
                addMapTiles(this.map);

                const bounds = [];

                settings.points.forEach((point) => {
                    const position = validPosition(point.latitude, point.longitude);

                    if (!position) {
                        return;
                    }

                    L.circleMarker(position, {
                        radius: 8,
                        color: '#ffffff',
                        weight: 2,
                        fillColor: point.status === 'active' ? '#2563eb' : '#64748b',
                        fillOpacity: 0.95,
                    }).addTo(this.map).bindTooltip(point.name);
                    bounds.push(position);
                });

                if (bounds.length > 0) {
                    this.map.fitBounds(bounds, { padding: [28, 28], maxZoom: 14 });
                }

                const current = validPosition(this.latitude, this.longitude);
                if (current) {
                    this.placeCandidate(current[0], current[1], false);
                }

                this.map.on('click', ({ latlng }) => this.placeCandidate(latlng.lat, latlng.lng));
                this.$watch('latitude', () => this.syncCandidate());
                this.$watch('longitude', () => this.syncCandidate());
                window.setTimeout(() => this.map?.invalidateSize(), 100);
            });
        },

        placeCandidate(latitude, longitude, updateCoordinates = true) {
            if (this.candidate) {
                this.candidate.setLatLng([latitude, longitude]);
            } else {
                this.candidate = L.marker([latitude, longitude], { draggable: true }).addTo(this.map);
                this.candidate.on('dragend', ({ target }) => {
                    const position = target.getLatLng();
                    this.placeCandidate(position.lat, position.lng);
                });
            }

            if (updateCoordinates) {
                this.latitude = latitude.toFixed(7);
                this.longitude = longitude.toFixed(7);
            }
        },

        syncCandidate() {
            const position = validPosition(this.latitude, this.longitude);
            if (position) {
                this.placeCandidate(position[0], position[1], false);
            }
        },

        destroy() {
            this.map?.remove();
        },
    }));
});

if ('serviceWorker' in navigator && import.meta.env.PROD) {
    window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
}
