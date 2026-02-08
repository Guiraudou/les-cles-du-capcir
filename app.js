/**
 * Application JavaScript - Les clés du Capcir
 */

// ============================================================
// SECTION : PAGE D'ACCUEIL
// ============================================================

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
	fetch('contact-handler.php', {
		method: 'POST',
		body: formData
	})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				statusDiv.className = 'mt-2 text-success fw-bold';
				statusDiv.textContent = data.message;
				form.reset();
			} else {
				statusDiv.className = 'mt-2 text-danger fw-bold';
				statusDiv.textContent = data.message || 'Une erreur est survenue.';
			}
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

// WeakMap pour stocker les fichiers sélectionnés par formulaire
const selectedFilesMap = new WeakMap();

/**
 * Charge tous les biens
 */
function loadBiens() {
	fetch('api/biens.php?action=list')
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				allBiens = data.data;
				applyFilter();
				updateStats(data.data);
			} else {
				showAlert('Erreur lors du chargement des biens', 'danger');
			}
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
 * Affiche les biens dans la liste
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
			<div class="card bien-card h-100">
				${bien.images && bien.images.length > 0 ? `
					<img src="${bien.images[0].url}"
						 class="bien-image"
						 alt="${escapeHtml(bien.titre)}">
				` : `
					<div class="bien-image bg-secondary d-flex align-items-center justify-content-center text-white">
						<i class="fa-solid fa-image fa-3x opacity-50"></i>
					</div>
				`}

				<div class="card-body d-flex flex-column">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<div class="d-flex gap-2 align-items-center">
							<span class="badge ${bien.statut === 'vente' ? 'badge-vente' : 'badge-location'}">
								${bien.statut.charAt(0).toUpperCase() + bien.statut.slice(1)}
							</span>
							${bien.actif == 0 ? '<span class="badge bg-secondary">Inactif</span>' : ''}
						</div>
						${bien.prix ? `<span class="fw-bold fs-5">${formatPrice(bien.prix)}</span>` : ''}
					</div>

					<h5 class="card-title">${escapeHtml(bien.titre)}</h5>

					<div class="small text-muted mb-2">
						${bien.lieu ? `<i class="fa-solid fa-location-dot"></i> ${escapeHtml(bien.lieu)}<br>` : ''}
						${bien.surface ? `<i class="fa-solid fa-ruler-combined"></i> ${bien.surface} m²` : ''}
						${bien.nb_chambres ? ` • ${bien.nb_chambres} ch.` : ''}
						${bien.statut === 'location' && bien.nb_personnes ? ` • ${bien.nb_personnes} pers.` : ''}
					</div>

					<div class="text-end mt-auto">
						<button class="btn btn-sm btn-info ms-2" onclick="showDetailModal(${bien.id})">
							<i class="fa-solid fa-eye"></i>
						</button>
						<button class="btn btn-sm btn-warning ms-2" onclick="editBien(${bien.id})">
							<i class="fa-solid fa-pen"></i>
						</button>
						<button class="btn btn-sm btn-danger ms-2" onclick="showDeleteModal(${bien.id}, '${escapeHtml(bien.titre)}')">
							<i class="fa-solid fa-trash"></i>
						</button>
					</div>
				</div>
			</div>
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
	}

	// Initialiser l'upload d'images
	initImageUpload(form);

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
			showModalAlert(
				form,
				`L'image ${file.name} dépasse 5MB`,
				'warning'
			);
			return false;
		}
		return true;
	});

	// Ajouter les fichiers à la liste
	const currentFiles = selectedFilesMap.get(form) || [];
	selectedFilesMap.set(form, [...currentFiles, ...validFiles]);

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
		if (data.success) {
			showAlert('Bien ajouté avec succès !', 'success');
			bootstrap.Modal.getInstance(document.getElementById('modalAdd')).hide();
			form.reset();
			selectedFilesMap.set(form, []);
			const previewContainer = form.querySelector('.image-preview-container');
			if (previewContainer) previewContainer.innerHTML = '';
			loadBiens();
		} else {
			showModalAlert(form, data.message || 'Erreur lors de l\'ajout', 'danger');
		}
	})
	.catch(error => {
		console.error('Erreur:', error);
		showModalAlert(form, 'Erreur de connexion', 'danger');
	});
}

/**
 * Ouvre le modal d'édition
 */
function editBien(id) {
	fetch(`api/biens.php?action=get&id=${id}`)
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				fillEditForm(data.data);
				new bootstrap.Modal(document.getElementById('modalEdit')).show();
			} else {
				showAlert('Bien non trouvé', 'danger');
			}
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
	form.querySelector('[name="lieu"]').value = bien.lieu || '';
	form.querySelector('[name="surface"]').value = bien.surface || '';
	form.querySelector('[name="description"]').value = bien.description || '';
	form.querySelector('[name="prix"]').value = bien.prix || '';

	// Nombre de chambres (peut être dans vente ou location)
	const nbChambresInputs = form.querySelectorAll('[name="nb_chambres"]');
	nbChambresInputs.forEach(input => input.value = bien.nb_chambres || '');

	form.querySelector('[name="nb_personnes"]').value = bien.nb_personnes || '';
	form.querySelector('[name="ordre"]').value = bien.ordre || 0;
	form.querySelector('[name="actif"]').value = bien.actif;

	// Afficher les champs selon le statut
	toggleFieldsByStatut(form, bien.statut);

	// Réinitialiser les fichiers sélectionnés
	selectedFilesMap.set(form, []);
	const previewContainer = form.querySelector('.image-preview-container');
	if (previewContainer) previewContainer.innerHTML = '';

	// Afficher les images actuelles
	displayCurrentImages(bien.id, bien.images || []);
}

/**
 * Affiche les images actuelles dans le formulaire d'édition
 */
function displayCurrentImages(bienId, images) {
	const container = document.getElementById('edit-images-current');
	if (!container) return;

	if (images.length === 0) {
		container.innerHTML = '<p class="text-muted">Aucune image</p>';
		return;
	}

	container.innerHTML = images.map(img => `
		<div class="image-preview">
			<img src="${img.url}" alt="Image">
			<button type="button" class="btn btn-danger btn-sm btn-remove"
					onclick="deleteImage(${bienId}, '${img.filename}')">
				<i class="fa-solid fa-times"></i>
			</button>
		</div>
	`).join('');
}

/**
 * Supprime une image
 */
function deleteImage(bienId, filename) {
	if (!confirm('Supprimer cette image ?')) return;

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
		if (data.success) {
			editBien(bienId);
			showAlert('Image supprimée', 'success');
		} else {
			showAlert(data.message || 'Erreur', 'danger');
		}
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
		if (data.success) {
			showAlert('Bien mis à jour avec succès !', 'success');
			bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
			selectedFilesMap.set(form, []);
			const previewContainer = form.querySelector('.image-preview-container');
			if (previewContainer) previewContainer.innerHTML = '';
			loadBiens();
		} else {
			showModalAlert(form, data.message || 'Erreur lors de la mise à jour', 'danger');
		}
	})
	.catch(error => {
		console.error('Erreur:', error);
		showModalAlert(form, 'Erreur de connexion', 'danger');
	});
}

/**
 * Affiche le modal de détails d'un bien
 */
function showDetailModal(id) {
	fetch(`api/biens.php?action=get&id=${id}`)
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				displayBienDetail(data.data);
			} else {
				showAlert('Erreur lors du chargement des détails', 'danger');
			}
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
	document.getElementById('modalDetailLieu').textContent = bien.lieu || 'Non renseigné';
	document.getElementById('modalDetailSurface').textContent = bien.surface ? `${bien.surface} m²` : 'Non renseigné';
	document.getElementById('modalDetailChambres').textContent = bien.nb_chambres || 'Non renseigné';
	document.getElementById('modalDetailPersonnes').textContent = bien.nb_personnes || 'Non renseigné';

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
		if (data.success) {
			showAlert('Bien supprimé avec succès', 'success');
			bootstrap.Modal.getInstance(document.getElementById('modalDelete')).hide();
			loadBiens();
		} else {
			showAlert(data.message || 'Erreur lors de la suppression', 'danger');
		}
	})
	.catch(error => {
		console.error('Erreur:', error);
		showAlert('Erreur de connexion', 'danger');
	});
}

/**
 * Affiche une alerte
 */
function showAlert(message, type = 'info') {
	const alertZone = document.getElementById('alert-zone');
	if (!alertZone) return;

	const alertId = 'alert-' + Date.now();

	const alertHTML = `
		<div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
			<i class="fa-solid fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'}"></i>
			${message}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	`;

	alertZone.insertAdjacentHTML('beforeend', alertHTML);

	// Auto-dismiss après 5 secondes
	setTimeout(() => {
		const alert = document.getElementById(alertId);
		if (alert) {
			const bsAlert = new bootstrap.Alert(alert);
			bsAlert.close();
		}
	}, 5000);
}

/**
 * Affiche une alerte dans un modal
 */
function showModalAlert(form, message, type = 'info') {
	const modal = form.closest('.modal');
	if (!modal) return;

	const alertZone = modal.querySelector('.modal-alert-zone');
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
		} else {
			// Afficher l'erreur
			alertZone.innerHTML = `
				<div class="alert alert-danger alert-dismissible fade show">
					<i class="fa-solid fa-triangle-exclamation"></i> ${data.message}
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			`;
		}
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
// SECTION : INITIALISATION
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
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
});
