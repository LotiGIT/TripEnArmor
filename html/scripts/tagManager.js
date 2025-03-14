// Liste de tags culturels
const culturalTags = [
  "Culturel",
  "Patrimoine",
  "Histoire",
  "Urbain",
  "Nature",
  "Plein air",
  "Sport",
  "Nautique",
  "Gastronomie",
  "Musée",
  "Atelier",
  "Musique",
  "Famille",
  "Cinéma",
  "Cirque",
  "Son et lumière",
  "Humour",
];

// Liste de tags cuisine
const cuisineTags = [
  "Française",
  "Fruits de mer",
  "Asiatique",
  "Indienne",
  "Italienne",
  "Gastronomique",
  "Restauration rapide",
  "Crêperie",
];

class TagManager {
  constructor(inputId, existingTags = []) {
    this.tagInput = document.getElementById(inputId);
    this.tagContainer = {
      removeChild(activityType, child) {
        this[activityType].removeChild(child);
      },
      activite: document.getElementById("activiteTags"),
      visite: document.getElementById("visiteTags"),
      spectacle: document.getElementById("spectacleTags"),
      parc_attraction: document.getElementById("parcAttractionTags"),
      restauration: document.getElementById("restaurationTags"),
    };
    this.availableTags = []; // Pour stocker les tags disponibles
    this.addedTags = {
      has(tag, activityType) {
        return this[activityType].includes(tag);
      },
      delete(tag) {
        for (const key in this) {
          if (Array.isArray(this[key])) {
            const index = this[key].indexOf(tag);
            if (index > -1) {
              this[key].splice(index, 1);
            }
          }
        }
      },
      activite: [],
      visite: [],
      spectacle: [],
      parc_attraction: [],
      restauration: [],
    }; // Pour stocker les tags ajoutés sous forme de dictionnaire

    this.init(existingTags);
  }

  init(existingTags) {
    for (const key in this.tagContainer) {
      if (typeof this.tagContainer[key] === "string") {
        this.tagContainer[key].classList.add("hidden");
      }
    }

    this.changeAvailableTags(document.getElementById("activityType").value);

    this.tagInput.addEventListener("change", () => {
      const tag = this.tagInput.value;
      const activityType = document.getElementById("activityType").value;
      this.addTag(tag, activityType);
      this.tagInput.value = "";
    });

    document
      .getElementById("activityType")
      .addEventListener("change", (event) => {
        this.changeAvailableTags(event.target.value);
      });

    // Initialize with existing tags
    if (existingTags.length > 0) {
      existingTags.forEach((tag) => {
        const tagName = typeof tag === "object" ? tag.nom : tag;
        const activityType = document.getElementById("activityType").value;
        this.addTag(tagName, activityType);
      });
    }
  }

  changeAvailableTags(activityType) {
    this.availableTags = [];
    switch (activityType) {
      case "activite":
        this.availableTags = culturalTags;
        break;
      case "visite":
        this.availableTags = culturalTags;
        break;
      case "spectacle":
        this.availableTags = culturalTags;
        break;
      case "parc_attraction":
        this.availableTags = culturalTags;
        break;
      case "restauration":
        this.availableTags = cuisineTags;
        break;
      default:
        this.availableTags = []; // Aucune option sélectionnée
        break;
    }
    this.updateSuggestionList(); // Mettre à jour la liste après changement
  }

  updateSuggestionList() {
    this.tagInput.innerHTML = `<option value="" class="hidden" selected>Rechercher un tag</option>`;
    const activityType = document.getElementById("activityType").value;
    const limitedTags = this.availableTags.filter(
      (tag) => !this.addedTags.has(tag, activityType)
    ); // Limiter à 5 éléments
    limitedTags.forEach((tag) => {
      const listItem = document.createElement("option");

      const tagName = typeof tag === "object" ? tag.nom : tag;

      listItem.value = tagName;
      listItem.append(tagName);
      listItem.classList.add(
        "suggestion-item",
        "p-2",
        "cursor-pointer",
        "hover:bg-gray-200"
      );
      listItem.setAttribute("data-tag", tagName);

      this.tagInput.appendChild(listItem);
    });
  }

  addTag(tag, activityType) {
    if (this.addedTags.has(tag, activityType)) {
      alert("Ce tag a déjà été ajouté.");
      return;
    }

    // Ajout d'un champ caché pour le tag dans le formulaire
    const form = document.getElementById("formulaire");

    const hiddenTagInput = document.createElement("input");
    hiddenTagInput.type = "hidden";
    hiddenTagInput.name = `tags[${activityType}][]`; // Stocker chaque tag selon le type d'activité
    hiddenTagInput.value = tag;
    form.appendChild(hiddenTagInput);

    // Création du tag visuel dans l'interface
    const tagDiv = document.createElement("div");
    tagDiv.textContent = tag;
    tagDiv.classList.add(
      "bg-secondary",
      "text-white",
      "py-1",
      "px-3",
      "mr-2",
      "flex",
      "items-center"
    );

    const removeBtn = document.createElement("span");
    removeBtn.textContent = "X";
    removeBtn.classList.add("remove-tag", "ml-8", "cursor-pointer");
    removeBtn.onclick = () => {
      // Suppression du tag visuel
      this.tagContainer.removeChild(activityType, tagDiv);
      // Suppression du tag de la liste des tags ajoutés
      this.addedTags.delete(tag);
    };

    tagDiv.appendChild(removeBtn);
    this.tagContainer[activityType].appendChild(tagDiv);

    // Ajouter le tag à la liste des tags ajoutés
    this.addedTags[activityType].push(tag);
  }
}
