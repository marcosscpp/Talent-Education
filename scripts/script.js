import Aos from "aos";
import "aos/dist/aos.css";

import Modal from "./modules/modal";
import Form from "./modules/form";
import Faq from "./modules/faq";
import triggerEvent from "./modules/triggerEvent";

triggerEvent("Pageview");

Aos.init({
  // once: true,
});

const modal = new Modal(
  "[data-modal='open']",
  "[data-modal='close']",
  "[data-modal='container']"
).init();
const form = new Form("[data-form]");
const faq = new Faq("[data-faq] dt").init();
