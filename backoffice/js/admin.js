// File: c:\Users\RESTOS-ADBTS01\Desktop\Arras Go\arrasgo\backoffice\js\admin.js

document.addEventListener("DOMContentLoaded", function () {
  // Fonction pour afficher une modale
  function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = "block";
    }
  }

  // Fonction pour fermer une modale
  function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = "none";
    }
  }

  // Gestion des événements pour les boutons d'ouverture de modale
  const openModalButtons = document.querySelectorAll("[data-modal-open]");
  openModalButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const modalId = this.getAttribute("data-modal-open");
      showModal(modalId);
    });
  });

  // Gestion des événements pour les boutons de fermeture de modale
  const closeModalButtons = document.querySelectorAll("[data-modal-close]");
  closeModalButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const modalId = this.getAttribute("data-modal-close");
      closeModal(modalId);
    });
  });

  // Confirmation avant la suppression d'un parcours
  const deleteButtons = document.querySelectorAll(".delete-parcours");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      const confirmation = confirm(
        "Êtes-vous sûr de vouloir supprimer ce parcours ?"
      );
      if (!confirmation) {
        event.preventDefault();
      }
    });
  });

  const burger = document.getElementById("burger-menu");
  const nav = document.getElementById("nav-admin");
  if (burger && nav) {
    burger.addEventListener("click", function () {
      nav.classList.toggle("open");
    });
    document.addEventListener("click", function (e) {
      if (!nav.contains(e.target) && e.target !== burger) {
        nav.classList.remove("open");
      }
    });
    window.addEventListener("scroll", function () {
      nav.classList.remove("open");
    });
  }
});
