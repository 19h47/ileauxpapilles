/* global delileauxpapilles */
import { AbstractBlock } from 'starting-blocks';
import * as L from 'leaflet';

// Retrieve latitude, longitude, popupContent and template_directory_uri from wp_localize_script
const {
	coordinates: { latitude, longitude },
	popupContent,
	template_directory_uri: templateDirectoryUri,
} = delileauxpapilles;

export default class MapBlock extends AbstractBlock {
	constructor(container) {
		super(container, 'MapBlock');

		this.map = null;
		this.tileLayer = null;
		this.marker = null;
	}

	init() {
		super.init();

		this.openPopup = JSON.parse(this.rootElement.getAttribute('data-popup-open')) || false;

		const customIcon = L.icon({
			iconUrl: `${templateDirectoryUri}/dist/img/svg/marker.svg`,
			iconSize: [32, 41],
			iconAnchor: [16, 41],
			popupAnchor: [0, -30],
		});

		const marker = L.marker([latitude, longitude], { icon: customIcon });

		const tileLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
			maxZoom: 20,
			subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
		});

		this.map = this.initMap();
		this.tileLayer = tileLayer;
		this.marker = marker;

		this.tileLayer.addTo(this.map);
		this.marker.addTo(this.map);

		this.marker.bindPopup(popupContent);

		if (this.openPopup) {
			this.marker.openPopup();
		}
	}

	initMap() {
		return L.map(this.rootElement, {
			center: [latitude, longitude],
			zoom: 15,
			zoomControl: true,
			scrollWheelZoom: false,
		});
	}
}
