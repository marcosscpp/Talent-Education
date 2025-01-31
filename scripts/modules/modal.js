import triggerEvent from "./triggerEvent";

export default class Modal {
  constructor(openButton, closeButton, containerModal, activeClass = "active") {
    this.openButtons = document.querySelectorAll(openButton);
    this.closeButton = document.querySelector(closeButton);
    this.containerModal = document.querySelector(containerModal);
    this.activeClass = activeClass;

    this.eventToggleModal = this.eventToggleModal.bind(this);
    this.outsideClick = this.outsideClick.bind(this);
  }

  toggleModal() {
    this.containerModal.classList.toggle(this.activeClass);
  }

  eventToggleModal(event) {
    event.preventDefault();
    triggerEvent("Abriu o Formulário");
    this.toggleModal();
  }

  outsideClick(event) {
    if (event.target === this.containerModal) {
      this.toggleModal();
    }
  }

  addModalEvents() {
    this.openButtons.forEach((openBtn) => {
      openBtn.addEventListener("click", this.eventToggleModal);
    });
    this.closeButton.addEventListener("click", this.eventToggleModal);
    this.containerModal.addEventListener("click", this.outsideClick);
  }

  init() {
    if (this.openButtons && this.closeButton && this.containerModal) {
      this.addModalEvents();
    }
    return this;
  }
}
