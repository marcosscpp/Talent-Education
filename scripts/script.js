import Faq from "./modules/faq";
import Modal from "./modules/modal";
import Form from "./modules/form";

const faq = new Faq("[data-faq] dt").init();
const modal = new Modal(
  "[data-modal='open']",
  "[data-modal='close']",
  "[data-modal='container']"
).init();
const form = new Form("[data-form]")