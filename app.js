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

/**
 * Charge tous les biens
 */
function loadBiens() {
	fetch('api/biens.php?action=list')
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				displayBiens(data.data);
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
					<img src="../uploads/biens/${bien.images[0].filename}"
						 class="bien-image"
						 alt="${escapeHtml(bien.titre)}">
				` : `
					<div class="bien-image bg-secondary d-flex align-items-center justify-content-center text-white">
						<i class="fa-solid fa-image fa-3x opacity-50"></i>
					</div>
				`}

				<div class="card-body">
					<div class="d-flex justify-content-between align-items-start mb-2">
						<span class="badge ${bien.statut === 'vente' ? 'badge-vente' : 'badge-location'}">
							${bien.statut.charAt(0).toUpperCase() + bien.statut.slice(1)}
						</span>
						${bien.actif == 0 ? '<span class="badge bg-secondary">Inactif</span>' : ''}
					</div>

					<h5 class="card-title">${escapeHtml(bien.titre)}</h5>

					<div class="small text-muted mb-2">
						${bien.lieu ? `<i class="fa-solid fa-location-dot"></i> ${escapeHtml(bien.lieu)}<br>` : ''}
						${bien.surface ? `<i class="fa-solid fa-ruler-combined"></i> ${bien.surface} m²` : ''}
						${bien.statut === 'vente' && bien.nb_chambres ? ` • ${bien.nb_chambres} ch.` : ''}
						${bien.statut === 'location' && bien.nb_personnes ? ` • ${bien.nb_personnes} pers.` : ''}
					</div>

					${bien.prix ? `<p class="fw-bold fs-5 mb-3">${formatPrice(bien.prix)}</p>` : ''}

					<div class="text-end">
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
 * Initialise les formulaires d'administration
 */
function initForms() {
	// Gestion du statut dans le formulaire d'ajout
	const addStatut = document.getElementById('add-statut');
	if (addStatut) {
		addStatut.addEventListener('change', function() {
			toggleFieldsByStatut('add', this.value);
		});
	}

	// Gestion du statut dans le formulaire d'édition
	const editStatut = document.getElementById('edit-statut');
	if (editStatut) {
		editStatut.addEventListener('change', function() {
			toggleFieldsByStatut('edit', this.value);
		});
	}

	// Soumission du formulaire d'ajout
	const formAdd = document.getElementById('formAdd');
	if (formAdd) {
		formAdd.addEventListener('submit', function(e) {
			e.preventDefault();
			submitAddForm();
		});
	}

	// Soumission du formulaire d'édition
	const formEdit = document.getElementById('formEdit');
	if (formEdit) {
		formEdit.addEventListener('submit', function(e) {
			e.preventDefault();
			submitEditForm();
		});
	}

	// Confirmation de suppression
	const btnConfirmDelete = document.getElementById('btnConfirmDelete');
	if (btnConfirmDelete) {
		btnConfirmDelete.addEventListener('click', function() {
			deleteBien(currentBienId);
		});
	}
}

/**
 * Affiche/masque les champs selon le statut
 */
function toggleFieldsByStatut(prefix, statut) {
	const venteFields = document.getElementById(`${prefix}-vente-fields`);
	const locationFields = document.getElementById(`${prefix}-location-fields`);

	if (!venteFields || !locationFields) return;

	venteFields.classList.remove('active');
	locationFields.classList.remove('active');

	if (statut === 'vente') {
		venteFields.classList.add('active');
	} else if (statut === 'location') {
		locationFields.classList.add('active');
	}
}

/**
 * Soumet le formulaire d'ajout
 */
function submitAddForm() {
	const form = document.getElementById('formAdd');
	const formData = new FormData(form);
	formData.append('action', 'create');

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
			loadBiens();
		} else {
			showAlert(data.message || 'Erreur lors de l\'ajout', 'danger');
		}
	})
	.catch(error => {
		console.error('Erreur:', error);
		showAlert('Erreur de connexion', 'danger');
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
	document.getElementById('edit-id').value = bien.id;
	document.getElementById('edit-statut').value = bien.statut;
	document.getElementById('edit-titre').value = bien.titre;
	document.getElementById('edit-lieu').value = bien.lieu || '';
	document.getElementById('edit-surface').value = bien.surface || '';
	document.getElementById('edit-description').value = bien.description || '';
	document.getElementById('edit-prix').value = bien.prix || '';
	document.getElementById('edit-nb-chambres').value = bien.nb_chambres || '';
	document.getElementById('edit-nb-chambres-loc').value = bien.nb_chambres || '';
	document.getElementById('edit-nb-personnes').value = bien.nb_personnes || '';
	document.getElementById('edit-ordre').value = bien.ordre || 0;
	document.getElementById('edit-actif').value = bien.actif;

	// Afficher les champs selon le statut
	toggleFieldsByStatut('edit', bien.statut);

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
			<img src="../uploads/biens/${img.filename}" alt="Image">
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

	fetch('api/biens.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			showAlert('Bien mis à jour avec succès !', 'success');
			bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
			loadBiens();
		} else {
			showAlert(data.message || 'Erreur lors de la mise à jour', 'danger');
		}
	})
	.catch(error => {
		console.error('Erreur:', error);
		showAlert('Erreur de connexion', 'danger');
	});
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
	}
});
