/**
 * Application JavaScript - Les clés du Capcir
 */

// ============================================================
// SECTION : PAGE D'ACCUEIL
// ============================================================

// ---------- Chargement des biens ----------

/**
 * Charge les biens de l'index (ventes et locations)
 */
function loadIndexBiens() {
	fetch('api/biens.php?action=list')
		.then(response => response.json())
		.then(data => {
			if (!data.success) {
				console.error('Erreur lors du chargement des biens');
				return;
			}

			const biens = data.data;

			// Filtrer et limiter les biens actifs
			const ventes = biens
				.filter(b => b.statut === 'vente' && b.actif == 1)
				.slice(0, 3);

			const locations = biens
				.filter(b => b.statut === 'location' && b.actif == 1)
				.slice(0, 3);

			// Afficher les ventes
			displayIndexBiens('ventes-list', ventes, 'vente');

			// Afficher les locations
			displayIndexBiens('locations-list', locations, 'location');
		})
		.catch(error => {
			console.error('Erreur:', error);
		});
}

/**
 * Affiche les biens dans une section de l'index
 */
function displayIndexBiens(containerId, biens, type) {
	const container = document.getElementById(containerId);
	if (!container) return;

	if (biens.length === 0) {
		container.innerHTML = `
			<div class="col-12">
				<div class="alert alert-info mb-0">
					Aucun bien en ${type} pour le moment.
				</div>
			</div>
		`;
		return;
	}

	container.innerHTML = biens.map(bien => `
		<div class="col-12">
			${createBienCard(bien, 'public')}
		</div>
	`).join('');
}

// ---------- Image hero ----------

function setHeroImage() {
	const hero = document.querySelector(".hero");
	if (hero) {
		hero.style.setProperty("--hero-image", `url('${HERO_IMAGE}')`);
	}
}

if (typeof HERO_IMAGE !== 'undefined') {
	setHeroImage();
}

// ---------- Menu sticky ----------

function handleStickyNav(){
	const sticky = document.getElementById("stickyNav");
	if (!sticky) return;

	const y = window.scrollY || document.documentElement.scrollTop;
	if (y > 120){
		sticky.classList.add("show");
		document.body.classList.add("is-sticky");
	} else {
		sticky.classList.remove("show");
		document.body.classList.remove("is-sticky");
	}
}

if (document.getElementById("stickyNav")) {
	handleStickyNav();
	window.addEventListener("scroll", handleStickyNav);
}

// ---------- Footer ----------

const yearElement = document.getElementById("year");
if (yearElement) {
	yearElement.textContent = new Date().getFullYear();
}

// ============================================================
// SECTION : FORMULAIRE DE CONTACT
// ============================================================

function handleContactForm(e) {
	e.preventDefault();

	const form = document.getElementById('contactForm');
	const submitBtn = document.getElementById('submitBtn');
	const btnText = document.getElementById('btnText');
	const btnSpinner = document.getElementById('btnSpinner');
	const statusDiv = document.getElementById('formStatus');

	// Désactiver le bouton pendant l'envoi
	submitBtn.disabled = true;
	btnSpinner.classList.remove('d-none');
	btnText.textContent = 'Envoi en cours...';
	statusDiv.textContent = '';
	statusDiv.className = 'mt-2';

	// Récupérer les données du formulaire
	const formData = new FormData(form);

	// Envoyer les données en AJAX
	fetch('api/contact.php', {
		method: 'POST',
		body: formData
	})
		.then(response => response.json())
		.then(data => {
			if (!data.success) {
				statusDiv.className = 'mt-2 text-danger fw-bold';
				statusDiv.textContent = data.message || 'Une erreur est survenue.';
				return;
			}

			statusDiv.className = 'mt-2 text-success fw-bold';
			statusDiv.textContent = data.message;
			form.reset();
		})
		.catch(error => {
			statusDiv.className = 'mt-2 text-danger fw-bold';
			statusDiv.textContent = 'Erreur de connexion. Veuillez réessayer.';
			console.error('Erreur:', error);
		})
		.finally(() => {
			submitBtn.disabled = false;
			btnSpinner.classList.add('d-none');
			btnText.textContent = 'Envoyer';

			setTimeout(() => {
				statusDiv.textContent = '';
				statusDiv.className = 'mt-2';
			}, 5000);
		});
}

// ============================================================
// SECTION : ADMINISTRATION DES BIENS
// ============================================================

let currentBienId = null;
let allBiens = []; // Stocke tous les biens
let currentFilter = 'all'; // Filtre actuel
let currentEditImages = []; // Images en cours d'édition (pour le réordonnancement)
let currentEditForm = null;  // Formulaire d'édition actif

// WeakMap pour stocker les fichiers sélectionnés par formulaire
const selectedFilesMap = new WeakMap();

/**
 * Ouvre le modal de confirmation de synchronisation Smoobu
 */
function syncSmoobu() {
	const modal = new bootstrap.Modal(document.getElementById('modalSyncSmoobu'));
	modal.show();
}

/**
 * Exécute la synchronisation avec Smoobu
 */
function executeSyncSmoobu() {
	const modal = bootstrap.Modal.getInstance(document.getElementById('modalSyncSmoobu'));
	const btn = document.getElementById('btnSyncSmoobu');
	const confirmBtn = document.getElementById('btnConfirmSync');

	if (!btn || !confirmBtn) return;

	// Fermer le modal
	modal.hide();

	// Désactiver les boutons pendant la synchro
	const originalHtml = btn.innerHTML;
	const originalConfirmHtml = confirmBtn.innerHTML;
	btn.disabled = true;
	confirmBtn.disabled = true;
	btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Synchronisation...';
	confirmBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> En cours...';

	const formData = new FormData();
	formData.append('action', 'synchronize');

	fetch('api/smoobu.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (!data.success) {
			showAlert(data.message || 'Erreur lors de la synchronisation', 'danger');
			if (data.error) {
				console.error('Erreur de synchronisation:', data.error);
			}
			return;
		}

		const stats = data.stats;

		let message =
			'Synchronisation terminée : '
			+ stats['added']+' ajouté'+(stats['added']>1?'s':'')+' ; '
			+ stats['updated']+' mis à jour ; '
			+ stats['skipped']+' ignoré'+(stats['skipped']>1?'s':'')+'.<br>'
		;
		if (stats.errors && stats.errors.length > 0) {
			message += '<ul class="mb-0 mt-2">' + stats.errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
		}
		showAlert(message, 'success');
		loadBiens();
	})
	.catch(error => {
		console.error('Erreur:', error);
		showAlert('Erreur de connexion lors de la synchronisation', 'danger');
	})
	.finally(() => {
		btn.disabled = false;
		confirmBtn.disabled = false;
		btn.innerHTML = originalHtml;
		confirmBtn.innerHTML = originalConfirmHtml;
	});
}

/**
 * Génère le HTML d'une carte de bien
 * @param {object} bien - Les données du bien
 * @param {string} context - 'admin' ou 'public'
 */
function createBienCard(bien, context = 'public') {
	// Boutons selon le contexte
	let buttonsHtml;
	if (context === 'admin') {
		buttonsHtml = `
			<button class="btn btn-sm btn-info ms-2" onclick="showDetailModal(${bien.id})">
				<i class="fa-solid fa-eye"></i>
			</button>
			<button class="btn btn-sm btn-warning ms-2" onclick="editBien(${bien.id})">
				<i class="fa-solid fa-pen"></i>
			</button>
			<button class="btn btn-sm btn-danger ms-2" onclick="showDeleteModal(${bien.id}, '${escapeHtml(bien.titre)}')">
				<i class="fa-solid fa-trash"></i>
			</button>
		`;
	} else {
		const actionButton = bien.statut === 'location'
			? `<button type="button" class="btn btn-sapin btn-sm" onclick="openBookingModal(${bien.id_smoobu || 0}, '${escapeHtml(bien.titre)}')">Réserver</button>`
			: `<a class="btn btn-sapin btn-sm" href="#contact">Infos / visite</a>`;

		buttonsHtml = `
			<button type="button" class="btn btn-outline-sapin btn-sm" onclick="showDetailModal(${bien.id})">Détails</button>
			${actionButton}
		`;
	}

	return `
		<div class="listing ${bien.actif == 0 ? 'bien-inactif' : ''}">
			<div class="listing-image-container">
				${bien.images && bien.images.length > 0 ? `
					<img src="${bien.images[0].url}" alt="${escapeHtml(bien.titre)}">
				` : `
					<div class="listing-image-placeholder bg-secondary d-flex align-items-center justify-content-center text-white">
						<i class="fa-solid fa-image fa-3x opacity-50"></i>
					</div>
				`}
				<div class="listing-image-overlay">
					<h5 class="listing-image-title">${escapeHtml(bien.titre)}</h5>
				</div>
			</div>
			<div class="body">
				<div class="mb-2 fw-bold small">
					${[
						bien.surface ? `${bien.surface} m²` : null,
						bien.nb_chambres ? `${bien.nb_chambres} ch.` : null,
						bien.statut === 'location' && bien.nb_personnes ? `${bien.nb_personnes} pers.` : null
					].filter(Boolean).join(' • ') || '&nbsp;'}
				</div>
				<div class="text-muted small mb-3">
					${bien.city ? `<i class="fa-solid fa-location-dot"></i> ${escapeHtml(bien.city)}` : ''}
				</div>
				${bien.prix ? `
					<div class="prix prix-right text-nowrap">
						${formatPrice(bien.prix)}
					</div>
				` : ''}
				${bien.description ? `
					<p class="small text-muted mb-3">
						${escapeHtml(bien.description.substring(0, 80))}${bien.description.length > 80 ? '...' : ''}
					</p>
				` : ''}
				<div class="d-flex gap-2 ${context === 'admin' ? 'justify-content-end' : ''}">
					${buttonsHtml}
				</div>
			</div>
		</div>
	`;
}

/**
 * Charge tous les biens
 */
function loadBiens() {
	fetch('api/biens.php?action=list')
		.then(response => response.json())
		.then(data => {
			if (!data.success) {
				showAlert('Erreur lors du chargement des biens', 'danger');
				return;
			}

			allBiens = data.data;
			applyFilter();
			updateStats(data.data);
		})
		.catch(error => {
			console.error('Erreur:', error);
			showAlert('Erreur de connexion', 'danger');
		});
}

/**
 * Applique le filtre actuel sur les biens
 */
function applyFilter() {
	let filteredBiens = allBiens;

	if (currentFilter === 'vente') {
		filteredBiens = allBiens.filter(b => b.statut === 'vente');
	}
	else if (currentFilter === 'location') {
		filteredBiens = allBiens.filter(b => b.statut === 'location');
	}

	displayBiens(filteredBiens);
}

/**
 * Affiche les biens dans la liste (admin)
 */
function displayBiens(biens) {
	const container = document.getElementById('biens-list');
	if (!container) return;

	if (biens.length === 0) {
		container.innerHTML = `
			<div class="col-12">
				<div class="alert alert-info">
					<i class="fa-solid fa-info-circle"></i> Aucun bien n'a été ajouté pour le moment.
				</div>
			</div>
		`;
		return;
	}

	container.innerHTML = biens.map(bien => `
		<div class="col-md-6 col-lg-4">
			${createBienCard(bien, 'admin')}
		</div>
	`).join('');
}

/**
 * Met à jour les statistiques
 */
function updateStats(biens) {
	const total = biens.length;
	const ventes = biens.filter(b => b.statut === 'vente' && b.actif == 1).length;
	const locations = biens.filter(b => b.statut === 'location' && b.actif == 1).length;

	const statTotal = document.getElementById('stat-total');
	const statVentes = document.getElementById('stat-ventes');
	const statLocations = document.getElementById('stat-locations');

	if (statTotal) statTotal.textContent = total;
	if (statVentes) statVentes.textContent = ventes;
	if (statLocations) statLocations.textContent = locations;
}

/**
 * Charge les données depuis Smoobu et remplit le formulaire
 */
function loadSmoobuData(form) {
	const idSmoobuInput = form.querySelector('[name="id_smoobu"]');
	const idSmoobu = idSmoobuInput.value.trim();

	if (!idSmoobu) {
		showAlert('Veuillez entrer un ID Smoobu', 'warning', form);
		idSmoobuInput.focus();
		return;
	}

	const btn = form.querySelector('.btn-load-smoobu');
	const originalHtml = btn.innerHTML;
	btn.disabled = true;
	btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Chargement...';

	fetch(`api/smoobu.php?action=get&id=${idSmoobu}`)
		.then(response => response.json())
		.then(data => {
			if (!data.success) {
				showAlert(data.error || 'Erreur lors du chargement des données Smoobu', 'danger', form);
				return;
			}

			// Remplir les champs du formulaire
			const apt = data.data;

			if (apt.name) form.querySelector('[name="titre"]').value = apt.name;
			if (apt.description) form.querySelector('[name="description"]').value = apt.description;
			if (apt.location?.city) form.querySelector('[name="city"]').value = apt.location.city;
			if (apt.size) form.querySelector('[name="surface"]').value = apt.size;
			if (apt.rooms?.bedrooms) form.querySelector('[name="nb_chambres"]').value = apt.rooms.bedrooms;
			if (apt.rooms?.maxOccupancy) form.querySelector('[name="nb_personnes"]').value = apt.rooms.maxOccupancy;
			if (apt.price?.minimal) form.querySelector('[name="prix"]').value = apt.price.minimal;

			showAlert('Données Smoobu chargées avec succès !', 'success', form);
		})
		.catch(error => {
			showAlert('Erreur lors de la communication avec l\'API Smoobu', 'danger', form);
		})
		.finally(() => {
			btn.disabled = false;
			btn.innerHTML = originalHtml;
		});
}

/**
 * Initialise un formulaire de bien (ajout ou édition)
 */
function initBienForm(form, isAddForm = false) {
	if (!form) return;

	// Gestion du changement de statut
	const statutRadios = form.querySelectorAll('[name="statut"]');
	statutRadios.forEach(radio => {
		radio.addEventListener('change', function() {
			toggleFieldsByStatut(form, this.value);
		});
	});

	// Pour le formulaire d'ajout, sélectionner "vente" par défaut
	if (isAddForm) {
		const venteRadio = form.querySelector('[name="statut"][value="vente"]');
		if (venteRadio) {
			venteRadio.checked = true;
			toggleFieldsByStatut(form, 'vente');
		}
		// Cacher la section des photos actuelles pour le formulaire d'ajout
		const currentImages = form.querySelector('.current-images');
		if (currentImages) {
			currentImages.classList.add('d-none');
		}
		// Cacher le champ statut actif/inactif pour le formulaire d'ajout
		const fieldActif = form.querySelector('.field-actif');
		if (fieldActif) {
			fieldActif.classList.add('d-none');
		}
	}

	// Initialiser l'upload d'images
	initImageUpload(form);

	// Initialiser le bouton de chargement Smoobu
	const btnLoadSmoobu = form.querySelector('.btn-load-smoobu');
	if (btnLoadSmoobu) {
		btnLoadSmoobu.addEventListener('click', function() {
			loadSmoobuData(form);
		});
	}

	// Gestion de la soumission
	form.addEventListener('submit', function(e) {
		e.preventDefault();
		if (isAddForm) {
			submitAddForm();
		} else {
			submitEditForm();
		}
	});
}

/**
 * Initialise les formulaires d'administration
 */
function initForms() {
	// Initialiser le formulaire d'ajout
	const formAdd = document.getElementById('formAdd');
	initBienForm(formAdd, true);

	// Initialiser le formulaire d'édition
	const formEdit = document.getElementById('formEdit');
	initBienForm(formEdit, false);

	// Confirmation de suppression
	const btnConfirmDelete = document.getElementById('btnConfirmDelete');
	if (btnConfirmDelete) {
		btnConfirmDelete.addEventListener('click', function() {
			deleteBien(currentBienId);
		});
	}
}

/**
 * Initialise les filtres
 */
function initFilters() {
	const filterButtons = document.querySelectorAll('[data-filter]');
	filterButtons.forEach(btn => {
		btn.addEventListener('click', function() {
			// Retirer la classe active de tous les boutons
			filterButtons.forEach(b => b.classList.remove('active'));

			// Ajouter la classe active au bouton cliqué
			this.classList.add('active');

			// Appliquer le filtre
			currentFilter = this.getAttribute('data-filter');
			applyFilter();
		});
	});
}

/**
 * Initialise l'upload d'images avec drag & drop
 */
function initImageUpload(form) {
	if (!form) return;

	// Initialiser le tableau de fichiers pour ce formulaire
	selectedFilesMap.set(form, []);

	const dropzone = form.querySelector('.image-dropzone');
	const fileInput = form.querySelector('input[type="file"][name="images[]"]');
	const previewContainer = form.querySelector('.image-preview-container');

	if (!dropzone || !fileInput || !previewContainer) return;

	// Click sur la dropzone pour ouvrir le sélecteur de fichiers
	dropzone.addEventListener('click', (e) => {
		if (e.target.tagName !== 'INPUT') {
			fileInput.click();
		}
	});

	// Changement de fichier via le bouton parcourir
	fileInput.addEventListener('change', (e) => {
		handleFiles(e.target.files, form);
	});

	// Drag & drop events
	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		dropzone.addEventListener(eventName, preventDefaults, false);
	});

	function preventDefaults(e) {
		e.preventDefault();
		e.stopPropagation();
	}

	['dragenter', 'dragover'].forEach(eventName => {
		dropzone.addEventListener(eventName, () => {
			dropzone.classList.add('dragover');
		}, false);
	});

	['dragleave', 'drop'].forEach(eventName => {
		dropzone.addEventListener(eventName, () => {
			dropzone.classList.remove('dragover');
		}, false);
	});

	dropzone.addEventListener('drop', (e) => {
		const dt = e.dataTransfer;
		const files = dt.files;
		handleFiles(files, form);
	}, false);
}

/**
 * Gère les fichiers sélectionnés
 */
function handleFiles(files, form) {
	const filesArray = Array.from(files);

	// Filtrer les fichiers image
	const imageFiles = filesArray.filter(file => file.type.startsWith('image/'));

	// Vérifier la taille (max 5MB)
	const validFiles = imageFiles.filter(file => {
		if (file.size > 5 * 1024 * 1024) {
			showAlert(`L'image ${file.name} dépasse 5MB`, 'warning', form);
			return false;
		}
		return true;
	});

	// Ajouter les fichiers à la liste
	const currentFiles = selectedFilesMap.get(form) || [];
	const newFiles = [...currentFiles, ...validFiles];

	// Limiter au nombre maximum d'images
	const maxImages = typeof MAX_IMAGES_UPLOAD !== 'undefined' ? MAX_IMAGES_UPLOAD : 10;
	if (newFiles.length > maxImages) {
		showAlert(`Vous ne pouvez pas ajouter plus de ${maxImages} images. ${newFiles.length - maxImages} image(s) ignorée(s).`, 'warning', form);
		selectedFilesMap.set(form, newFiles.slice(0, maxImages));
	} else {
		selectedFilesMap.set(form, newFiles);
	}

	// Afficher l'aperçu
	displayImagePreviews(form);
}

/**
 * Affiche l'aperçu des images
 */
function displayImagePreviews(form) {
	const previewContainer = form.querySelector('.image-preview-container');
	if (!previewContainer) return;

	previewContainer.innerHTML = '';

	const files = selectedFilesMap.get(form) || [];
	files.forEach((file, index) => {
		const reader = new FileReader();
		reader.onload = (e) => {
			const previewItem = document.createElement('div');
			previewItem.className = 'preview-item';
			previewItem.innerHTML = `
				<img src="${e.target.result}" alt="Preview">
				<button type="button" class="remove-preview" data-index="${index}">
					<i class="fa-solid fa-times"></i>
				</button>
			`;

			// Ajouter l'événement de suppression
			const removeBtn = previewItem.querySelector('.remove-preview');
			removeBtn.addEventListener('click', () => {
				removeImagePreview(form, index);
			});

			previewContainer.appendChild(previewItem);
		};
		reader.readAsDataURL(file);
	});
}

/**
 * Supprime une image de l'aperçu
 */
function removeImagePreview(form, index) {
	const files = selectedFilesMap.get(form) || [];
	files.splice(index, 1);
	selectedFilesMap.set(form, files);
	displayImagePreviews(form);
}

/**
 * Affiche/masque les champs selon le statut
 */
function toggleFieldsByStatut(form, statut) {
	const venteFields = form.querySelector('.vente-fields');
	const locationFields = form.querySelector('.location-fields');

	if (!venteFields || !locationFields) return;

	venteFields.classList.add('hide');
	locationFields.classList.add('hide');

	// Gérer l'affichage du champ Smoobu
	const smoobuContainers = form.querySelectorAll('.smoobu-field-container');
	smoobuContainers.forEach(container => {
		if (statut === 'location') {
			container.classList.remove('hide');
		} else {
			container.classList.add('hide');
		}
	});

	if (statut === 'vente') {
		venteFields.classList.remove('hide');
	} else if (statut === 'location') {
		locationFields.classList.remove('hide');
	}
}

/**
 * Soumet le formulaire d'ajout
 */
function submitAddForm() {
	const form = document.getElementById('formAdd');
	const formData = new FormData(form);
	formData.append('action', 'create');

	// Supprimer l'input file vide et ajouter les fichiers sélectionnés
	formData.delete('images[]');
	const files = selectedFilesMap.get(form) || [];
	if (files.length > 0) {
		files.forEach(file => {
			formData.append('images[]', file, file.name);
		});
	}

	fetch('api/biens.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (!data.success) {
			showAlert(data.message || 'Erreur lors de l\'ajout', 'danger', form);
			return;

		}

		showAlert('Bien ajouté avec succès !', 'success');
		bootstrap.Modal.getInstance(document.getElementById('modalAdd')).hide();
		form.reset();
		selectedFilesMap.set(form, []);
		const previewContainer = form.querySelector('.image-preview-container');
		if (previewContainer) previewContainer.innerHTML = '';
		loadBiens();
	})
	.catch(error => {
		console.error('Erreur:', error);
		showAlert('Erreur de connexion', 'danger', form);
	});
}

/**
 * Ouvre le modal d'édition
 */
function editBien(id) {
	fetch(`api/biens.php?action=get&id=${id}`)
		.then(response => response.json())
		.then(data => {
			if (!data.success) {
				showAlert('Bien non trouvé', 'danger');
				return;
			}

			fillEditForm(data.data);
			new bootstrap.Modal(document.getElementById('modalEdit')).show();
		})
		.catch(error => {
			console.error('Erreur:', error);
			showAlert('Erreur de connexion', 'danger');
		});
}

/**
 * Remplit le formulaire d'édition
 */
function fillEditForm(bien) {
	const form = document.getElementById('formEdit');

	form.querySelector('[name="id"]').value = bien.id;

	// Cocher le bon radio button pour le statut
	const statutRadios = form.querySelectorAll('[name="statut"]');
	statutRadios.forEach(radio => {
		radio.checked = (radio.value === bien.statut);
	});

	form.querySelector('[name="titre"]').value = bien.titre;
	form.querySelector('[name="city"]').value = bien.city || '';
	form.querySelector('[name="surface"]').value = bien.surface || '';
	form.querySelector('[name="description"]').value = bien.description || '';
	form.querySelector('[name="prix"]').value = bien.prix || '';

	// Nombre de chambres (peut être dans vente ou location)
	const nbChambresInputs = form.querySelectorAll('[name="nb_chambres"]');
	nbChambresInputs.forEach(input => input.value = bien.nb_chambres || '');

	form.querySelector('[name="nb_personnes"]').value = bien.nb_personnes || '';
	form.querySelector('[name="ordre"]').value = bien.ordre || 0;
	form.querySelector('[name="actif"]').value = bien.actif;

	// ID Smoobu
	const idSmoobuInput = form.querySelector('[name="id_smoobu"]');
	if (idSmoobuInput) {
		idSmoobuInput.value = bien.id_smoobu || '';
	}

	// Afficher les champs selon le statut
	toggleFieldsByStatut(form, bien.statut);

	// Réinitialiser les fichiers sélectionnés
	selectedFilesMap.set(form, []);
	const previewContainer = form.querySelector('.image-preview-container');
	if (previewContainer) previewContainer.innerHTML = '';

	// Afficher les images actuelles
	currentEditForm = form;
	displayCurrentImages(form, bien.id, bien.images || []);
}

/**
 * Affiche les images actuelles dans le formulaire d'édition
 */
function displayCurrentImages(form, bienId, images) {
	currentEditImages = [...images];
	const container = form.querySelector('.current-images .images-list');
	if (!container) {
		return;
	}

	if (images.length === 0) {
		container.innerHTML = '<p class="text-muted">Aucune image</p>';
		return;
	}

	container.innerHTML = images.map((img, index) => `
		<div class="image-preview">
			<img src="${img.url}" alt="Image">
			<div class="image-order-controls">
				<button type="button" class="btn btn-sm btn-secondary btn-order-up"
						onclick="moveImage(${bienId}, ${index}, -1)"
						${index === 0 ? 'disabled' : ''}>
					<i class="fa-solid fa-arrow-up"></i>
				</button>
				<button type="button" class="btn btn-sm btn-secondary btn-order-down"
						onclick="moveImage(${bienId}, ${index}, 1)"
						${index === images.length - 1 ? 'disabled' : ''}>
					<i class="fa-solid fa-arrow-down"></i>
				</button>
			</div>
			<button type="button" class="btn btn-danger btn-sm btn-remove"
					onclick="deleteImage(${bienId}, '${img.filename}')">
				<i class="fa-solid fa-times"></i>
			</button>
		</div>
	`).join('');
}

/**
 * Déplace une image vers le haut ou vers le bas
 */
function moveImage(bienId, index, direction) {
	const newIndex = index + direction;
	if (newIndex < 0 || newIndex >= currentEditImages.length) return;

	[currentEditImages[index], currentEditImages[newIndex]] = [currentEditImages[newIndex], currentEditImages[index]];

	displayCurrentImages(currentEditForm, bienId, currentEditImages);

	const filenames = currentEditImages.map(img => img.filename);
	saveImageOrder(bienId, filenames);
}

/**
 * Enregistre le nouvel ordre des images via l'API
 */
function saveImageOrder(bienId, filenames) {
	const formData = new FormData();
	formData.append('action', 'reorder-images');
	formData.append('bien_id', bienId);
	filenames.forEach(f => formData.append('filenames[]', f));

	fetch('api/biens.php', { method: 'POST', body: formData })
		.then(r => r.json())
		.catch(err => console.error('Erreur réordonnancement:', err));
}

/**
 * Supprime une image
 */
function deleteImage(bienId, filename) {
	if (!confirm('Supprimer cette image ?')) {
		return;
	}

	const formData = new FormData();
	formData.append('action', 'delete-image');
	formData.append('bien_id', bienId);
	formData.append('filename', filename);

	fetch('api/biens.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (!data.success) {
			showAlert(data.message || 'Erreur', 'danger');
			return;
		}

		// Recharger juste les images du bien sans rouvrir le modal
		fetch(`api/biens.php?action=get&id=${bienId}`)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					displayCurrentImages(currentEditForm, bienId, data.data.images || []);
					showAlert('Image supprimée', 'success');
				}
			});
	})
	.catch(error => {
		console.error('Erreur:', error);
		showAlert('Erreur de connexion', 'danger');
	});
}

/**
 * Soumet le formulaire d'édition
 */
function submitEditForm() {
	const form = document.getElementById('formEdit');
	const formData = new FormData(form);
	formData.append('action', 'update');

	// Supprimer l'input file vide et ajouter les fichiers sélectionnés
	formData.delete('images[]');
	const files = selectedFilesMap.get(form) || [];
	if (files.length > 0) {
		files.forEach(file => {
			formData.append('images[]', file, file.name);
		});
	}

	fetch('api/biens.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (!data.success) {
			showAlert(data.message || 'Erreur lors de la mise à jour', 'danger', form);
			return;
		}

		showAlert('Bien mis à jour avec succès !', 'success');
		bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
		selectedFilesMap.set(form, []);
		const previewContainer = form.querySelector('.image-preview-container');
		if (previewContainer) previewContainer.innerHTML = '';
		loadBiens();
	})
	.catch(error => {
		console.error('Erreur:', error);
		showAlert('Erreur de connexion', 'danger', form);
	});
}

/**
 * Affiche le modal de détails d'un bien
 */
function showDetailModal(id) {
	fetch(`api/biens.php?action=get&id=${id}`)
		.then(response => response.json())
		.then(data => {
			if (!data.success) {
				showAlert('Erreur lors du chargement des détails', 'danger');
				return;
			}

			displayBienDetail(data.data);
		})
		.catch(error => {
			console.error('Erreur:', error);
			showAlert('Erreur de connexion', 'danger');
		});
}

/**
 * Affiche les détails d'un bien dans la modal
 */
function displayBienDetail(bien) {
	const modalContent = document.querySelector('#modalDetailBien .modal-content');

	// Appliquer le style inactif si nécessaire
	if (bien.actif == 0) {
		modalContent.classList.add('bien-inactif');
	} else {
		modalContent.classList.remove('bien-inactif');
	}

	// Titre avec badge
	const statutLabel = `<span class="badge ${bien.statut === 'vente' ? 'badge-vente' : 'badge-location'} me-2">${bien.statut.charAt(0).toUpperCase() + bien.statut.slice(1)}</span>`;
	const inactifLabel = bien.actif == 0 ? ' <span class="text-muted">(Inactif)</span>' : '';
	document.getElementById('modalDetailBienTitle').innerHTML = statutLabel + escapeHtml(bien.titre) + inactifLabel;

	// Images - Carrousel
	const imagesContainer = document.getElementById('modalDetailImages');
	const thumbnailsContainer = document.getElementById('modalDetailThumbnails');
	const carousel = document.getElementById('carouselDetailBien');

	if (bien.images && bien.images.length > 0) {
		// Générer les slides du carrousel
		imagesContainer.innerHTML = bien.images.map((img, index) => `
			<div class="carousel-item ${index === 0 ? 'active' : ''}">
				<img src="${img.url}" class="d-block w-100" alt="${escapeHtml(bien.titre)}" style="max-height: 500px; object-fit: contain;">
			</div>
		`).join('');

		// Générer les vignettes
		thumbnailsContainer.innerHTML = bien.images.map((img, index) => `
			<div class="thumbnail ${index === 0 ? 'active' : ''}" data-bs-target="#carouselDetailBien" data-bs-slide-to="${index}">
				<img src="${img.url}" alt="${escapeHtml(bien.titre)}">
			</div>
		`).join('');

		// Ajouter l'événement pour mettre à jour la vignette active
		const bsCarousel = new bootstrap.Carousel(carousel);
		carousel.addEventListener('slid.bs.carousel', function(e) {
			document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
			document.querySelectorAll('.thumbnail')[e.to].classList.add('active');
		});
	} else {
		imagesContainer.innerHTML = `
			<div class="carousel-item active">
				<div class="bg-secondary d-flex align-items-center justify-content-center text-white" style="height: 500px;">
					<i class="fa-solid fa-image fa-5x opacity-50"></i>
				</div>
			</div>
		`;
		thumbnailsContainer.innerHTML = '';
	}

	// Informations
	document.getElementById('modalDetailLieu').textContent = bien.city || 'Non renseigné';
	document.getElementById('modalDetailSurface').textContent = bien.surface ? `${bien.surface} m²` : 'Non renseigné';
	document.getElementById('modalDetailChambres').textContent = bien.nb_chambres || 'Non renseigné';

	// Personnes (seulement pour les locations)
	const personnesContainer = document.getElementById('modalDetailPersonnesContainer');
	if (bien.statut === 'location' && bien.nb_personnes) {
		personnesContainer.style.display = 'flex';
		document.getElementById('modalDetailPersonnes').textContent = bien.nb_personnes;
	} else {
		personnesContainer.style.display = 'none';
	}

	// Prix
	const prixContainer = document.getElementById('modalDetailPrixContainer');
	if (bien.prix) {
		prixContainer.style.display = 'block';
		document.getElementById('modalDetailPrix').textContent = formatPrice(bien.prix);
	} else {
		prixContainer.style.display = 'none';
	}

	// Description
	document.getElementById('modalDetailDescription').textContent = bien.description || 'Aucune description';

	// Afficher la modal
	new bootstrap.Modal(document.getElementById('modalDetailBien')).show();
}

/**
 * Affiche le modal de suppression
 */
function showDeleteModal(id, titre) {
	currentBienId = id;
	const titleElement = document.getElementById('delete-bien-title');
	if (titleElement) {
		titleElement.textContent = titre;
	}
	new bootstrap.Modal(document.getElementById('modalDelete')).show();
}

/**
 * Supprime un bien
 */
function deleteBien(id) {
	const formData = new FormData();
	formData.append('action', 'delete');
	formData.append('id', id);

	fetch('api/biens.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (!data.success) {
			showAlert(data.message || 'Erreur lors de la suppression', 'danger');
			return;
		}

		showAlert('Bien supprimé avec succès', 'success');
		bootstrap.Modal.getInstance(document.getElementById('modalDelete')).hide();
		loadBiens();
	})
	.catch(error => {
		console.error('Erreur:', error);
		showAlert('Erreur de connexion', 'danger');
	});
}

/**
 * Affiche une alerte
 * @param {string} message - Le message à afficher
 * @param {string} type - Le type d'alerte ('info', 'success', 'warning', 'danger')
 * @param {HTMLElement|null} container - Optionnel: l'élément conteneur (form/modal). Si null, affiche dans la page principale
 */
function showAlert(message, type = 'info', container = null) {
	if (!container) {
		container = document;
	}
	const alertZone = container.querySelector('.alert-zone');

	if (!alertZone) return;

	const alertHTML = `
		<div class="alert alert-${type} alert-dismissible fade show" role="alert">
			<i class="fa-solid fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'}"></i>
			${message}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	`;

	alertZone.innerHTML = alertHTML;

	// Auto-dismiss après 5 secondes
	setTimeout(() => {
		alertZone.innerHTML = '';
	}, 5000);
}

// ============================================================
// SECTION : UTILITAIRES
// ============================================================

/**
 * Échappe le HTML
 */
function escapeHtml(text) {
	const div = document.createElement('div');
	div.textContent = text;
	return div.innerHTML;
}

/**
 * Formate un prix
 */
function formatPrice(price) {
	return new Intl.NumberFormat('fr-FR', {
		style: 'currency',
		currency: 'EUR',
		minimumFractionDigits: 0,
		maximumFractionDigits: 0
	}).format(price);
}

// ============================================================
// SECTION : CONNEXION MODAL
// ============================================================

/**
 * Gère la soumission du formulaire de connexion
 */
function handleLoginForm(e) {
	e.preventDefault();

	const form = document.getElementById('loginForm');
	const formData = new FormData(form);
	const alertZone = document.getElementById('login-alert');

	fetch('api/login.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			// Rediriger vers admin
			window.location.href = 'admin.php';
			return;
		}

		// Afficher l'erreur
		alertZone.innerHTML = `
			<div class="alert alert-danger alert-dismissible fade show">
				<i class="fa-solid fa-triangle-exclamation"></i> ${data.message}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		`;
	})
	.catch(error => {
		console.error('Erreur:', error);
		alertZone.innerHTML = `
			<div class="alert alert-danger alert-dismissible fade show">
				<i class="fa-solid fa-triangle-exclamation"></i> Erreur de connexion
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		`;
	});
}

// ============================================================
// SECTION : RÉSERVATION SMOOBU
// ============================================================

// Variable pour stocker l'instance du script chargé
let smoobuScriptLoaded = false;

/**
 * Charge le script Smoobu une seule fois
 */
function loadSmoobuScript() {
	if (smoobuScriptLoaded) {
		return Promise.resolve();
	}

	return new Promise((resolve, reject) => {
		const script = document.createElement('script');
		script.src = 'https://login.smoobu.com/js/Settings/BookingToolIframe.js';
		script.onload = () => {
			smoobuScriptLoaded = true;
			resolve();
		};
		script.onerror = reject;
		document.head.appendChild(script);
	});
}

/**
 * Ouvre le modal de réservation avec l'iframe Smoobu
 * @param {number|null} apartmentId - ID Smoobu de l'appartement (null pour tous les biens)
 * @param {string|null} title - Titre personnalisé pour le modal
 * @param {boolean} skipShow - Si true, ne pas ouvrir le modal (déjà ouvert)
 */
function openBookingModal(apartmentId = null, title = null, skipShow = false) {
	const modal = document.getElementById('modal_booking');
	const modalTitle = document.getElementById('modal_booking_label');
	const container = document.getElementById('booking-iframe-container');

	if (!modal || !container) {
		return;
	}

	title = title !== '' ? title : null;
	apartmentId = apartmentId !== 0 ? apartmentId : null;

	// Mise à jour du titre
	if (title) {
		modalTitle.textContent = title;
	} else {
		modalTitle.textContent = apartmentId ? 'Réserver ce bien' : 'Réserver votre séjour';
	}

	// Générer un ID unique pour le container iframe
	const iframeId = apartmentId ? `apartmentIframe${apartmentId}` : 'apartmentIframeAll';
	const accountId = SMOOBU_ACCOUNT_ID;
	const iframeUrl = apartmentId
		? `https://login.smoobu.com/fr/booking-tool/iframe/${accountId}/${apartmentId}`
		: `https://login.smoobu.com/fr/booking-tool/iframe/${accountId}`;

	// Afficher un loader pendant le chargement
	container.innerHTML = `
		<div class="text-center py-5">
			<div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
				<span class="visually-hidden">Chargement...</span>
			</div>
			<p class="mt-3 text-muted">Chargement du système de réservation...</p>
		</div>
	`;

	// Charger et initialiser l'iframe Smoobu
	loadSmoobuScript().then(() => {
		if (typeof BookingToolIframe !== 'undefined') {
			// Créer le container de l'iframe
			container.innerHTML = `<div id="${iframeId}"></div>`;

			BookingToolIframe.initialize({
				url: iframeUrl,
				baseUrl: 'https://login.smoobu.com',
				target: `#${iframeId}`
			});
		}
	}).catch(error => {
		console.error('Erreur lors du chargement du script Smoobu:', error);
		container.innerHTML = '<div class="alert alert-danger m-3">Erreur lors du chargement du système de réservation.</div>';
	});

	// Ouvrir le modal seulement si pas déjà ouvert
	if (!skipShow) {
		const bsModal = new bootstrap.Modal(modal);
		bsModal.show();
	}
}

// ============================================================
// SECTION : INITIALISATION
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
	// Charger les biens sur l'index
	const ventesContainer = document.getElementById('ventes-list');
	const locationsContainer = document.getElementById('locations-list');
	if (ventesContainer && locationsContainer) {
		loadIndexBiens();
	}

	// Gérer l'ouverture du modal de réservation
	const bookingModal = document.getElementById('modal_booking');
	if (bookingModal) {
		bookingModal.addEventListener('show.bs.modal', function(event) {
			const container = document.getElementById('booking-iframe-container');
			if (container && !container.hasChildNodes()) {
				openBookingModal(null, null, true);
			}
		});
	}
	// Formulaire de contact (page publique)
	const contactForm = document.getElementById('contactForm');
	if (contactForm) {
		contactForm.addEventListener('submit', handleContactForm);
	}

	// Formulaire de connexion (modal)
	const loginForm = document.getElementById('loginForm');
	if (loginForm) {
		loginForm.addEventListener('submit', handleLoginForm);
	}

	// Administration (page admin)
	const biensList = document.getElementById('biens-list');
	if (biensList) {
		loadBiens();
		initForms();
		initFilters();
	}

	// Bouton de confirmation de synchronisation Smoobu
	const btnConfirmSync = document.getElementById('btnConfirmSync');
	if (btnConfirmSync) {
		btnConfirmSync.addEventListener('click', executeSyncSmoobu);
	}
});
