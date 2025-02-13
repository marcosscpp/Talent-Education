import SmoothScroll from "./modules/smooth-scroll";
import Aos from "aos";
import "aos/dist/aos.css";

import Form from "./modules/form";
import Faq from "./modules/faq";
import triggerEvent from "./modules/triggerEvent";

const smoothScroll = new SmoothScroll("[href^='#']").init();
triggerEvent("Pageview");

Aos.init({
  once: true,
});

const form = new Form("[data-form]");
const faq = new Faq("[data-faq] dt").init();
